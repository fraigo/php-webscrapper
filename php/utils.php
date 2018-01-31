<?php

/**
* WebScrapper PHP utility
* https://github.com/fraigo/webscrapper/
* Author     : Francisco Igor (franciscoigor@gmail.com)
* License    : Apache 2.0 
* Last update: 2018-01-31
*
* A Work in progress
* To-do:
* - Cache folder cleaning
* - JSON output
* - Database support
* - Better filtering options
* - More content extraction types
*/

define("CACHE_FOLDER","cache");

/**
* getCache Get a cached copy of data identified by ID. 
* If no data is cached for $id, then returns the $default value specified or null if not specified.
* Each data is stored (cached) as a file in CACHE_FOLDER, using a $datetime timestamp and an Id using the MD5 sum of $id.
* The timestamp is Ymd (YearMonthDay), so the cache expires when the date changes.
* To-do: Clean cache for previous timestamps
* To-do: Implement cache expiration based on timeout (n minutes) using file modification time.
* Parameters:
*  $id      string  Cache id
*  $default mixed   Optional) Default value when not in cache or expired
* Return value:
*  Cached data (mixed) 
*/
function getCache($id,$default=null){
    $datetime=date("Ymd");
    $fileid=$datetime."_".md5($id);
    if (!file_exists(CACHE_FOLDER."/$fileid")){
        return $default;
    }
    $data=unserialize(file_get_contents(CACHE_FOLDER."/$fileid"));
    if ($data){
        return $data;
    }
    return $default;
}

/**
* setCache: Save data using a cache id.
* Each data is stored (cached) as a file in CACHE_FOLDER, using a $datetime timestamp and an Id using the MD5 sum of $id.
* The data stored is in serialized form, so, you can store any serializable data structure (primitive types, array, object).
* Parameters:
*  $id      string  Cache id
*  $data    mixed   Data to be stored
* Return value:
*  None
*/
function setCache($id,$data){
    $datetime=date("YmdH");
    $fileid=$datetime."_".md5($id);
    file_put_contents(CACHE_FOLDER."/$fileid",serialize($data));
}

/**
* getResponse: Retrieve data from URL or a cached one if available.
* If there is a cached version of the url not expired, it returns a cached copy.
* If there is not a cached version, ir retrieves the URL response using HTTP¨and cURL API
* Parameters:
*  $url string  URL of the document to retrieve
* Return value:
*  HTTP response (HTML content) 
*/
function getResponse($url){
    $response=getCache($url);
    if (!$response){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // We'll parse redirect url from header.
        curl_setopt($ch, CURLOPT_HEADER, FALSE); 
        // Get redirect url and follow it.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
        $response = curl_exec($ch);
        setCache($url,$response);
        curl_close($ch);
    }
    return $response;
}

/*
* getDocument: Gets an HTML document (DomDocument) object from the HTML code from response.
* The document elements can be retrieved using the DomDocument API
* Parameters:
*  $response    string  HTML code (response from getResponse() call)
* Return value:
*  A DOMDocument object
*/
function getDocument($response){
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($response);
    return $dom;
}

/*
* getItems: get HTML Document nodes using tag names and a list of content values.
* Atributes can be any HTML attribute (ex: a, div, span)
* Content values can be: 
*  - textContent (current text-only content of the tag)
*  - attribute.name (tag attributes using name, ex: attribute.class)
*  - html (html outer content of the tag)
*  - tagname (current tag name)
* Parameters:
*  $doc     DOMDocument Document object (from getDocument() call)
*  $tags    array       Array of tags to search for (ex: ["a","span"])
*  $values  array       Array of alias => value to extact. 
*                       The value will be extracted using the column alias assigned. Example:
*                       ["classname"=>"attribute.class","content"=>"textContent","mytag"=>"tagname"]
* Return value:
*  An array of rows. Earch row with the fields alias specified in $values.
*  Example: [ ["classname"=>"client-name","content"=>"John Doe","mytag"=>"span"] ]
*/
function getItems($doc,$tags,$values=null){
    if(!$values){
        $values=["text"=>"textContent"];
    } 
    $results=[];
    foreach($tags as $tag){
        $items = $doc->getElementsByTagName($tag);
        foreach($items as $item){
            $result=[];
            foreach($values as $alias=>$value){
                list($prop,$index)=explode(".",$value);
                if ($prop=="tagname"){
                    $result[$alias]=$tag;
                }
                if ($prop=="attribute"){
                    foreach($item->attributes as $attr){
                        if($attr->name==$index){
                            $result[$alias]=$attr->value;
                        }
                        
                    }
                }
                if ($prop=="textContent"){
                    $result[$alias]=$item->textContent;
                }
                if ($prop=="html"){
                    $result[$alias]=htmlentities($doc->saveHTML($item));
                }
            }
            $results[]=$result;
        }
    }
    return $results;
    
}

/*
* filterItems: Gets a subset of columns from a 2D array filtering by column values.
* Using the source $items, only returns the $columns specified,and filtering only rows with columnname=value
* Parameters:
*  $items   array   A 2D array of items (from the getItems() call)
*  $cols    array   An array of column names to extract from $items (ex: ["content","rate"])
*  $cfg     array   A set of filters in "column"=>"value" format (ex: ["classname"=>"client-data"]) 
*                   Any row that match the column=>value is included in the returning result
* Return value:
*  A 2D filtered array
*/
function filterItems($items,$cols,$cfg){
    $result=[];
    foreach($items as $item){
        foreach($cfg as $key=>$val){
            if($item[$key]==$val){
                $row=[];
                foreach($cols as $col){
                    $row[$col]=$item[$col];
                }
                $result[]=$row;
                continue;
            }
        }
    }
    return $result;
}

/*
* printTable: Print a 2D array as HTML table
* Parameters:
*  $data    array   Array of rows to be printed
*  $header  array   (optional) Array of column names. 
*                   If none is specified, it uses the column names
*/
function printTable($data,$header=null){
    if (!$header){
        $headers=array_keys($data[0]);
    }
    echo "<table>";
    printRow($headers,"#");
    foreach($data as $idx=>$item){
        printRow($item,$idx+1);
    }
    echo "</table>";
}

/*
* printRow: Prints a single HTML row using array values.
* Parameters:
*  $row   : Array of elements to be printed as cells
*  $index : If set, adds an extra initial column with index value
*/
function printRow($row,$index=null){
    echo "<tr>";
    if($index!==null){
        echo "<td>$index</td>";
    }
    foreach($row as $item){
        echo "<td>$item</td>";
    }
    echo "</tr>";

}

/*
* mergeData: Merges 2d data from two 2D arrays, row by row
* Both arrays must be the same row length.
* Parameters: 
*  $data1: First set of data
*  $data2: Second set of data
* Return value:
*  A unique 2D array with merged columns of data
*/
function mergeData($data1,$data2){
    if (count($data1)!=count($data2)){
        die("Row numbers do not match");
    }
    foreach($data1 as $key=>$row){
        $data1[$key]=array_merge($row,$data2[$key]);
    }
    return $data1;
}

/*
* processDocuments: Processes the document URLs and retrieves structured data
* Parameters: 
*  $urls: array of URLs to be processed
*  $config: array of process configuration. It includes:
*    - tags      array  Tags to process
*    - elements  array  Array of alias=>elements to be processed
*    - fields    array  Column names to be returned
*    - filters   array  Content filters alias=>valuue
*/
function processDocuments($urls,$config,$merged=true){
    
    $results=[];

    foreach($config as $idxcfg=>$cfg){
        $results[$idxcfg]=[];
    }
    foreach($urls as $idx=>$url){
        $response=getResponse($url);
        $doc=getDocument($response);
        foreach($config as $idxcfg=>$cfg){
            $resultItems=getItems($doc,$cfg["tags"],$cfg["elements"]);
            $result=filterItems($resultItems,$cfg["fields"],$cfg["filter"]);
            $results[$idxcfg]=array_merge($results[$idxcfg],$result);
        }
        
    }

    if (!$merged){
        return $results;
    }
    
    $final=$results[0];
    for($idx=1; $idx<count($results);$idx++){
        $final=mergeData($final,$results[$idx]);
    }
    
    return $final;
}

