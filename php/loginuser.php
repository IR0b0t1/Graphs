<?php
include("cfg.php");
session_start();

if (!isset($_POST['emaillogin'], $_POST['passwordlogin'])) {
    die('Missing credentials');
}

$email = trim($_POST['emaillogin']);
$password = $_POST['passwordlogin'];
$log = fopen("../debug.txt", "a");

$sql = "SELECT ID, Password FROM Users WHERE Login = :email LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['Password'])) {
    fwrite($log, "\nInvalid email or password");
    die('Invalid email or password');
}

$_SESSION['userID'] = (int)$user['ID'];
$sql = "SELECT COUNT(*) AS Graphs FROM Graphs WHERE UserID = :userID";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':userID', $user['ID'], PDO::PARAM_INT);
$stmt->execute();

$graphsCount = $stmt->fetch(PDO::FETCH_ASSOC);
fwrite($log, "\nUser has ".$graphsCount['Graphs']." graphs");

$_SESSION['graphCount'] = (int)$graphsCount['Graphs'];
fwrite($log, "\nSession data - user ID: ".$_SESSION['userID'].", graphs amount: ".$_SESSION['graphCount']);

session_regenerate_id(true);
fwrite($log, "\nUser logged successfully: $email");

header("Location: ../index.php");
exit;
