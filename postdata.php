<?php
include("cfg.php");

$day = $_GET['day'];
$temperature = $_GET['temperature'];
$isIllness = $_GET['isIllness'];
$isDone = $_GET['isDone'];

$isIllness = $isIllness == 'true' ? 1 : 0;
$isDone = $isDone == 'true' ? 1 : 0;
if($temperature > 37) {
    $temperature = 37;
}
if($temperature < 36) {
    $temperature = 36;
}

$c = $dbh->exec("set names utf8");

$stmt = $dbh->prepare("UPDATE Temperature 
                    SET Temperature = :temperature, 
                        isDone = :isDone, 
                        isIllness = :isIllness 
                    WHERE Day = :day");
$stmt->execute([
    ':temperature' => $temperature,
    ':isDone' => $isDone,
    ':isIllness' => $isIllness,
    ':day' => $day
]);
?>
