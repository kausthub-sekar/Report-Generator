<?php

//cloud credentials
//$con = mysql_connect("cloudmysql.cnjwrz2mq8zc.ap-southeast-1.rds.amazonaws.com","wegcloud","wegcloud_123") or die("Unable to connect to SERVER.sdfs.!");
//$db = mysql_select_db("weg_appvigil_dev",$con) or die("Unable to connect to DATABASE..!");


//localhost db
$con = mysql_connect("localhost","root","wegilant@123456") or die("Unable to connect to SERVER.sdfs.!");
$db = mysql_select_db("weg_appvigil_dev",$con) or die("Unable to connect to DATABASE..!");

?>
