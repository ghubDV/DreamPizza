<?php
//db connection

$dbserv="your_db_server";
$dbuser="your_db_user";
$dbpass="your_db_pass";
$dbname="your_db_name";
$db_connect=new mysqli($dbserv, $dbuser, $dbpass, $dbname);

if($db_connect->connect_errno){
    die("Error trying to connect to database");
}

?>
