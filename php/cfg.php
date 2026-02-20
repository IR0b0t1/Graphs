<?php
$dbname = "temperature";
$host = "localhost";
$user = "root";
$password = "";
$dns = "mysql:dbname=$dbname;host=$host";
$dbh = new PDO($dns, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>