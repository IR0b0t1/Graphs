<?php
    include('cfg.php');
    session_start();
    
    $c = $dbh->exec("set names utf8");

    $graphNo = $_GET['graphNo'];
    $day = $_GET['day'];

    $log = fopen('../log/recorddelete-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');

    fwrite($log, "graphNo: $graphNo\ndayL $day\n");

    $sql = "SELECT ID FROM Graphs WHERE UserID = :userID AND GraphNo = :graphNo";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':userID' =>$_SESSION['userID'],
        ':graphNo' => $graphNo
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $graphID = $result[0][0];

    $sql = "DELETE FROM temperature WHERE GraphID = :graphID AND Day = :day";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID'  => $graphID,
        ':day' => $day
    ]);

    fwrite($log, "Deleted record with day = $day and GraphID = $graphID from 'temperature' table");

    $sql = "UPDATE temperature SET Day=Day-1 WHERE GraphID = :graphID AND Day > :day";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID,
        ':day' => $day
    ]);

    fwrite($log, "Updated records with day > $day and GraphID = $graphID from 'temperature' table");

    fclose($log);
?>