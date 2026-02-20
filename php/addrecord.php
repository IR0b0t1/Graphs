<?php
include('cfg.php');
session_start();

$c = $dbh->exec("set names utf8");

$graphNo = $_GET['graphNo'];
$temperature = $_GET['temperature'];
$isIllness = $_GET['isIll'];
$isDone = $_GET['isDone'];

$isIllness = $isIllness == 'true' ? 1 : 0;
$isDone = $isDone == 'true' ? 1 : 0;

if($temperature > 37) {
    $temperature = 37;
}
if($temperature < 36) {
    $temperature = 36;
}

$dataQuery = "SELECT ID FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";

$stmt = $dbh->prepare($dataQuery);
$stmt->bindParam(':graphNo', $graphNo, PDO::PARAM_INT);
$stmt->bindParam(':userID', $_SESSION["userID"], PDO::PARAM_INT);
$stmt->execute();
$serverData = $stmt->fetchAll(PDO::FETCH_NUM);
$graphID = $serverData[0][0];

$stmt = $dbh->prepare("SELECT MAX(Day) AS 'Day' FROM temperature WHERE GraphID = :graphID");
$stmt->execute([
    ':graphID' => $graphID
]);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$day = $result[0]['Day'] + 1;

$stmt = $dbh->prepare("INSERT INTO temperature(`GraphID`, `Day`, `Temperature`, `isDone`, `isIllness`) 
VALUES (:graphID, :day, :temperature, :isDone, :isIllness)");

$stmt->execute([
    ':graphID' => $graphID,
    ':temperature' => $temperature,
    ':isDone' => $isDone,
    ':isIllness' => $isIllness,
    ':day' => $day
]);
?>