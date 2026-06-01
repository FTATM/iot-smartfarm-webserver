<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");
require_once 'config.php';

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$ch = curl_init($TMD_config['api_url']);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer {$TMD_config['token']}"],
    CURLOPT_TIMEOUT => 15,
]);
$tmd_raw = curl_exec($ch);
curl_close($ch);

if (!$tmd_raw) {
    echo json_encode(["status" => "error", "message" => "failed to fetch TMD data"]);
    pg_close($db);
    exit;
}

$tmd_data = json_decode($tmd_raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "invalid JSON from TMD API"]);
    pg_close($db);
    exit;
}

$data = [];
$location = $tmd_data['WeatherForecasts'][0]['location'] ?? null;
$data['location'] = $location['lat'] . "," . $location['lon'];
$forecast = $tmd_data['WeatherForecasts'][0]['forecasts'] ?? [];
$data['forecast']['description'] = " fc = time:{temp,humidity,rain,condition} condition: 1=Clear 2=PartlyCloudy 3=Cloudy 4=Overcast 5=LightRain 6=ModerateRain 7=HeavyRain 8=Thunderstorm 9=VeryCold 10=Cold 11=Cool 12=VeryHot";
foreach ($forecast as $entry) {
    $data['forecast'][substr($entry['time'], 11, 5)] = [
        't' => round($entry['data']['tc'], 1),  // temperature
        'h' => round($entry['data']['rh']),     // humidity
        'r' => $entry['data']['rain'],          // rain
        'c' => $entry['data']['cond']           // condition
    ];
}


echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
// echo json_encode(["status" => "success", "data" => $tmd_data], JSON_UNESCAPED_UNICODE);


pg_close($db);


function GetNameCondition($cond)
{
    $conditions = [
        1 => "ท้องฟ้าแจ่มใส (Clear)",
        2 => "มีเมฆบางส่วน (Partly cloudy)",
        3 => "เมฆเป็นส่วนมาก (Cloudy)",
        4 => "มีเมฆมาก (Overcast)",
        5 => "ฝนตกเล็กน้อย (Light rain)",
        6 => "ฝนปานกลาง (Moderate rain)",
        7 => "ฝนตกหนัก (Heavy rain)",
        8 => "ฝนฟ้าคะนอง (Thunderstorm)",
        9 => "อากาศหนาวจัด (Very cold)",
        10 => "อากาศหนาว (Cold)",
        11 => "อากาศเย็น (Cool)",
        12 => "อากาศร้อนจัด (Very hot)"
    ];
    return $conditions[$cond] ?? "ไม่ทราบสภาพอากาศ";
}
