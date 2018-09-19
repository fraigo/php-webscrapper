# WebScrapper (PHP)
Web scrapper utility API  to extract structured data from web pages

These utilities can be used to **retrieve**, **extract**, **filter** and **merge** data from web sites


Usage
------

1. Define a **list of urls** from the same site to process
    * Copy or generate the URLs for **distinct pages** of the same listing, for example: 
       * http://site/listing?page=1 
       * http://site/listing?page=2
    * Save this URLs in an array . For example: 
    * `$urls = [ "http://site/listing?page=1", "http://site/listing?page=2" ];`
2. Define a set of **data to extract** from the site
    * Search for **relevant information** in the content (ex: names, values, comments)
     * Using your **browser**, **inspect** these elements and try to find  the **container tag** and a common **identification** (ex: ***class*** attribute)
     * Define a processing configuration with:
         * **Tag(s)** name(s) to extract (ex: `span`, `a`, `div`)
         * **Elements** to extract from these tags (ex: *textContent*, *attributes* by name) and apply functions to transform data 
           * attribute.name : Extract the contents of a tag attribute (Example: `attribute.id`)
           * textContent : Extract the whole text content of a tag 
           * ->transform : Append to make a data transformation using a local function name (Example: `attribute.href->fixAttribute`)
         * **Filters** to select only the right information to process
      * Write a processing configuration  structure (array) using your configuration (one or more configuration items). For example:
>

     $cfg=[ 	
       [	"tags"=>["span"],
    	 	"elements"=>["class2"=>"attribute.class", "rating"=>"attribute.title"],
    		"fields"=>["rating"],
    		"filter"=>["class2"=>"star-rating"] 
       ] 
    ]

3. Call the web scrapper process using the **URLs** and **configuration**.
    * `processDocuments($urls,$cfg);`
 4. You can obtain a data structure with extracted values 

|#|name|rating|comment|
|--|--|--|--|
|1|John Doe|4.77|Awesome guy. Keep it up !!|
|2|Mahatma Ghandi|4.64|So pleasant and helpful...|
|3|Barbara Streisand|4.92|Awesome singer!|


## Demos

* php/demo_basic.php : Basic demo using a doctor ratings page
* php/demo_filter.php : Demo from GitHub site using functions to filter content using custom logic
* php/demo_bbcnews.php : Demo for bbcnews.com to extract news headers and links using data transformation functions to extract imformation.

