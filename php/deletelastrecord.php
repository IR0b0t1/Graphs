<?php
    include('cfg.php');
    session_start();

    $c = $dbh->exec("set names utf8");

    $graphNo = $_GET['graphNo'];

    $log = fopen('../log/recorddeletelast-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');

    fwrite($log, "graphNo: $graphNo\nuserID: ".$_SESSION['userID']."\n");

    $sql = "SELECT ID FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphNo' => $graphNo,
        ':userID' => $_SESSION['userID']
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $graphID = $result[0][0];

    fwrite($log, "graphID: $graphID\n");

    $sql = "SELECT MAX(Day) FROM temperature WHERE GraphID = :graphID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID
    ]);

    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $day = $result[0][0];

    fwrite($log, "day: $day\n");

    $sql = "DELETE FROM temperature WHERE GraphID = :graphID AND Day = :day";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID'  => $graphID,
        ':day' => $day
    ]);

    fwrite("Deleted record with day = $day and GraphID = $graphID from 'temperature' table");

    fclose($log);
?>