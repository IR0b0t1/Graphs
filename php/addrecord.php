<?php
include('cfg.php');
$graphID = $_GET('graphID');
$temperature = $_GET('temperature');
$isIll = $_GET('isIll');
$isDone = $_GET('isDone');

$isIllness = $isIllness == 'true' ? 1 : 0;
$isDone = $isDone == 'true' ? 1 : 0;

if($temperature > 37) {
    $temperature = 37;
}
if($temperature < 36) {
    $temperature = 36;
}

$c = $dbh->exec("set names utf8");

$query = $dbh->preprare("SELECT MAX(Day) AS 'Day' FROM temperature WHERE GraphID = :graphID");
$query->execute([
    ':graphID' => $graphID
]);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$day = $result[0] + 1;

$stmt = $dbh->prepare("INSERT INTO temperature(`GraphID`, `Day`, `Temperature`, `isDone`, `isIllness`) 
VALUES (NULL, :graphID, :day, :temperature, :isDone, :isIllness)");
$stmt->execute([
    ':graphID' => $graphID,
    ':temperature' => $temperature,
    ':isDone' => $isDone,
    ':isIllness' => $isIllness,
    ':day' => $day
]);
?>