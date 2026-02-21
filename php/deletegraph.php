<?php
    include("cfg.php");
    session_start();

    $c = $dbh->exec("set names utf8");

    $graphNo = $_GET['graphNo'];

    $log = fopen('../log/graphdelete-'.date('Y-m-d').'-'.time().'.txt', 'w') or die('Unable to open file!');

    fwrite($log, "graphNo: $graphNo\nuserID: ".$_SESSION['userID']."\n");

    $sql = "SELECT ID FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphNo' => $graphNo,
        ':userID' => $_SESSION['userID']
    ]);
    $serverData = $stmt->fetchAll(PDO::FETCH_NUM);
    $graphID = $serverData[0][0];

    fwrite($log, "graphID: $graphID\n");

    $sql = "DELETE FROM temperature WHERE GraphID = :graphID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID
    ]);
    
    fwrite($log, "All records from table 'Temperatures' with GraphID = $graphID were deleted\n");

    $sql = "DELETE FROM Graphs WHERE ID = :graphID";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ':graphID' => $graphID
    ]);

    fwrite($log, "A record from table 'Graphs' with ID = $graphID was deleted\n");
    fwrite($log, "deletegraph closes");

    fclose($log);
    header("Location: ../index.php?graphNo=".$graphNo-1);
?>