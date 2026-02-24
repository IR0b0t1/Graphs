<?php
    include('cfg.php');
    
    $c = $dbh->exec("set names utf8");

    $graphID = $_GET['graphID'];

    $log = fopen('../log/recorddeletelast-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');

    fwrite($log, "graphID: $graphID\n");

    $sql = "SELECT MAX(Day) FROM temperature WHERE GraphID = :graphID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID
    ]);

    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $day = $result[0][0];

    $sql = "DELETE FROM temperature WHERE GraphID = :graphID AND Day = :day";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID'  => $graphID,
        ':day' => $day
    ]);

    fwrite("Deleted record with day = $day and GraphID = $graphID from 'temperature' table");

    fclose($log);
?>