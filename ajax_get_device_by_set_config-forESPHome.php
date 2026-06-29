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
            mo.monitor_id,
            mo.group_id,
            mo.device_id,
            mo.type_id,
            mo.datax_id,
            mo.datax_value,
            mo.createtime,
            mo.monitor_name,
            pdmd.divice_name AS device_name
        FROM public.page_data_manage_monitor mo
        LEFT JOIN public.page_data_manage_device pdmd 
            ON mo.device_id = pdmd.device_id
        WHERE mo.group_id  = $1
          AND mo.device_id = $2
          AND mo.type_id   = $3
          AND mo.datax_id  = $4
        ORDER BY mo.createtime DESC
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
        $data[$row['monitor_name']] =
            $row['datax_value'] !== null
            ? (float)$row['datax_value']
            : null;
    }
}

if (count($data) > 0) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode((object)[]);   
}

pg_close($db);
