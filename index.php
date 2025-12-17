<?php
include("cfg.php");
header('Content-Type: image/png');

session_start();
$c=$dbh->exec("set names utf8");
$data = array();

// Zmienne
$width = $_GET['width'];
$height = $_GET['height'];
$margin = $_GET['margin'];
$days = $_GET['days'];
$minTemp = 36.0;
$maxTemp = 37.0;
$temp = 36.0;
$lineNumber = 0;
$place = $width-105;
$lineMarginsHorizontal = ($height - 2 * $margin)/5;
$lineMarginsVertical = ($width - 2 * $margin)/$days;

// Klasa Day do ogarniania poszczególnych dni w bazie danych
class Day {
    public $day;
    public $temperature;
    public $isDone;
    public $isIllness;

    function set_day($day) {
        $this->id = $day;
    }
    function get_day() {
        return $this->id;
    }

    function set_temperature($temperature) {
        $this->temperature = $temperature;
    }
    function get_temperature() {
        return $this->temperature;
    }

    function set_isDone($isDone) {
        $this->isDone = $isDone;
    }
    function get_isDone() {
        return $this->isDone;
    }

    function set_isIllness($isIllness) {
        $this->isIllness = $isIllness;
    }
    function get_isIllness() {
        return $this->isIllness;
    }
}

$dataQuery = `
    SELECT
        ID, Day, Temperature, isDone, isIllness
    FROM Temperature
    LEFT JOIN Graphs ON Graphs.ID = Temperature.GraphID
    WHERE Graphs.ID = :id AND Graphs.UserID = :userID
    ORDER BY Temperature.ID ASC
`
$dataStmt = $dbh->prepare($dataQuery);
$dataStmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$dataStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_INT);
$dataStmt->execute();
$serverData = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

for($i=0;$i<$days;$i++) {
    if($i<count($serverData)) {
        $databaseData = new Day();
        $databaseData->set_day($serverData[$i]['Day']);
        $databaseData->set_temperature($serverData[$i]['Temperature']);
        $databaseData->set_isDone($serverData[$i]['isDone']);
        $databaseData->set_isIllness($serverData[$i]['isIllness']);
        $data[] = $databaseData;
    } else {
        $databaseData = new Day();
        $databaseData->set_day($i+1);
        $databaseData->set_temperature(36);
        $databaseData->set_isDone(0);
        $databaseData->set_isIllness(0);
        $data[] = $databaseData;
    }
}

function calculateX($days, $margin, $width, $day) {
    return $margin + (($width - 2 * $margin)/$days)*$day;
}

function calculateY($margin, $height, $temperature, $isIllness, $isDone) {
    if($isDone == false || $isIllness == true){
        return $height - $margin;
    } else {
        return $height - ($margin + (($height - 2 * $margin) * ($temperature - 36.0)));
    }
}

// niesamowity generator markerów
function generateMarkers($im, $data) {
    global $days, $id, $temperature, $isDone, $isIllness, $margin, $width, $height;

    for($j=0;$j<count($data);$j++) {
        $markerID = $data[$j]->get_day();
        $markerTemperature = $data[$j]->get_temperature();
        $markerIsIllness = $data[$j]->get_isIllness();
        $markerIsDone = $data[$j]->get_isDone();
        $x = calculateX($days, $margin, $width, $markerID);
        $y = calculateY($margin, $height, $markerTemperature, $markerIsIllness, $markerIsDone);
        $color = imagecolorallocate($im, 0, 0, 255);
        if($markerIsDone == false) {
            $color = imagecolorallocate($im, 50, 50, 50);
        }
        if($markerIsIllness == true){
            $color = imagecolorallocate($im, 255, 0, 0);
        }
        imagefilledellipse($im, $x, $y, 9, 9, $color);
    }
}

// Niesamowity generator linii
function addMarkerLines($im, $data) {
    $blue = imagecolorallocate($im, 0, 0, 255);
    global $days, $id, $temperature, $isDone, $isIllness, $margin, $width, $height;
    
    $x1 = 0;
    $y1 = 0;
    $wasPreviousValid = false;
    
    for($i = 0; $i < count($data); $i++) {
        if ($data[$i]->get_isDone() === 1 && $data[$i]->get_isIllness() === 0) {
            $x2 = calculateX($days, $margin, $width, $data[$i]->get_day());
            $y2 = calculateY($margin, $height, $data[$i]->get_temperature(), $data[$i]->get_isIllness(), $data[$i]->get_isDone());
            if ($i > 0 && $wasPreviousValid == true) {
                imageline($im, $x1, $y1, $x2, $y2, $blue);
            }
            
            $x1 = $x2;
            $y1 = $y2;
            $wasPreviousValid = true;
        } else{
            $wasPreviousValid = false;
            $x1 = 0;
            $y1 = 0;
            $x2 = 0;
            $y2 = 0;
        }
    }
}

// Cała reszta
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im, 255, 255, 255);
$red = imagecolorallocate($im, 255, 0, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$gray = imagecolorallocate($im, 50, 50, 50);
$bk = imagecolorallocate($im, 0, 0, 0);
$grayLine = [$gray, $gray, $gray, $white, $white, $white];
$redLine = [$red, $white];
imagefilledrectangle($im, 0, 0, $width, $height, $white);
imagestringup($im, 7, 25, $height/2, "Temperatura", $bk);
imagestring($im, 7, $width/2-50, $height-75, "Dzien pomiaru", $bk);

for ($x = $height-$margin; $x >= $margin; $x-=$lineMarginsHorizontal) {
    imagesetstyle($im, $grayLine);
    imageline($im, 100, $x, $width-100, $x, IMG_COLOR_STYLED);
    imagestring($im, 10, 50, $x-10, $temp, $bk);
    $temp += .2;
}

for ($y = $width-$margin; $y >= $margin; $y-=$lineMarginsVertical) {
    imagesetstyle($im, $grayLine);
    imageline($im, $y, 100, $y, $height-100, IMG_COLOR_STYLED);
    $lineNumber++;
}

for ($z = $days; $z>=1; $z--){
    imagestring($im, 10, $place, $height-90, $z, $bk);
    $place -= $lineMarginsVertical;
}
generateMarkers($im, $data);
addMarkerLines($im, $data);

// To robi śmieszne kreseczki
// for ($y = 0; $y <= $width; $y+=1) {
//     imagesetstyle($im, $grayLine);
//     imageline($im, $y, 0, 1000, $y, IMG_COLOR_STYLED);
// }
// for ($y = 0; $y <= $height; $y+=1) {
//     imagesetstyle($im, $grayLine);
//     imageline($im, 0, $y, $y, 1000, IMG_COLOR_STYLED);
// }
// for ($y = 0; $y <= $height; $y+=1) {
//     imagesetstyle($im, $grayLine);
//     imageline($im, $y, 0, 1000, $y, IMG_COLOR_STYLED);
// }
// for ($y = 0; $y <= $width; $y+=1) {
//     imagesetstyle($im, $grayLine);
//     imageline($im, 0, $y, $y, 1000, IMG_COLOR_STYLED);
// }

imagepng($im);
?>