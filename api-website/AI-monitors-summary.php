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
$required_sensors = $sensor_list['required_sensors'] ?? [];

if (!$branch_id) {
    echo json_encode(["status" => "error", "message" => "branch_id (bid) is required"]);
    pg_close($db);
    exit;
}
function getSensorSummary($db, int $branchId, array $requiredSensors): string
{
    $sql = "SELECT
            s.monitor_id,
            s.monitor_name,
            g.group_name,
            dv.divice_name,
            s.min_value,
            s.max_value,
            COUNT(l.id)                                                     AS reading_count,
            ROUND(AVG(l.data_value)::numeric, 2)                            AS avg_val,
            ROUND((MAX(l.data_value) - MIN(l.data_value))::numeric, 2)      AS range_val,
            (ARRAY_AGG(l.data_value ORDER BY l.createtime DESC))[1]         AS latest_value,
            MAX(l.createtime)                                               AS last_seen,
            ROUND(AVG(CASE WHEN EXTRACT(HOUR FROM l.createtime) BETWEEN 6  AND 12
                           THEN l.data_value END)::numeric, 2)              AS avg_morning,
            ROUND(AVG(CASE WHEN EXTRACT(HOUR FROM l.createtime) BETWEEN 12 AND 18
                           THEN l.data_value END)::numeric, 2)              AS avg_afternoon,
            SUM(CASE WHEN l.data_value > s.max_value
                       OR l.data_value < s.min_value THEN 1 ELSE 0 END)    AS anomaly_count,
            MAX(CASE WHEN l.data_value > s.max_value THEN l.data_value END) AS peak_high,
            MIN(CASE WHEN l.data_value < s.min_value THEN l.data_value END) AS peak_low
        FROM data_log l
        JOIN page_data_manage_monitor s
            ON s.group_id = l.group_id AND s.device_id = l.device_id
           AND s.type_id  = l.type_id  AND s.datax_id  = l.datax_id
        JOIN page_data_manage_group  g  ON g.group_id   = s.group_id
        JOIN page_data_manage_device dv ON dv.device_id = s.device_id
        WHERE s.branch_id  = $1
          AND s.monitor_name IN ('" . implode("', '", $requiredSensors) . "')
          AND l.createtime >= NOW() - INTERVAL '24 HOURS'
        GROUP BY s.monitor_id, s.monitor_name, g.group_name,
                 dv.divice_name, s.min_value, s.max_value
        ORDER BY s.monitor_id
    ";
    $result = pg_query_params($db, $sql, [$branchId]);

    // ─── Query schedule ครั้งเดียว แทน loop ───
    $sqlSched = "
        SELECT monitor_id,
               TO_CHAR(start_work, 'HH24:MI') || ' - ' || 
               TO_CHAR(end_work,   'HH24:MI') AS time_range
        FROM page_data_manage_monitor_sub
        WHERE monitor_id IN (
            SELECT monitor_id FROM page_data_manage_monitor WHERE branch_id = $1
        )
        ORDER BY monitor_id, start_work
    ";
    $schedResult = pg_query_params($db, $sqlSched, [$branchId]);

    // จัดกลุ่ม schedule ตาม monitor_id
    $schedules = [];
    while ($row = pg_fetch_assoc($schedResult)) {
        $schedules[$row['monitor_id']][] = $row['time_range'];
    }

    // ─── แปลงเป็น text ───
    $lines   = [];
    $lines[] = "=== Sensor Summary | Branch {$branchId} | " . date('Y-m-d H:i') . " ===";

    while ($row = pg_fetch_assoc($result)) {
        $lines[] = formatSensorLine($row, $schedules[$row['monitor_id']] ?? []);
    }

    return implode("\n", $lines);
}

// ─── แปลง 1 sensor → 1 บรรทัด ───
function formatSensorLine(array $s, array $schedule): string
{
    // icon
    $anomaly = (int)$s['anomaly_count'];
    $icon    = $anomaly === 0 ? 'normal' : ($anomaly >= 5 ? 'danger' : 'warning');

    // offline (ไม่มีข้อมูลเกิน 1 ชม.)
    $offline = (time() - strtotime($s['last_seen'])) > 3600 ? 'offline' : '';

    // trend เฉพาะเมื่อต่างกันเกิน 2 หน่วย
    $trend = '';
    if ($s['avg_morning'] !== null && $s['avg_afternoon'] !== null) {
        $diff = abs((float)$s['avg_afternoon'] - (float)$s['avg_morning']);
        if ($diff > 2) {
            $arrow = (float)$s['avg_afternoon'] > (float)$s['avg_morning'] ? '[up]' : '[down]';
            $trend = " trend={$s['avg_morning']}→{$s['avg_afternoon']}{$arrow}";
        }
    }

    // anomaly
    $anomalyText = '';
    if ($anomaly > 0) {
        $peak        = $s['peak_high'] ?? $s['peak_low'];
        $anomalyText = " anomaly={$anomaly} times peak={$peak}";
    }

    // schedule
    $sched = !empty($schedule) ? ' | ' . implode(', ', $schedule) : '';

    return " | [{$icon}] Device-name:{$s['divice_name']}"
         . " avg={$s['avg_val']} now={$s['latest_value']}"
         . " range={$s['range_val']} limit={$s['min_value']}-{$s['max_value']}"
         . $trend
         . $anomalyText
         . $sched
         . $offline;
}

$data = getSensorSummary($db, (int)$branch_id, $required_sensors);

if ($data) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [],"result" => $result, "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
