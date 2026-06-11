<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
include_once("includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

// รับ POST body เป็น JSON
$body = file_get_contents('php://input');
$input = json_decode($body, true);

if (!$input || !is_array($input)) {
    echo json_encode(["status" => "error", "error" => "Invalid or missing JSON body."]);
    exit;
}

// ตัวอย่าง input ที่คาดหวัง:
// [
//   {"group_id": 1, "device_id": 10, "type_id": 2, "datax_id": 5},
//   {"group_id": 1, "device_id": 11, "type_id": 2, "datax_id": 5}
// ]

$data = [];

foreach ($input as $item) {

    // validate แต่ละ item
    if (!isset($item['group_id'], $item['device_id'], $item['type_id'], $item['datax_id'])) {
        continue; // ข้ามถ้าข้อมูลไม่ครบ
    }

    $sql = "
        SELECT 
            dl.id,
            dl.group_id,
            dl.device_id,
            dl.type_id,
            dl.datax_id,
            dl.data_value,
            dl.createtime,
            pdmd.divice_name AS device_name
        FROM public.data_log dl
        LEFT JOIN public.page_data_manage_device pdmd 
            ON dl.device_id = pdmd.device_id
        WHERE dl.group_id  = $1
          AND dl.device_id = $2
          AND dl.type_id   = $3
          AND dl.datax_id  = $4
        ORDER BY dl.createtime DESC
        LIMIT 1
    ";

    $result = pg_query_params($db, $sql, [
        $item['group_id'],
        $item['device_id'],
        $item['type_id'],
        $item['datax_id']
    ]);

    if (!$result) {
        echo json_encode(["status" => "error", "error" => pg_last_error($db)]);
        pg_close($db);
        exit;
    }

    $row = pg_fetch_assoc($result);

    if ($row) {
        $data[] = [
            "device_name" => $row['device_name'],
            "device_id"   => (int)$row['device_id'],
            "group_id"    => (int)$row['group_id'],
            "type_id"     => (int)$row['type_id'],
            "datax_id"    => (int)$row['datax_id'],
            "data_value"  => $row['data_value'] !== null ? (float)$row['data_value'] : null,
            "createtime"  => $row['createtime']
        ];
    }
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "No data found."]);
}

pg_close($db);