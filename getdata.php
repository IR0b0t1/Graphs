<?php
include("cfg.php");

$c=$dbh->exec("set names utf8");
$data = array();

$sth= $dbh->query("select * from Temperature");
$serverData=$sth->fetchAll();

$width = $_GET['width'];
$height = $_GET['height'];
$margin = $_GET['margin'];
$days = count($serverData);

function calculateX($days, $margin, $width, $day) {
    return $margin + (($width - 2 * $margin)/$days)*$day;
}

function calculateY($margin, $height, $temperature, $isIllness, $isDone) {
    if($isDone == false || $isIllness == true || $temperature == 36){
        return $height - $margin;
    } else {
        return $height - ($margin + (($height - 2 * $margin) * ($temperature - 36.0)));
    }
}
echo "<map name='graphmap'>\n";
for($i=0;$i<count($serverData);$i++) {
    $x = calculateX($days, $margin, $width, $serverData[$i]['Day']);
    $y = calculateY($margin, $height, $serverData[$i]['Temperature'], $serverData[$i]['isIllness'], $serverData[$i]['isDone']);
    echo "<area shape='circle' coords=" . $x . "," . $y . ",5 alt='graphPoint' onclick='nodeClicked(" . $serverData[$i]['Day'] . ", " . $serverData[$i]['Temperature'] . ", " . $serverData[$i]['isIllness'] . ", " . $serverData[$i]['isDone'] . ", " . $serverData[$i]['ID'] . ")' class='clicker'>\n";
}
echo "</map>";
?>