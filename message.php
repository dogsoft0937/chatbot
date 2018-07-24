<?php
$data = json_decode(file_get_contents('php://input'),true);
$content = $data["content"];

// 급식
if($content == "급식"){
echo <<< EOD
    {
        "message": {
            "text": "급식을 선택하세요."
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "오늘 급식",
                "내일 급식",
                "선택 날짜 급식",
                "처음으로"
            ]
        }   
    }
EOD;
}

// 날씨
elseif($content == "날씨"){
echo <<< EOD
    {
        "message": {
            "text": "어떤 정보가 필요한가요?"
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "기온",
                "미세먼지",
                "처음으로"
            ]
        }
    }
EOD;
}
else if(strpos($content, "기온") !== false){
    $URL="http://192.168.168.108/weather/weather_api.php";
    $json=file_get_contents($URL);
    $result=json_decode($json, true);
    $temp=substr($result['기온'],0,4);
    $temp_min=substr($result['최저기온'],0,4);
    $temp_max=substr($result['최고기온'],0,4);
echo <<< EOD
    {
        "message": {
            "text": "현재 기온은 $temp °C입니다.\\n최저기온은 $temp_min °C,최고기온은 $temp_max °C입니다."
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
              ]
        }
    }
EOD;
}
else if(strpos($content, "미세먼지") !== false){
    $URL="http://192.168.168.108/weather/mise_api.php";
    $json=file_get_contents($URL);
    $result=json_decode($json, true);
echo <<< EOD
    {
        "message": {
            "text": "현재 기온은 $temp °C입니다.\\n최저기온은 $temp_min °C,최고기온은 $temp_max °C입니다."
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
              ]
        }
    }
EOD;
}
// 처음으로
elseif($content == "처음으로"){
echo <<< EOD
    {
        "message": {
            "text": "메인입니다."
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
            ]
        }
    }
EOD;
}

// 오늘 급식
elseif(strpos($content, "오늘") !== false && strpos($content, "급식") !== false){
    $url='http://192.168.168.108/school-meal/meal_api_today.php?countryCode=stu.sen.go.kr&schulCode=B100000599&insttNm=서울아이티고등학교&'.
    'schulCrseScCode=4&schMmealScCode=2';
    $json=file_get_contents($url);
    $result=json_decode($json, true);
    $final=$result['메뉴'];
    $schYmd=$result['날짜'];
    echo <<< EOD
    {
        "message": {
            "text": "[$schYmd]\\n\\n$final"
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
            ]
        }
    }
EOD;
}

// 내일 급식
elseif(strpos($content, "내일") !== false && strpos($content, "급식") !== false){
    $url='http://192.168.168.108/school-meal/meal_api_tomorrow.php?countryCode=stu.sen.go.kr&schulCode=B100000599&insttNm=서울아이티고등학교&schulCrseScCode=4&schMmealScCode=2';
    $json=file_get_contents($url);
    $result=json_decode($json, true);
    $final=$result['메뉴'];
    $schYmd=$result['날짜'];
    echo <<< EOD
    {
        "message": {
            "text": "[$schYmd]\\n\\n$final"
        },
        "keyboard": { 
            "type": "buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
            ]
        }
    }
EOD;
}
elseif(strpos($content, "선택") !== false && strpos($content, "날짜") !== false&& strpos($content, "급식") !== false){
    echo <<< EOD
    {
        "message": {
            "text": "급식을 알기 원하는 날짜 입력\\n 예시:08월06일\\n(탈출하려면 <처음으로> 입력!)"
        }
    }
EOD;
}
else if(strpos($content,"월")!== false &&strpos($content,"일")!== false){
    $content=str_replace("월",".",$content);
    $content=str_replace("일","",$content);
    $schYmd="2018.$content";
    $url='http://192.168.168.108/school-meal/meal_api_custom.php?countryCode=stu.sen.go.kr&schulCode=B100000599&insttNm=서울아이티고등학교&schulCrseScCode=4&schMmealScCode=2&schYmd='.$schYmd;
    $json=file_get_contents($url);
    $result=json_decode($json, true);
    $final=$result['메뉴'];
    $schYmd=$result['날짜'];
    echo <<< EOD
    {
        "message":{
            "text": "[$schYmd]\\n\\n$final"
        },
        "keyboard":{
            "type":"buttons",
            "buttons": [
                "급식",
                "날씨",
                "자유대화"
            ]
        }
    }
EOD;
}


// 자유대화
else if($content == "자유대화"){
echo <<< EOD
    {
        "message": {
            "text": "자유대화\\n(탈출하려면 <처음으로> 입력!)"
        }
    }
EOD;
}
?>