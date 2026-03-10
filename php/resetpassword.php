<?php
include("cfg.php");

if (!isset($_POST['token'], $_POST['password'])) {
    http_response_code(400);
    echo 'Brak wymaganych danych.';
    exit;
}

$token = $_POST['token'];
$password = $_POST['password'];

$passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/';
if (!preg_match($passwordRegex, $password)) {
    http_response_code(400);
    echo 'Hasło nie spełnia wymagań bezpieczeństwa.';
    exit;
}

$sql = "SELECT ID FROM Users WHERE PasswordResetToken = :token AND PasswordResetExpires > NOW() LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->execute([':token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(400);
    echo 'Token resetowania hasła jest nieprawidłowy lub wygasł.';
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$updateSql = "UPDATE Users 
              SET Password = :password, PasswordResetToken = NULL, PasswordResetExpires = NULL 
              WHERE ID = :id";
$updateStmt = $dbh->prepare($updateSql);
$updateStmt->execute([
    ':password' => $passwordHash,
    ':id' => $user['ID']
]);

echo 'Hasło zostało pomyślnie zmienione. Możesz się teraz zalogować.';
?> 

