<?php
/**
* meal_api.php
* Created: Tuesday, Jan 30, 2018
* 
* Juneyoung KANG <juneyoung@juneyoung.kr>
* Gyoha High School
*
* Creates a tomorrow school meal JSON file from the NEIS webpage.
* Github : https://github.com/Juneyoung-Kang/school-meal/
*
* How to use?
* http://juneyoung.kr/api/school-meal/meal_api_tomorrow.php?countryCode=stu.goe.go.kr&schulCode=J100004922&insttNm=교하고등학교&schulCrseScCode=4&schMmealScCode=2
* 
* For more information, visit github and see README.md
*
* Licensed under The MIT License
*/

error_reporting(0);                           // error reporting disable
header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding

require "simple_html_dom.php";                // use 'simple_html_dom.php'

$countryCode = $_GET['countryCode'];          // local office of education website
$schulCode =  $_GET['schulCode'];             // school code
$insttNm = $_GET['insttNm'];                  // school name
$schulCrseScCode = $_GET['schulCrseScCode'];  // school levels code
$schMmealScCode = $_GET['schMmealScCode'];    // meal kinds code
$time = time();
$tomorrow = date("Y-m-d", strtotime("+33 hours", $time));
$schYmd = $tomorrow;

$MENU_URL = "sts_sci_md01_001.do";            // view weekly table

// url for today
$URL="http://" . $countryCode . "/" . $MENU_URL . "?schulCode=" . $schulCode . "&insttNm=" . urlencode( $insttNm ) . "&schulCrseScCode=" . $schulCrseScCode . "&schMmealScCode=" . $schMmealScCode . "&schYmd=" . $schYmd;

// DOMDocument
$dom=new DOMDocument;

// load HTML file 
$html=$dom->loadHTMLFile($URL);
$dom->preserveWhiteSpace=false;

// get elements by tag name
$table=$dom->getElementsByTagName('table');
$tbody=$table->item(0)->getElementsByTagName('tbody');
$rows=$tbody->item(0)->getElementsByTagName('tr');
$cols=$rows->item(1)->getElementsByTagName('td');

// reset date format
$schYmd=str_replace(".", "-", $schYmd);

// get day
$day=date('w', strtotime($schYmd));

// check blank has values
if($cols->item($day)->nodeValue==null){
    echo '';
}else{
    $final=$cols->item($day)->nodeValue;
}

// replace unnecessary characters
$final=preg_replace("/[0-9]/", "", $final);
$final=str_replace(".","\\n",$final);
$final=str_replace("\\n\\n\\n\\n","\\n",$final);
$final=str_replace("\\n\\n\\n","\\n",$final);
$final=str_replace("\\n\\n","\\n",$final);;
$final=str_replace(" ","",$final);


// no meal
if($final==null){
    $final="내일은 급식이 없습니다.";
}

$schYmd=substr_replace($schYmd,"년",4,1);
$schYmd=substr_replace($schYmd,"월",9,1);
$schYmd=substr_replace($schYmd,"일",14,1);
// array
$array = array(
    '날짜' => $schYmd,
    '메뉴' => $final
);

$json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
echo $json;
?>