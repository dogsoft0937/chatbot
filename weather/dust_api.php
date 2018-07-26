<?php
error_reporting(0);                           // 에러보고 표시하지 않음
header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding    
$countryCode = "openapi.airkorea.or.kr"; 
$MENU_URL = "openapi/services/rest/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty";          // weathermap site
$serviceKey="h2MyQ0pom36BAOwX9DkxFX4oXhRQyc5umxAnKJMstSsYz8DyD6hB0VPV785uO7qIOIKXRlBXXXlSs0n1AUA21Q%3D%3D";             // weather location code
$numOfRows="1"; //한페이지 결과수
$stationName="노원구"; //측정소 이름
$dataTerm="DAILY";//요청 데이터기간(일주일:WEEK,한달:MONTH)
$ver="1.3";
$URL="http://".$countryCode."/".$MENU_URL."?serviceKey=".$serviceKey."&numOfRows=".$numOfRows.
"&stationName=".$stationName."&dataTerm=".$dataTerm."&ver=".$ver."&_returnType=json";
// http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty?
// serviceKey=h2MyQ0pom36BAOwX9DkxFX4oXhRQyc5umxAnKJMstSsYz8DyD6hB0VPV785uO7qIOIKXRlBXXXlSs0n1AUA21Q%3D%3D
// &numOfRows=1&stationName=노원구&dataTerm=DAILY&ver=1.3
$json=file_get_contents($URL);
$result=json_decode($json);
$nfdust=$result->list[0]->pm10Value;
$tfdust=$result->list[0]->pm10Value24;
$nUdust=$result->list[0]->pm25Value;
$tUdust=$result->list[0]->pm25Value24;
$array = array(
    '현재 미세먼지 농도' => $nfdust, //now fine dust
    '24시간 평균 미세먼지 농도' => $tfdust, //today fine dust
    '현재 초미세먼지 농도' => $nUdust, //now Ultrafine dust
    '24시간 평균 초미세먼지 농도' => $tUdust //today Ultrafine dust
);
// json encoding
$json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
// echo json
echo $json;

?>