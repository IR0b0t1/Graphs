<?php
include("cfg.php");
session_start();

if (!isset($_GET['token'])) {
    die('Brak tokenu weryfikacyjnego.');
}

$token = $_GET['token'];

$sql = "SELECT ID, IsVerified FROM Users WHERE VerificationToken = :token LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->execute([':token' => $token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Nieprawidłowy lub wygasły token weryfikacyjny.');
}

if ((int)$user['IsVerified'] === 1) {
    echo 'Twoje konto jest już zweryfikowane. Możesz się zalogować.';
    exit;
}

$updateSql = "UPDATE Users SET IsVerified = 1, VerificationToken = NULL WHERE ID = :id";
$updateStmt = $dbh->prepare($updateSql);
$updateStmt->execute([':id' => $user['ID']]);

echo 'Konto zostało pomyślnie zweryfikowane. Możesz teraz się zalogować.';
?> 