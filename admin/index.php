<?php

include_once("classes/FL.php");
include_once("classes/VT.php");

$return = VT::table("test")->select();

var_dump($return) ;
//print_r(VT::$whereVals);