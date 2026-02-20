<?php
include('cfg.php');
session_start();

$c = $dbh->exec("set names utf8");

$daysAmount = $_POST['daysAmount'];
$graphName = $_POST['graphName'];

$sql = "SELECT MAX(GraphNo) FROM Graphs WHERE UserID = :userID";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':userID', $_SESSION['userID']);
$stmt->execute();
$serverData = $stmt->fetchAll(PDO::FETCH_NUM);

$graphNo = $serverData[0][0] + 1;

$log = fopen('addgraphlogs-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');
fwrite($log, "daysAmount: $daysAmount,\ngraphName: $graphName,\ngraphNo: $graphNo\n\n");

$sql = "INSERT INTO Graphs (`Name`, `UserID`, `GraphNo`) VALUES (:name, :userID, :graphNo)";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ':name' => $graphName,
    ':userID' => $_SESSION['userID'],
    ':graphNo' => $graphNo
]);

$sql = "SELECT ID FROM Graphs WHERE UserID = :userID AND GraphNo = :graphNo";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ':userID' => $_SESSION['userID'],
    ':graphNo' => $graphNo
]);
$serverData = $stmt->fetchAll(PDO::FETCH_NUM);
$graphID = $serverData[0][0];

for($i = 1; $i <= $daysAmount; $i++) {
    $sql = "INSERT INTO temperature (`GraphID`, `Day`, `Temperature`, `isDone`, `isIllness`) VALUES (:graphID, :day, 36, 0, 0)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID,
        ':day' => $i
    ]);

    fwrite($log, "Day no. $i\nGraphID: $graphID\nTemperature: 36\nIsDone: 0\nIsIllness: 0\n\n");
}
fwrite($log, "addgraph.php closes");
fclose($log);
?>