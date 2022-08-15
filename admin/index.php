<?php

include_once("classes/FL.php");
include_once("classes/VT.php");

// SELECT * FROM table Where title LIKE '%a%' ORDER BY id DESC

VT::table("table")->where("title", " LIKE ", "%a%")->orderBy(["id"])->limit(3,5);

echo VT::$limit;