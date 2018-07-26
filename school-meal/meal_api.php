<?php
error_reporting(0);                           // 에러보고 표시하지 않음
header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding
require "simple_html_dom.php";                // use 'simple_html_dom.php'
$countryCode = $_GET['countryCode'];          // website
$schulCode =  $_GET['schulCode'];             // school code
$insttNm = $_GET['insttNm'];                  // school name
$schulCrseScCode = $_GET['schulCrseScCode'];  // school levels code
$schMmealScCode = $_GET['schMmealScCode'];    // meal kinds code
if($_GET['schYmd']==1){    $ptime="+9 hours"; $date = date("Y.m.d", strtotime($ptime, time()));}
else if($_GET['schYmd']==2){    $ptime="+33 hours"; $date = date("Y.m.d", strtotime($ptime, time()));}
else { $date=$_GET['schYmd'];}
$schYmd = $date; //today date
$MENU_URL = "sts_sci_md01_001.do";            // view weekly table
$URL="http://" . $countryCode . "/" . $MENU_URL . "?schulCode=" . $schulCode . "&insttNm=" . urlencode( $insttNm ) . "&schulCrseScCode=" .
 $schulCrseScCode . "&schMmealScCode=" . $schMmealScCode . "&schYmd=".$schYmd ;
$dom=new DOMDocument; // DOMDocument
$html=$dom->loadHTMLFile($URL); // HTML파일 로딩
// $dom->preserveWhiteSpace=false;
$table=$dom->getElementsByTagName('table'); // 태그이름으로 요소 가져오기
$tbody=$table->item(0)->getElementsByTagName('tbody');
$rows=$tbody->item(0)->getElementsByTagName('tr');
$cols=$rows->item(1)->getElementsByTagName('td');
$schYmd=str_replace(".", "-", $schYmd);//날짜 format방식을 재설정
$day=date('w', strtotime($schYmd));//날짜
if($cols->item($day)->nodeValue!=null){ //공간에 값이 있는지 확인
    $final=$cols->item($day)->nodeValue;
}
$final=preg_replace("/[0-9]/", "", $final); //파싱한 데이터에서 쓸모 없는 값 제거 
$final=str_replace(".","\\n",$final);
$final=str_replace("\\n\\n\\n\\n","\\n",$final);
$final=str_replace("\\n\\n\\n","\\n",$final);
$final=str_replace("\\n\\n","\\n",$final);
$final=str_replace(" ","",$final);
if($final==null){    $final="급식이 없습니다.";     } //급식이 없을 경우 
$schYmd=substr_replace($schYmd,"년",4,1);
$schYmd=substr_replace($schYmd,"월",9,1);
$schYmd=substr_replace($schYmd,"일",14,1);
$array = array( // array
    '날짜' => $schYmd,
    '메뉴' => $final
);
$json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); //json encoding
echo $json;
?>