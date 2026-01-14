<?php
include("cfg.php");

if (!isset($_POST['emailregister'], $_POST['passwordregister'])) {
    die('Missing registration data');
}

$email = trim($_POST['emailregister']);
$password = $_POST['passwordregister'];

$log = fopen("debug.txt", "a");

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailRegex, $email)) {
    fwrite($log, "\nInvalid email format");
    fclose($log);
    exit;
}

$passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
if (!preg_match($passwordRegex, $password)) {
    fwrite($log, "\nWeak password");
    fclose($log);
    exit;
}

$sql = "SELECT COUNT(*) FROM Users WHERE Login = :email";
$stmt = $dbh->prepare($sql);
$stmt->execute([':email' => $email]);

if ($stmt->fetchColumn() > 0) {
    fwrite($log, "\nEmail already registered");
    fclose($log);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (Login, Password) VALUES (:email, :pass)";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ':email' => $email,
    ':pass'  => $passwordHash
]);

fwrite($log, "\nUser registered successfully: $email");
fclose($log);

header("Location: ../index.php");
exit;
?>