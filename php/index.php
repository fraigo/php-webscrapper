<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP Simple Web Scrapper</title>
</head>
<body>

<h1>Demo cases</h1>

<h2>Basic Demo</h2>

<ol>
    <li>Analize a sequence of page results for rating of doctors in Vancouver, BC. </li>
    <li>Extract some relevant information (name, user rating, comments) </li>
    <li>Structure the data and return a list of fields extracted</li>
    <li>Show the structured data (table view)</li>
</ol>

<a href="demo_basic.php">View demo</a>


<h2>Content Filter Demo</h2>

<ol>
    <li>Extract a list of the top GitHub Projects.</li>
    <li>Extract some relevant information (project name, stars, forks) using function filters</li>
    <li>Structure the data and return a list of fields extracted</li>
    <li>Show the structured data (table view)</li>
</ol>

<a href="demo_filter.php">View demo</a>


<h2>Content transformation Demo</h2>

<ol>
    <li>Extract a list of the news headers from BBC News.</li>
    <li>Extract some relevant information (News headers, link URLs) using a transformation function (in this case to fill the relative URLS with the base URL)</li>
    <li>Structure the data and return a list of fields extracted</li>
    <li>Show the structured data (table view)</li>
</ol>

<a href="demo_bbcnews.php">View demo</a>

</body>
</html>
