<?php

define("BASEURL","https://www.bbc.com");
define("FULLURL",BASEURL."/news/technology");

//API for web scrapper
require_once("utils.php");

//base call structures
$config=[];
$urls=[FULLURL];


//Process Configurations 

//Task: Extract news titles
//Extract class name (as class1) and content (as title) from links (a)
//Filter tags with class name (class1) equals to 'c-entry-box--compact__title'
$config[]=[
	"tags"=>["span"],
	"elements"=>["class1"=>"attribute.class","title"=>"textContent"],
	"fields"=>["title"],
	"filter"=>["class1"=>"title-link__title-text"]
];

//Task: Extract news images
//Extract class name (as class1) and content (as title) from links (a)
//Filter tags with class name (class1) equals to 'c-entry-box--compact__title'


function baseRef($url){
  if (strpos($url,"/")===0){
    return cleanText(BASEURL.$url);
	}
	if (strpos($url,"://")===false){
    return cleanText(FULLURL.$url);
  }
  return cleanText("$url");
}

$config[]=[
	"tags"=>["a"],
"elements"=>["class1"=>"attribute.class","link"=>"attribute.href->baseRef"],
	"fields"=>["link"],
	"filter"=>["class1"=>"title-link"]
];


//process and merge all three structures in one
//setting the 3rd parameter in false will return three separated structures
$final=processDocuments($urls,$config,true);

//prints the HTML table with result
printTable($final);

