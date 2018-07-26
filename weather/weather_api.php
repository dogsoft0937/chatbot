<?php
error_reporting(0);                           // error reporting disable
header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding    

$countryCode = "api.openweathermap.org"; 
$MENU_URL = "data/2.5/weather";          // weathermap site
$weatherCode =  "1835848";             // weather location code
$appid="873398ad6edf855b1cd831c642308663"; //valized key
$units="metric";
//http://api.openweathermap.org/data/2.5/weather?id=1835848&appid=873398ad6edf855b1cd831c642308663&units=metric
// url for today
$URL="http://" . $countryCode . "/" . $MENU_URL . "?id=" . $weatherCode . "&appid=" . $appid."&units=".$units;
$json=file_get_contents($URL);
$result=json_decode($json, true);
$temp=$result['main']['temp'];
$temp_min=$result['main']['temp_min'];
$temp_max=$result['main']['temp_max'];
// array
$array = array(
    '기온' => $temp,
    '최저기온' => $temp_min,
    '최고기온' => $temp_max
);
// json encoding
$json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
// echo json
echo $json;

?>