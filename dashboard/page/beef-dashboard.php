<?php
session_start();
// ============================================================
//  BEEF CATTLE MANAGEMENT DASHBOARD
//  Single-file PHP · Mock Data · ออกแบบตามต้นแบบ
// ============================================================

$Title    = "Beef Cattle Dashboard";
$subTitle = "Beef Cattle Management Dashboard";

// ── MOCK DATA ──────────────────────────────────────────────

$summaryStats = [
    ['label' => 'วัวทั้งหมด',      'value' => '48 ตัว',  'sub' => '6 ระยะการเลี้ยง'],
    ['label' => 'น้ำหนักเฉลี่ย',   'value' => '412 kg',  'sub' => 'ระยะขุนต้น–ขุนปลาย'],
    ['label' => 'ต้นทุนรายวัน',    'value' => '฿5,280',  'sub' => 'เฉลี่ย ฿110/ตัว'],
    ['label' => 'รายได้คาดการณ์',  'value' => '฿1.44M',  'sub' => '18 ตัวพร้อมขาย'],
];

$stages = [
    ['name' => 'ลูกวัว (Calf)',  'age' => '0–3 เดือน',   'weight' => '40–90 kg',   'bar_pct' => 15,  'bar_color' => '#EF9F27', 'sell_label' => 'ยังไม่ควรขาย', 'sell_style' => 'background:#F1EFE8;color:#5F5E5A;'],
    ['name' => 'หย่านม',         'age' => '4–8 เดือน',   'weight' => '90–180 kg',  'bar_pct' => 30,  'bar_color' => '#EF9F27', 'sell_label' => 'ยังไม่ควรขาย', 'sell_style' => 'background:#F1EFE8;color:#5F5E5A;'],
    ['name' => 'รุ่น (Grower)',  'age' => '9–15 เดือน',  'weight' => '180–300 kg', 'bar_pct' => 50,  'bar_color' => '#EF9F27', 'sell_label' => 'ยังไม่ควรขาย', 'sell_style' => 'background:#F1EFE8;color:#5F5E5A;'],
    ['name' => 'ขุนต้น',         'age' => '16–22 เดือน', 'weight' => '300–450 kg', 'bar_pct' => 75,  'bar_color' => '#EF9F27', 'sell_label' => 'บางตลาด',     'sell_style' => 'background:#FAEEDA;color:#854F0B;'],
    ['name' => 'ขุนปลาย',        'age' => '23–30 เดือน', 'weight' => '450–600 kg', 'bar_pct' => 90,  'bar_color' => '#1D9E75', 'sell_label' => 'เหมาะขายแล้ว', 'sell_style' => 'background:#E1F5EE;color:#0F6E56;'],
    ['name' => 'เกินขุน',        'age' => '>30 เดือน',   'weight' => '>600 kg',    'bar_pct' => 100, 'bar_color' => '#E24B4A', 'sell_label' => 'ควรขายแล้ว!', 'sell_style' => 'background:#FCEBEB;color:#A32D2D;'],
];

$diseases = [
    ['age' => '0–3 เดือน',   'name' => 'ท้องเสีย',       'cause' => 'ภูมิต่ำ',       'risk' => 'high'],
    ['age' => '4–8 เดือน',   'name' => 'ปอดอักเสบ',      'cause' => 'อากาศเปลี่ยน',  'risk' => 'med'],
    ['age' => '9–15 เดือน',  'name' => 'พยาธิ',          'cause' => 'กินหญ้าเยอะ',   'risk' => 'low'],
    ['age' => '16–22 เดือน', 'name' => 'กรดในกระเพาะ',   'cause' => 'อาหารข้นสูง',   'risk' => 'med'],
    ['age' => '23–30 เดือน', 'name' => 'ขาอักเสบ',       'cause' => 'น้ำหนักมาก',    'risk' => 'high'],
];

$feedTable = [
    ['stage' => 'ลูกวัว',  'main' => 'นม+หญ้าอ่อน', 'conc' => '16–18% โปรตีน', 'daily' => '2–3%BW'],
    ['stage' => 'หย่านม',  'main' => 'หญ้าสด',       'conc' => '1 kg',          'daily' => '3%BW'],
    ['stage' => 'รุ่น',    'main' => 'หญ้า+ฟาง',     'conc' => '1.5–2 kg',      'daily' => '2.5–3%'],
    ['stage' => 'ขุนต้น',  'main' => 'หญ้า 60%',     'conc' => 'อาหารข้น 40%', 'daily' => '3%'],
    ['stage' => 'ขุนปลาย', 'main' => 'หญ้า 30%',     'conc' => 'อาหารข้น 70%', 'daily' => '3–3.5%'],
];

$marketData = [
    ['range' => '<300 kg',    'pct' => 30,  'status' => 'ราคาต่ำ',    'dot' => '#888780', 'sbg' => '#F1EFE8', 'stx' => '#5F5E5A', 'featured' => false],
    ['range' => '400 kg',     'pct' => 55,  'status' => 'เริ่มคุ้ม',  'dot' => '#EF9F27', 'sbg' => '#FAEEDA', 'stx' => '#854F0B', 'featured' => false],
    ['range' => '500 kg',     'pct' => 80,  'status' => 'เหมาะขาย',  'dot' => '#1D9E75', 'sbg' => '#E1F5EE', 'stx' => '#0F6E56', 'featured' => false],
    ['range' => '550–600 kg', 'pct' => 100, 'status' => 'กำไรสูงสุด', 'dot' => '#639922', 'sbg' => '#EAF3DE', 'stx' => '#3B6D11', 'featured' => true],
    ['range' => '>650 kg',    'pct' => 60,  'status' => 'Overfeed',   'dot' => '#E24B4A', 'sbg' => '#FCEBEB', 'stx' => '#A32D2D', 'featured' => false],
];

// chart data
$chartWeightLabels = json_encode(['3m', '6m', '12m', '18m', '24m', '30m']);
$chartWeightData   = json_encode([80, 150, 250, 380, 500, 600]);
$chartCostLabels   = json_encode(['ลูกวัว', 'รุ่น', 'ขุนต้น', 'ขุนปลาย']);
$chartCostData     = json_encode([1000, 2000, 3000, 4500]);

function riskDot(string $risk): string
{
    $c = ['high' => '#E24B4A', 'med' => '#EF9F27', 'low' => '#1D9E75'][$risk] ?? '#888';
    return "<span style=\"display:inline-block;width:8px;height:8px;border-radius:50%;background:{$c};flex-shrink:0;\"></span>";
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($Title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'IBM Plex Sans Thai', system-ui, sans-serif;
            background: #F5F3EE;
            color: #1C1917;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        /* scrollbars */
        ::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #D3D1C7;
            border-radius: 4px;
        }

        /* cards */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #E7E5DF;
            transition: box-shadow .15s, border-color .15s;
        }

        .card:hover {
            border-color: #EF9F27;
            box-shadow: 0 0 0 2px #EF9F2722;
        }

        /* accent dots */
        .dot-amber {
            background: #EF9F27;
        }

        .dot-teal {
            background: #1D9E75;
        }

        .dot-coral {
            background: #D85A30;
        }

        .dot-blue {
            background: #378ADD;
        }

        .dot-green {
            background: #639922;
        }

        .dot-red {
            background: #E24B4A;
        }

        /* section title */
        .sec-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            font-weight: 600;
            color: #44403C;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 8px;
        }

        .sec-title .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* table base */
        .dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .72rem;
        }

        .dash-table th {
            text-align: left;
            padding: 5px 6px;
            color: #78716C;
            font-weight: 500;
            border-bottom: 1px solid #E7E5DF;
            white-space: nowrap;
        }

        .dash-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #F5F3EE;
            vertical-align: middle;
        }

        .dash-table tr:last-child td {
            border-bottom: none;
        }

        .dash-table tr:hover td {
            background: #FAFAF8;
        }

        /* sell badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* progress bar */
        .progress-wrap {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .progress-track {
            flex: 1;
            height: 5px;
            background: #F1EFE8;
            border-radius: 3px;
            overflow: hidden;
            min-width: 40px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
        }

        /* disease row */
        .disease-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            background: #FAFAF8;
            border-radius: 8px;
            font-size: .7rem;
        }

        /* market row */
        .market-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid #E7E5DF;
            font-size: .7rem;
        }

        .market-row.featured {
            border: 1.5px solid #639922;
            background: #F6FBF0;
        }

        /* profit calc */
        .profit-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 8px;
            border-radius: 8px;
            font-size: .72rem;
        }

        .profit-highlight {
            background: #FAEEDA;
            border: 1px solid #FAC775;
        }

        /* slider */
        input[type=range] {
            width: 100%;
            height: 3px;
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            background: #E7E5DF;
            border-radius: 2px;
            outline: none;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #EF9F27;
            cursor: pointer;
        }

        /* metric card accent */
        .metric-sub {
            display: inline-block;
            margin-top: 3px;
            font-size: .65rem;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 20px;
        }
    </style>
</head>

<body>

    <!-- ════════════════════════════════════
     HEADER
════════════════════════════════════ -->
    <header
        style="background:#fff;border-bottom:1px solid #E7E5DF;padding:8px 16px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div
                style="width:36px;height:36px;border-radius:10px;background:#FAEEDA;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 64 64">
                    <path fill="currentColor"
                        d="m61.989 16.547l-.027-.933l-.919-.021c-2.791-.073-4.992.396-6.369 1.333l-.172.117l-.11.179c-.735 1.195-1.156 3.005-1.228 5.255a25.7 25.7 0 0 1-3.791 2.65c-1.272-.799-2.77-1.407-4.342-1.797c.029-.018.064-.035.094-.053l.178-.113l.115-.177c1.376-2.108 2.035-5.487 1.904-9.769l-.028-.932l-.919-.021a32 32 0 0 0-3.061.072C45.303 10.241 46.688 6.981 46.525 2c0 0-2.908 8.135-9.72 8.844a39 39 0 0 0-.671-.756L36 9.939c-1.298-1.464-2.868-2.501-11.29-2.501c-8.136 0-9.742 1.233-10.995 2.844a9 9 0 0 1-.254.316c-.082.096-.155.191-.236.287C5.98 10.616 2.897 2 2.897 2c-.164 5.015 1.243 8.283 3.252 10.378a32 32 0 0 0-3.199-.063l-.919.026l-.021.933c-.102 4.217.598 7.672 1.97 9.727l.117.175l.178.111c.799.499 1.806.906 3.001 1.212c.087 1.508 1.065 3.018 2.099 4.614c1.166 1.8 2.358 3.66 2.444 5.614c-.863 1.624-.868 3.398-.868 4.718c0 .704.099 1.331.28 1.896v14.775c0 3.244 2.559 5.884 5.703 5.884c3.14 0 5.693-2.64 5.693-5.884v-7.082h4.157v7.072c0 3.25 2.56 5.894 5.706 5.894c2.966 0 5.409-2.347 5.682-5.337c2.314-.023 4.189-1.968 4.189-4.359v-3.322a14.7 14.7 0 0 0 2.578-.359v4.05c0 3.007 2.369 5.453 5.281 5.453c2.914 0 5.285-2.446 5.285-5.453V35.948c0-3.152-1.178-6.122-3.34-8.488a27.5 27.5 0 0 0 3.581-2.69c2.019-.119 3.649-.566 4.731-1.3l.174-.118l.109-.18c1.125-1.826 1.289-4.61 1.229-6.625M9.208 24.283q.001-.263.013-.527l.034-.836l-.808-.167c-1.221-.253-2.221-.6-2.976-1.032c-.979-1.595-1.514-4.207-1.532-7.453c3.086-.008 5.569.409 7.275 1.214l.743.351l.194-.286c2.944.587 5.52.083 5.799-.392c.902-1.531.3-4.877-1.705-4.591c1.053-.63 3.183-1.158 8.466-1.158c6.48 0 8.407.639 9.293 1.314a12 12 0 0 1-.643-.131c-2.154-.527-2.816 2.989-1.887 4.566c.297.503 3.178 1.039 6.342.269l.102.152l.725-.293c1.693-.684 4.016-1.046 6.758-1.059c.002 3.253-.516 5.881-1.485 7.497c-.74.431-1.729.781-2.94 1.042l-.784.169l.026 1.352c0 1.013-.855 2.433-1.761 3.937c-.87 1.445-1.811 3.026-2.308 4.71c-.05-.042-.096-.087-.148-.128c-.354-1.492-1.156-2.612-2.286-3.179c-1.464-.731-3.314-.421-4.985.83a41 41 0 0 0-4.014-.181c-1.45 0-2.797.061-4.012.18c-1.67-1.25-3.519-1.563-4.986-.828c-1.124.563-1.921 1.676-2.278 3.152c-.507-1.735-1.515-3.311-2.445-4.745c-.92-1.418-1.787-2.758-1.787-3.749m3.681 15.161c0-2.195.145-3.978 1.968-5.268l.328-.232l.071-.4c.188-1.059.657-1.824 1.319-2.156q.386-.193.845-.193c.73 0 1.576.369 2.383 1.039l.314.262l.404-.045c1.244-.14 2.655-.21 4.193-.21c1.536 0 2.947.07 4.195.21l.404.045l.315-.262c1.143-.949 2.362-1.277 3.227-.846c.662.332 1.13 1.097 1.318 2.156l.071.4l.328.232c1.824 1.29 1.969 3.072 1.969 5.268c0 2.293-.913 4.529-11.828 4.529c-10.911.001-11.824-2.236-11.824-4.529m27.535 12.86h-2.225v-3.27h2.225zm13.142-16.356v16.724h-6.688v-6.71l-1.272.426c-1.347.451-2.734.68-4.122.68h-5.222v9.039h-7.539v-9.039H20.69v9.049h-7.521V43.896c2.345 1.617 6.411 2.046 11.546 2.046c8.264 0 13.767-1.104 13.767-6.497c0-1.248-.005-2.906-.737-4.456c.074-1.922 1.233-3.863 2.365-5.743c.928-1.539 1.801-3.003 1.996-4.402c2.434.168 4.873.977 6.678 2.242l.491.345l.024-.013l.648.587c2.334 2.113 3.619 4.934 3.619 7.943m5.659-14.002c-.854.5-2.229.81-3.905.875l-.225.009c.03-1.886.325-3.426.835-4.381c.896-.527 2.358-.839 4.13-.888c-.028 1.889-.318 3.416-.835 4.385" />
                    <path fill="currentColor"
                        d="M42.93 20.804c.713-1.093 1.043-2.917.975-5.138c-1.846-.043-3.395.177-4.497.618a15.7 15.7 0 0 1 1.612 5.234c.771-.167 1.423-.404 1.91-.714M5.424 15.667c-.053 2.267.309 4.095 1.018 5.157c.532.332 1.235.576 2.065.737c.314-1.597.929-3.271 1.944-5.083c-1.146-.598-2.907-.874-5.027-.811" />
                    <ellipse cx="33.193" cy="22.569" fill="currentColor" rx="3.611" ry="3.909" />
                    <ellipse cx="16.231" cy="22.569" fill="currentColor" rx="3.612" ry="3.909" />
                    <path fill="currentColor"
                        d="M28.275 41.747c1.572.788 3.797-.633 4.965-3.173c1.172-2.541.846-5.24-.727-6.028s-3.795.635-4.967 3.174c-1.173 2.542-.845 5.24.729 6.027m-7.117 0c1.571-.787 1.898-3.485.727-6.025c-1.167-2.541-3.393-3.963-4.964-3.176c-1.575.789-1.9 3.487-.729 6.028s3.394 3.961 4.966 3.173" />
                </svg>
            </div>
            <div>
                <div style="font-size:.85rem;font-weight:700;color:#1C1917;"><?= htmlspecialchars($subTitle) ?></div>
                <div style="font-size:.65rem;color:#A8A29E;">ระบบจัดการฟาร์มวัวเนื้อ · อัปเดตล่าสุดวันนี้</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span
                style="font-size:.7rem;font-weight:600;padding:4px 10px;border-radius:20px;background:#EAF3DE;color:#3B6D11;border:1px solid #97C459;">●
                Live</span>
            <span style="font-size:.65rem;color:#A8A29E;" class="mono"><?= date('d/m/Y H:i') ?></span>
        </div>
    </header>

    <!-- ════════════════════════════════════
     MAIN
════════════════════════════════════ -->
    <main style="flex:1;overflow:hidden;display:flex;flex-direction:column;gap:10px;padding:10px;min-height:0;">

        <!-- ── ROW 1: SUMMARY METRICS ── -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;flex-shrink:0;">
            <?php
            $mc = [
                ['#FAEEDA', '#854F0B'],
                ['#E1F5EE', '#0F6E56'],
                ['#FAECE7', '#993C1D'],
                ['#EAF3DE', '#3B6D11'],
            ];
            foreach ($summaryStats as $i => $s):
                [$bg, $tx] = $mc[$i];
            ?>
                <div class="card" style="padding:12px 14px; position:relative;">
                    <div
                        style="font-size:.65rem;font-weight:600;color:#78716C;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">
                        <?= htmlspecialchars($s['label']) ?>
                    </div>
                    <div class="mono" style="font-size:1.4rem;font-weight:700;color:#1C1917;line-height:1.2;">
                        <?= htmlspecialchars($s['value']) ?>
                    </div>
                    <span class="metric-sub"
                        style="position:absolute; top:8px; right:10px; background:<?= $bg ?>; color:<?= $tx ?>;">
                        <?= htmlspecialchars($s['sub']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ── ROW 2: MAIN CONTENT ── -->
        <div style="flex:1;display:grid;grid-template-columns:1fr 252px;gap:10px;min-height:0;overflow:hidden;">

            <!-- LEFT -->
            <div style="display:flex;flex-direction:column;gap:10px;min-height:0;overflow:hidden;">

                <!-- TOP: Stage table + Disease + Cost -->
                <div style="display:grid; grid-template-columns:1fr 380px; gap:10px; flex-shrink:0;">
                    <!-- ระยะการเลี้ยง -->
                    <div class="card"
                        style="padding:12px;display:flex;flex-direction:column;min-height:0;overflow:hidden;">
                        <div class="sec-title">
                            <span class="dot dot-amber"></span>ระยะการเลี้ยงและสถานะการขาย
                        </div>
                        <div style="flex:1;overflow-y:auto;min-height:0;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>ระยะ</th>
                                        <th>อายุ</th>
                                        <th>น้ำหนักเป้าหมาย</th>
                                        <th style="min-width:100px;">ความคืบหน้า</th>
                                        <th>ขายได้?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stages as $s): ?>
                                        <tr>
                                            <td style="font-weight:600;color:#1C1917;"><?= htmlspecialchars($s['name']) ?>
                                            </td>
                                            <td style="color:#78716C;white-space:nowrap;"><?= htmlspecialchars($s['age']) ?>
                                            </td>
                                            <td style="color:#78716C;" class="mono"><?= htmlspecialchars($s['weight']) ?>
                                            </td>
                                            <td>
                                                <div class="progress-wrap">
                                                    <div class="progress-track">
                                                        <div class="progress-fill"
                                                            style="width:<?= $s['bar_pct'] ?>%;background:<?= $s['bar_color'] ?>;">
                                                        </div>
                                                    </div>
                                                    <span style="font-size:.65rem;color:#A8A29E;min-width:22px;"
                                                        class="mono"><?= $s['bar_pct'] ?>%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge"
                                                    style="<?= $s['sell_style'] ?>"><?= htmlspecialchars($s['sell_label']) ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:10px; min-height:0;">
                        <!-- โรคที่ต้องระวัง -->
                        <div class="card"
                            style="padding:12px;display:flex;flex-direction:column;min-height:0;overflow:hidden;">
                            <div class="sec-title">
                                <span class="dot dot-red"></span>โรคที่ต้องระวัง
                            </div>
                            <div
                                style="flex:1;display:flex;flex-direction:column;gap:5px;overflow-y:auto;min-height:0;">
                                <?php foreach ($diseases as $d): ?>
                                    <div class="disease-row">
                                        <?= riskDot($d['risk']) ?>
                                        <span
                                            style="color:#A8A29E;min-width:80px;flex-shrink:0;"><?= htmlspecialchars($d['age']) ?></span>
                                        <span style="font-weight:600;flex:1;"><?= htmlspecialchars($d['name']) ?></span>
                                        <span style="color:#A8A29E;"><?= htmlspecialchars($d['cause']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <div
                                    style="display:flex;gap:10px;padding-top:4px;font-size:.65rem;color:#A8A29E;justify-content:flex-end;">
                                    <span style="display:flex;align-items:center;gap:3px;"><?= riskDot('high') ?>
                                        สูง</span>
                                    <span style="display:flex;align-items:center;gap:3px;"><?= riskDot('med') ?>
                                        ปานกลาง</span>
                                    <span style="display:flex;align-items:center;gap:3px;"><?= riskDot('low') ?>
                                        ต่ำ</span>
                                </div>
                            </div>
                        </div>

                        <!-- ต้นทุนรายระยะ -->
                        <div class="card" style="padding:12px; flex:1;">
                            <div class="sec-title">
                                <span class="dot dot-amber"></span>ต้นทุนรายระยะ
                            </div>
                            <?php
                            $costRows = [
                                ['ลูกวัว', '30–40 บาท/วัน', '~฿1,000/เดือน'],
                                ['รุ่น', '50–70 บาท/วัน', '~฿2,000/เดือน'],
                                ['ขุนต้น', '80–110 บาท/วัน', '~฿3,000/เดือน'],
                                ['ขุนปลาย', '120–160 บาท/วัน', '~฿4,500/เดือน'],
                            ];
                            foreach ($costRows as $cr):
                            ?>
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;border-bottom:1px solid #F5F3EE;font-size:.68rem;">
                                    <span style="font-weight:600;color:#44403C;"><?= $cr[0] ?></span>
                                    <span style="color:#78716C;"><?= $cr[1] ?></span>
                                    <span class="mono" style="font-weight:600;color:#EF9F27;"><?= $cr[2] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM: Feed table + Market + Charts -->
                <div
                    style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; flex: 1; min-height: 0; overflow: hidden;">

                    <!-- ===== ตารางอาหาร ===== -->
                    <div class="card"
                        style="padding: clamp(6px, 1vw, 12px); display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                        <div class="sec-title"
                            style="font-size: clamp(10px, 1vw, 13px); flex-shrink: 0; margin-bottom: 6px;">
                            <span class="dot dot-teal"></span>ตารางอาหารวัวเนื้อ
                        </div>

                        <!-- scroll zone -->
                        <div style="flex: 1; min-height: 0; overflow-y: auto;">
                            <table class="dash-table"
                                style=" width: 100%; font-size: clamp(9px, 0.9vw, 12px); border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th
                                            style="font-size: clamp(8px, 0.8vw, 11px); position: sticky; top: 0; background: #fff; z-index: 1;">
                                            ระยะ</th>
                                        <th
                                            style="font-size: clamp(8px, 0.8vw, 11px); position: sticky; top: 0; background: #fff; z-index: 1;">
                                            อาหารหลัก</th>
                                        <th
                                            style="font-size: clamp(8px, 0.8vw, 11px); position: sticky; top: 0; background: #fff; z-index: 1;">
                                            อาหารข้น</th>
                                        <th
                                            style="font-size: clamp(8px, 0.8vw, 11px); position: sticky; top: 0; background: #fff; z-index: 1;">
                                            กิน/วัน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($feedTable as $f): ?>
                                        <tr>
                                            <td style="font-weight: 600; padding: clamp(3px, 0.4vh, 6px) 4px;">
                                                <?= htmlspecialchars($f['stage']) ?>
                                            </td>
                                            <td style="color: #78716C; padding: clamp(3px, 0.4vh, 6px) 4px;">
                                                <?= htmlspecialchars($f['main']) ?>
                                            </td>
                                            <td style="color: #78716C; padding: clamp(3px, 0.4vh, 6px) 4px;">
                                                <?= htmlspecialchars($f['conc']) ?>
                                            </td>
                                            <td class="mono" style="color: #44403C; padding: clamp(3px, 0.4vh, 6px) 4px;">
                                                <?= htmlspecialchars($f['daily']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ===== ราคาตลาด ===== -->
                    <div class="card"
                        style="padding: clamp(6px, 1vw, 12px); display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                        <div class="sec-title"
                            style="font-size: clamp(10px, 1vw, 13px); flex-shrink: 0; margin-bottom: 6px;">
                            <span class="dot dot-blue"></span>ราคาตลาดและสถานะขาย
                        </div>

                        <!-- scroll zone -->
                        <div
                            style="flex: 1; min-height: 0; overflow-y: auto; display: flex; flex-direction: column; gap: clamp(4px, 0.6vh, 8px);">
                            <?php foreach ($marketData as $m): ?>
                                <div class="market-row <?= $m['featured'] ? 'featured' : '' ?>"
                                    style="display: flex; align-items: center; gap: clamp(4px, 0.5vw, 8px); font-size: clamp(9px, 0.9vw, 12px);">
                                    <span
                                        style="width: clamp(6px, 0.6vw, 8px); height: clamp(6px, 0.6vw, 8px); border-radius: 50%; background: <?= $m['dot'] ?>; flex-shrink: 0;"></span>
                                    <span class="mono" style=" font-weight: 600; min-width: clamp(52px, 5vw, 72px); flex-shrink: 0;
                "><?= htmlspecialchars($m['range']) ?></span>
                                    <div
                                        style="flex: 1; height: 4px; background: #F1EFE8; border-radius: 2px; overflow: hidden;">
                                        <div
                                            style="width: <?= $m['pct'] ?>%; height: 100%; background: <?= $m['dot'] ?>; border-radius:2px;">
                                        </div>
                                    </div>
                                    <span class="badge"
                                        style="background: <?= $m['sbg'] ?>; color: <?= $m['stx'] ?>; font-size: clamp(8px, 0.75vw, 10px); padding: clamp(1px, 0.2vh, 3px) clamp(4px, 0.5vw, 8px); flex-shrink: 0;"><?= htmlspecialchars($m['status']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ===== Charts ===== -->
                    <div class="card"
                        style="padding: clamp(6px, 1vw, 12px); display: flex; flex-direction: column; gap: clamp(6px, 1vh, 12px); min-height: 0; overflow: hidden;">
                        <!-- กราฟ 1 -->
                        <div style="flex: 1; min-height: 0; display: flex; flex-direction: column;">
                            <div class="sec-title"
                                style=" font-size: clamp(10px, 1vw, 13px); flex-shrink: 0; margin-bottom: 4px;">
                                <span class="dot dot-amber"></span>น้ำหนักมาตรฐาน
                            </div>
                            <div style="position: relative; flex: 1; min-height: 0;">
                                <canvas id="weightChart" role="img" aria-label="เส้นกราฟน้ำหนักมาตรฐานวัวเนื้อตามอายุ"
                                    style="width: 100% !important; height: 100% !important;">
                                </canvas>
                            </div>
                        </div>

                        <!-- กราฟ 2 -->
                        <div style="flex: 1; min-height: 0; display: flex; flex-direction: column;">
                            <div class="sec-title"
                                style="font-size: clamp(10px, 1vw, 13px); flex-shrink: 0; margin-bottom: 4px;">
                                <span class="dot dot-teal"></span>ต้นทุน/เดือน
                            </div>
                            <div style="position: relative; flex: 1; min-height: 0;">
                                <canvas id="costChart" role="img" aria-label="แท่งกราฟต้นทุนอาหารต่อเดือน"
                                    style="width: 100% !important; height: 100% !important;">
                                </canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /LEFT -->

            <!-- RIGHT: แยกเป็น 2 กรอบ ซ้อนกันในแนวตั้ง -->
            <div style="display:flex; flex-direction:column; gap:10px; min-height:0; overflow:hidden;">

                <!-- กรอบที่ 1: คำนวณกำไรสุทธิ -->
                <div class="card" style="padding:12px; display:flex ;flex-direction:column; gap:10px; flex:1;">
                    <div class="sec-title"><span class="dot dot-green"></span>คำนวณกำไรสุทธิ</div>

                    <div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:.7rem;color:#78716C;margin-bottom:4px;">
                            <span>จำนวนวัวที่ขาย</span>
                            <span class="mono" id="num-out" style="font-weight:600;color:#1C1917;">15 ตัว</span>
                        </div>
                        <input type="range" id="num-cattle" min="1" max="48" value="15" step="1" oninput="calcProfit()">
                    </div>

                    <div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:.7rem;color:#78716C;margin-bottom:4px;">
                            <span>น้ำหนักเฉลี่ย (kg)</span>
                            <span class="mono" id="wt-out" style="font-weight:600;color:#1C1917;">550 kg</span>
                        </div>
                        <input type="range" id="avg-weight" min="30" max="700" value="550" step="10"
                            oninput="calcProfit()">
                    </div>

                    <div style="height:1px;background:#E7E5DF;"></div>

                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <div class="profit-row" style="background:#FAFAF8;">
                            <span style="color:#78716C;">รายได้รวม (฿85/kg)</span>
                            <span class="mono" style="font-weight:600;" id="p-income">฿0</span>
                        </div>
                        <div class="profit-row" style="background:#FAFAF8;">
                            <span style="color:#78716C;">ต้นทุนอาหาร (30 เดือน)</span>
                            <span class="mono" style="font-weight:600;" id="p-cost">฿0</span>
                        </div>
                        <div class="profit-row profit-highlight">
                            <span style="font-weight:700;color:#854F0B;">กำไรสุทธิ</span>
                            <span class="mono" style="font-size:.9rem;font-weight:700;" id="p-profit">฿0</span>
                        </div>
                    </div>
                </div><!-- /กรอบ 1 -->

                <!-- กรอบที่ 2: สูตรอาหารขุน -->
                <div class="card" style="padding:12px; display:flex; flex-direction:column; gap:8px; flex:1;">
                    <div class="sec-title"><span class="dot dot-teal"></span>สูตรอาหารขุน (ต่อ 100 kg)</div>
                    <?php
                    $formula = [
                        ['ข้าวโพดบด', 35, 45],
                        ['มันเส้น', 25, 30],
                        ['รำละเอียด', 20, 10],
                        ['กากถั่วเหลือง', 15, 10],
                        ['เกลือแร่', 3, 3],
                        ['ยูเรีย', 2, 2],
                    ];
                    ?>
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>วัตถุดิบ</th>
                                <th style="text-align:right;">ขุนต้น</th>
                                <th style="text-align:right;">ขุนปลาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formula as $r): ?>
                                <tr>
                                    <td style="color:#44403C;"><?= htmlspecialchars($r[0]) ?></td>
                                    <td class="mono" style="text-align:right;color:#78716C;"><?= $r[1] ?> kg</td>
                                    <td class="mono" style="text-align:right;font-weight:600;color:#EF9F27;"><?= $r[2] ?> kg
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- สรุปรวม -->
                    <div
                        style="display:flex;justify-content:space-between;padding:5px 6px;background:#F5F3EE;border-radius:8px;font-size:.68rem;">
                        <span style="font-weight:600;color:#44403C;">รวมทั้งหมด</span>
                        <span class="mono" style="color:#78716C;">100 kg</span>
                        <span class="mono" style="font-weight:600;color:#EF9F27;">100 kg</span>
                    </div>
                </div><!-- /กรอบ 2 -->

            </div><!-- /RIGHT -->

        </div><!-- /ROW 2 -->

    </main>

    <!-- FOOTER -->
    <footer
        style="flex-shrink:0;padding:5px 16px;background:#fff;border-top:1px solid #E7E5DF;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.65rem;color:#A8A29E;">🐄 Beef Cattle Management Dashboard</span>
        <span style="font-size:.65rem;color:#A8A29E;" class="mono"><?= date('Y') ?> · PHP Mock Data</span>
    </footer>

    <!-- ════════════════════════════════════
     SCRIPTS
════════════════════════════════════ -->
    <script>
        // ── Weight Chart ──
        new Chart(document.getElementById('weightChart'), {
            type: 'line',
            data: {
                labels: <?= $chartWeightLabels ?>,
                datasets: [{
                    data: <?= $chartWeightData ?>,
                    borderColor: '#EF9F27',
                    backgroundColor: 'rgba(239,159,39,0.07)',
                    borderWidth: 2,
                    pointBackgroundColor: '#EF9F27',
                    pointRadius: 3,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E',
                            callback: v => v + 'kg'
                        }
                    }
                }
            }
        });

        // ── Cost Chart ──
        new Chart(document.getElementById('costChart'), {
            type: 'bar',
            data: {
                labels: <?= $chartCostLabels ?>,
                datasets: [{
                    data: <?= $chartCostData ?>,
                    backgroundColor: ['#9FE1CB', '#5DCAA5', '#EF9F27', '#BA7517'],
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E',
                            callback: v => '฿' + v.toLocaleString()
                        }
                    }
                }
            }
        });

        // ── Profit Calculator ──
        function calcProfit() {
            const n = parseInt(document.getElementById('num-cattle').value);
            const w = parseInt(document.getElementById('avg-weight').value);
            document.getElementById('num-out').textContent = n + ' ตัว';
            document.getElementById('wt-out').textContent = w + ' kg';
            const income = n * w * 85;
            const cost = n * 135000;
            const profit = income - cost;
            document.getElementById('p-income').textContent = '฿' + income.toLocaleString();
            document.getElementById('p-cost').textContent = '฿' + cost.toLocaleString();
            const el = document.getElementById('p-profit');
            el.textContent = '฿' + profit.toLocaleString();
            el.style.color = profit >= 0 ? '#0F6E56' : '#A32D2D';
        }
        calcProfit();
    </script>

</body>

</html>