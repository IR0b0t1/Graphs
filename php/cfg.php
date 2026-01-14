<?php
$dbname = "temperature";
$host = "localhost";
$user = "root";
$password = "";
$dns = "mysql:dbname=$dbname;host=$host";
$dbh = new PDO($dns, $user, $password);
?>