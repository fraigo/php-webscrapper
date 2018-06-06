<?php
error_reporting( E_ALL  & ~E_NOTICE);

//API for web scrapper
require_once("utils.php");

//base call structures
$config=[];
$urls=[];


//URLS to process
$urls[]="https://github.com/explore?since=monthly";


//Process Configurations 

//Task: Extract project name
//Extract class name (attribute.class) and content (textContent) from links (a)
//Use class1 as alias for class attribute and name for content
//Filter only project names (part1)

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


function isStar($value){
	return substr($value,-6)=="gazers";
}

function isNetwork($value){
	return substr($value,-7)=="network";
}

$config[]=[
	"tags"=>["a"],
	"elements"=>["class1"=>"attribute.class","project_stars"=>"textContent","link_stars"=>"attribute.href"],
	"fields"=>["project_stars","link_stars"],
	"filter"=>["class1"=>"d-inline-block link-gray mr-4","link_stars"=>["isStar"]]
];
$config[]=[
	"tags"=>["a"],
	"elements"=>["class1"=>"attribute.class","project_network"=>"textContent","link_network"=>"attribute.href"],
	"fields"=>["project_network","link_network"],
	"filter"=>["class1"=>"d-inline-block link-gray mr-4","link_network"=>["isnetwork"]]
];


//process and merge all structures in one
$final1=processDocuments($urls,$config,true);

//style for the table cells only (bordered)
echo "<style> td { border: 1px solid #eee} </style>";

//prints the HTML table with result
printTable($final1);

