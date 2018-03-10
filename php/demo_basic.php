<?php
error_reporting( E_ALL  & ~E_NOTICE);

//API for web scrapper
require_once("utils.php");

//base call structures
$config=[];
$urls=[];


//URLS to process
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=1";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=2";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=3";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=4";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=5";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=6";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=7";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=8";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=9";
$urls[]="https://www.ratemds.com/best-doctors/bc/vancouver/family-gp/?page=10";


//Process Configurations 

//Task: Extract doctor names
//Extract class name (attribute.class) and content (textContent) from links (a)
//Use class1 as alias for class attribute and name for content
//Filter only tags with class name equal to 'search-item-doctor-link'
$config[]=[
	"tags"=>["a"],
	"elements"=>["class1"=>"attribute.class","name"=>"textContent"],
	"fields"=>["name"],
	"filter"=>["class1"=>"search-item-doctor-link"]
];

//Task: Extract doctor ratings
//Extract class name (attribute.class) and rating (attribute.title) from spans (span)
//Use 'class2' as alias for class attribute and 'rating' for title attribute
//Filter only tags with class name equal to 'star-rating'
$config[]=[
	"tags"=>["span"],
	"elements"=>["class2"=>"attribute.class","rating"=>"attribute.title"],
	"fields"=>["rating"],
	"filter"=>["class2"=>"star-rating"]
];

//Task: Extract doctor comments
//Extract class name (attribute.class) and comment (textContent) from paragraphs (p)
//Use 'class3' as alias for class attribute and 'comment' for text content
//Filter only tags with class name equal to 'rating-comment'
$config[]=[
	"tags"=>["p"],
	"elements"=>["class3"=>"attribute.class","comment"=>"textContent"],
	"fields"=>["comment"],
	"filter"=>["class3"=>"rating-comment"]
];

echo "Processing URLs<br>".implode("<br>",$urls);

//process and merge all three structures in one
//setting the 3rd parameter in false will return three separated structures
$final=processDocuments($urls,$config,true);

//style for the table cells only (bordered)
echo "<style> td { border: 1px solid #eee} </style>";

//prints the HTML table with result
printTable($final);

