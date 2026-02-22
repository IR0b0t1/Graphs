<?php
include("cfg.php");
session_start();

$c = $dbh->exec("set names utf8");

$day = $_GET['day'];
$temperature = round($_GET['temperature'], 1);
$isIllness = $_GET['isIllness'];
$isDone = $_GET['isDone'];
$graphNo = $_GET['graphNo'];

$isIllness = $isIllness == 'true' ? 1 : 0;
$isDone = $isDone == 'true' ? 1 : 0;
if($temperature > 37) {
    $temperature = 37;
}
if($temperature < 36) {
    $temperature = 36;
}

$stmt = $dbh->prepare("SELECT ID FROM Graphs WHERE UserID = :userID AND GraphNo = :graphNo");
$stmt->execute([
    ':userID' => $_SESSION['userID'],
    ':graphNo' => $graphNo
]);

$serverData = $stmt->fetchAll(PDO::FETCH_NUM);
$graphID = $serverData[0][0];

$stmt = $dbh->prepare("UPDATE Temperature 
                    SET Temperature = :temperature, 
                        isDone = :isDone, 
                        isIllness = :isIllness 
                    WHERE Day = :day AND GraphID = :graphID");
                    
$stmt->execute([
    ':temperature' => $temperature,
    ':isDone' => $isDone,
    ':isIllness' => $isIllness,
    ':day' => $day,
    ':graphID' => $graphID
]);
?>
