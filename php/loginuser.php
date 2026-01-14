<?php
include("cfg.php");
session_start();

if (!isset($_POST['emaillogin'], $_POST['passwordlogin'])) {
    die('Missing credentials');
}

$email = trim($_POST['emaillogin']);
$password = $_POST['passwordlogin'];
$log = fopen("debug.txt", "a");

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
session_regenerate_id(true);
fwrite($log, "\nUser logged successfully: $email");

header("Location: ../index.php");
exit;
