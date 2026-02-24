<?php
    include('cfg.php');
    
    $c = $dbh->exec("set names utf8");

    $graphID = $_GET['graphID'];
    $day = $_GET['day'];

    $log = fopen('../log/recorddelete-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');

    fwrite($log, "graphID: $graphID\ndayL $day\n");

    $sql = "DELETE FROM temperature WHERE GraphID = :graphID AND Day = :day";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID'  => $graphID,
        ':day' => $day
    ]);

    fwrite("Deleted record with day = $day and GraphID = $graphID from 'temperature' table");

    fclose($log);
?>