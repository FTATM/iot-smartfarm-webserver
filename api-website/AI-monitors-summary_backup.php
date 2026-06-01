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

if (!$branch_id) {
    echo json_encode(["status" => "error", "message" => "branch_id (bid) is required"]);
    pg_close($db);
    exit;
}

// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = " SELECT
    s.monitor_id,
    s.monitor_name,
    g.group_name,
    dv.divice_name,
    t.type_name,
    dx.datax_name,
    s.min_value,
    s.max_value,

    s.list_time_of_work,

    COUNT(l.id) AS reading_count,

    ROUND(AVG(l.data_value)::numeric, 2) AS avg_val,

    ROUND( (MAX(l.data_value) - MIN(l.data_value))::numeric, 2) AS range_val,

    (
        ARRAY_AGG(l.data_value ORDER BY l.createtime DESC)
    )[1] AS latest_value,

    MAX(l.createtime) AS last_seen,

    ROUND( AVG( CASE WHEN EXTRACT(HOUR FROM l.createtime) BETWEEN 6 AND 12
                THEN l.data_value
            END
        )::numeric,
        2
    ) AS avg_morning,

    ROUND(
        AVG(
            CASE
                WHEN EXTRACT(HOUR FROM l.createtime)
                     BETWEEN 12 AND 18
                THEN l.data_value
            END
        )::numeric,
        2
    ) AS avg_afternoon,

    SUM(
        CASE
            WHEN l.data_value > s.max_value
              OR l.data_value < s.min_value
            THEN 1
            ELSE 0
        END
    ) AS anomaly_count,

    MAX(
        CASE
            WHEN l.data_value > s.max_value
            THEN l.data_value
        END
    ) AS peak_high,

    MIN(
        CASE
            WHEN l.data_value < s.min_value
            THEN l.data_value
        END
    ) AS peak_low

FROM data_log l

JOIN page_data_manage_monitor s
    ON s.group_id = l.group_id
   AND s.device_id = l.device_id
   AND s.type_id = l.type_id
   AND s.datax_id = l.datax_id

JOIN page_data_manage_group g
    ON g.group_id = s.group_id

JOIN page_data_manage_device dv
    ON dv.device_id = s.device_id

JOIN page_data_manage_type t
    ON t.type_id = s.type_id

JOIN page_data_manage_datax dx
    ON dx.datax_id = s.datax_id

WHERE s.branch_id = $1
  AND l.createtime >= NOW() - INTERVAL '24 HOURS'

GROUP BY
    s.monitor_id,
    s.monitor_name,
    g.group_name,
    dv.divice_name,
    t.type_name,
    dx.datax_name,
    s.min_value,
    s.max_value

ORDER BY s.monitor_id
";

$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}
$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
    
    $sql = "SELECT start_work, end_work FROM page_data_manage_monitor_sub WHERE monitor_id = $1";
    $sub_result = pg_query_params($db, $sql, [$row['monitor_id']]);

    $sub_data = [];
    while ($sub_row = pg_fetch_assoc($sub_result)) {
        $sub_data[] = $sub_row['start_work'] . " - " . $sub_row['end_work'];
    }
    $data[count($data)-1]['time_of_work'] = $sub_data;
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [],"result" => $result, "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
