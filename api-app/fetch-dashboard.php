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

// ✅ รับค่าจาก Flutter หรือ Postman
$branch_id = $_POST['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($branch_id)) {
    echo json_encode(["status" => "error", "message" => "please input branch id"]);
    pg_close($db);
    exit;
}

// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT 
        d.id,
        d.item_type_id,
        d.item_name,
        d.sort,
        d.createtime,
        d.updatetime,
        d.status,
        d.size,
        d.icon_id,
        d.branch_id,
        s.id as sub_id,
        s.label_name as sub_name,
        s.status as sub_status,
        s.label_color_code as sub_bg_color_code,
        s.text_color_code as sub_text_color_code,
        m.monitor_id as m_id,
        m.group_id as m_group_id,
        m.device_id as m_device_id,
        m.type_id as m_type_id,
        m.datax_id as m_datax_id,
        m.datax_value as m_value,
        m.monitor_name as m_name,
        m.image_banner as m_img,
        t.type_name as t_name,
        t.type_path as t_path,
        i.id as i_id,
        i.name as i_name,
        i.path as i_path
        FROM dashboard_item AS d
        LEFT JOIN dashboard_item_sub_data AS s ON d.id = s.dashboard_item_id
        LEFT JOIN page_data_manage_monitor AS m ON m.monitor_id = s.monitor_id
        LEFT JOIN dashboard_type AS t ON t.id = d.item_type_id
        LEFT JOIN icons AS i ON d.icon_id = i.id
        WHERE d.branch_id = $1 AND d.status = '1'
        ORDER BY d.sort ASC
        ";

$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}
$dashboards = [];

while ($row = pg_fetch_assoc($result)) {
    $monitor_id = $row['id'];
    // $data[] = $row;
    $sql_select_log = "SELECT data_value FROM data_log WHERE group_id = $1 AND device_id = $2 AND type_id = $3 AND datax_id = $4 ORDER BY id DESC LIMIT 15";
    $result_select_log = pg_query_params($db, $sql_select_log, [$row['m_group_id'], $row['m_device_id'], $row['m_type_id'], $row['m_datax_id']]);

    if (!isset($dashboards[$monitor_id])) {
        $dashboards[$monitor_id] = $row;
        $dashboards[$monitor_id] += [
            'values' => [[$row['m_value']]]
        ];
        $dashboards[$monitor_id] += [
            'labels' => [$row['m_name']]
        ];
        $dashboards[$monitor_id] += [
            'colors' => [$row['label_color_code'] ?? "#666666"]
        ];

        // เติม log (เฉพาะ data_value)
        while ($data = pg_fetch_assoc($result_select_log)) {
            $dashboards[$monitor_id]['values'][0][] = $data['data_value'];
        }
    } else {
        array_push($dashboards[$monitor_id]['values'], [$row['m_value']]);
        array_push($dashboards[$monitor_id]['labels'], $row['m_name']);
        array_push($dashboards[$monitor_id]['colors'], $row['label_color_code'] ?? "#666666");

        // index ล่าสุด
        $lastIndex = count($dashboards[$monitor_id]['values']) - 1;

        // เติม log
        while ($data = pg_fetch_assoc($result_select_log)) {
            $dashboards[$monitor_id]['values'][$lastIndex][] = $data['data_value'];
        }
    }
}

if (count($dashboards) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($dashboards)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
