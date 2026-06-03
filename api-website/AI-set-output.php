<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$branch_id = $_GET['bid'] ?? null;
$raw = file_get_contents('php://input');
$sensor_list = json_decode($raw, true);
$output_list = $sensor_list['output_list'] ?? [];
$status = $sensor_list['status'] ?? null;


if (!$branch_id || !$output_list || !$status) {
    echo json_encode(["status" => "error", "message" => "branch_id (bid), output_list, and status are required"]);
    pg_close($db);
    exit;
}
$status = strtoupper($status) === "ON" ? "1" : "0";

$params = [$branch_id];

$placeholders = [];
foreach ($output_list as $i => $name) {
    $placeholders[] = '$' . ($i + 2);
    $params[] = $name;
}

$sql = "SELECT group_id, device_id, type_id, datax_id FROM page_data_manage_monitor WHERE branch_id = $1 AND monitor_name IN (" . implode(',', $placeholders) . ")";

$result = pg_query_params($db, $sql, $params);
$data = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $data[] = $row;
    }
}

$payload = array_map(function ($item) use ($status) {
    return [
        "group_id"  => $item['group_id'],
        "device_id" => $item['device_id'],
        "type_id"   => $item['type_id'],
        "datax_id"  => $item['datax_id'],
        "data_value" => $status
    ];
}, $data);

$ch = curl_init('http://localhost/iotsf/api_push_data_by_hardware_multidata.php');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);

$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);
$result = $response_data['data'] ?? [];

echo json_encode(["status" => "success", "message" => "Output set successfully", "result" => $result], JSON_UNESCAPED_UNICODE) . "\n";


// ✅ ปิดการเชื่อมต่อ
pg_close($db);
