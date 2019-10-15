<?php
error_reporting( E_ALL  & ~E_NOTICE);

//API for web scrapper
require_once("scraper.php");

//base call structures
$config=[];
$urls=[];


//URLS to process
$urls[]="https://github.com/explore?since=monthly";


//Process Configurations 

//Task1: Extract project name
//Extract class name (attribute.class) and content (textContent) from links (a)
//Use class1 as alias for class attribute and name for content
//Filter only project names (part1) using a filter function isProject()

function isProject($value){
	if ($value=="f4 lh-condensed mb-1") return true;
	if ($value=="f3") return true;
	return false;
}

$config[]=[
	"tags"=>["h1","h3"],
	"elements"=>["class1"=>"attribute.class","project_name"=>"textContent"],
	"fields"=>["project_name"],
	"filter"=>["class1"=>["isProject"]]
];



//Task2: Extract project stars 
//Extract class name (attribute.class), content (textContent) and link (attribute.href) from links (a)
//Use "class1" as alias for class attribute and "link_stars" for the link
//Filter only specific class names (d-inline-block link-gray mr-4) and links with a filter function isStar()


function isStar($value){
	return substr($value,-6)=="gazers";
}

$config[]=[
	"tags"=>["a"],
	"elements"=>["class1"=>"attribute.class","project_stars"=>"textContent","link_stars"=>"attribute.href"],
	"fields"=>["project_stars","link_stars"],
	"filter"=>["class1"=>"d-inline-block link-gray mr-4","link_stars"=>["isStar"]]
];

//Task3: Extract project network 
//Extract class name (attribute.class), content (textContent) and link (attribute.href) from links (a)
//Use "class1" as alias for class attribute and "link_stars" for the link
//Filter only specific class names (d-inline-block link-gray mr-4) and links with a filter function isNetwork()

function isNetwork($value){
	return substr($value,-7)=="network";
}

$config[]=[
	"tags"=>["a"],
	"elements"=>["class1"=>"attribute.class","project_network"=>"textContent","link_network"=>"attribute.href"],
	"fields"=>["project_network","link_network"],
	"filter"=>["class1"=>"d-inline-block link-gray mr-4","link_network"=>["isnetwork"]]
];


//process and merge all structures in one
$final1=processDocuments($urls,$config,true);

//prints the HTML table with result
printTable($final1);

