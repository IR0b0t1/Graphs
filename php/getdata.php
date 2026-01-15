<?php
include("cfg.php");
session_start();

$width  = (int)$_GET['width'];
$height = (int)$_GET['height'];
$margin = (int)$_GET['margin'];
$days   = (int)$_GET['days'];

$sql = "
    SELECT COUNT(Day) AS Days FROM Temperature
    INNER JOIN Graphs ON Graphs.ID = Temperature.GraphID
    WHERE Graphs.ID = :id
      AND Graphs.UserID = :userID 
"
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
$stmt->execute();
$serverData = $stmt->fetchAll(PDO::FETCH_ASSOC)

$sql = "
    SELECT
        Temperature.ID,
        Temperature.Day,
        Temperature.Temperature,
        Temperature.isDone,
        Temperature.isIllness
    FROM Temperature
    INNER JOIN Graphs ON Graphs.ID = Temperature.GraphID
    WHERE Graphs.ID = :id
      AND Graphs.UserID = :userID
    ORDER BY Temperature.Day ASC
";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
$stmt->execute();
$serverData = $stmt->fetchAll(PDO::FETCH_ASSOC);

function calculateX($days, $margin, $width, $day) {
    return (int)($margin + (($width - 2 * $margin) / $days) * $day);
}

function calculateY($margin, $height, $temperature, $isIllness, $isDone) { 
    if($isDone == false || $isIllness == true){ 
        return $height - $margin; 
        } 
    else { 
        return $height - ($margin + (($height - 2 * $margin) * ($temperature - 36.0))); 
        } 
}

echo "<map name='graphmap'>\n";

foreach ($serverData as $row) {
    $x = calculateX($days, $margin, $width, $row['Day']);
    $y = calculateY(
        $margin,
        $height,
        $row['Temperature'],
        $row['isIllness'],
        $row['isDone']
    );

    echo "<area
        shape='circle'
        coords='{$x},{$y},5'
        alt='graphPoint'
        class='clicker'
        onclick='nodeClicked(
            {$row['Day']},
            {$row['Temperature']},
            {$row['isIllness']},
            {$row['isDone']},
            {$row['ID']}
        )'
    >\n";
}

echo "</map>";
