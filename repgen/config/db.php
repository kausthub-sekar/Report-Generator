<?php

//cloud credentials
//$con = mysql_connect("cloudmysql.cnjwrz2mq8zc.ap-southeast-1.rds.amazonaws.com","wegcloud","wegcloud_123") or die("Unable to connect to SERVER.sdfs.!");
//$db = mysql_select_db("weg_appvigil_dev",$con) or die("Unable to connect to DATABASE..!");

echo " i am in file";
//localhost db
$con = mysql_connect("localhost","root","") or die("Unable to connect to SERVER..!");
echo " connected";
$db = mysql_select_db("weg_appvigil_dev",$con) or die("Unable to connect to DATABASE..!");
echo " selected db";

//PDO based db connectivity
/*
$hostname = "localhost:8080";
$username = "root";
$passwd = "";
try {
	$dbh=new PDO("mysql:host=$hostname;dbname=mysql", $username, $passwd);
	echo "Connected to database";
}
catch (PDOException $e) {
	echo $e->getMessage();
}*/
echo " i am at the end of file";
?>
