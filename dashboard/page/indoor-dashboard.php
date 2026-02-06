<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        'title' => 'ความชื้นในดิน',
        'value' => '(Soil Moisture)',
        'unit' => '%RH',
    ],
    [
        "title" => "ค่าความนำไฟฟ้า",
        "value" => "(EC)",
        "unit"  => "μS/cm"
    ],
    [
        "title" => "ค่าความเป็นกรด-ด่าง",
        "value" => "(pH)",
        "unit"  => "pH"
    ],
    [
        "title" => "อุณหภูมิ",
        "value" => "(Temp)",
        "unit"  => "°C"
    ],
    [
        "title" => "ความชื้น",
        "value" => "(Humidity)",
        "unit"  => "%"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-indoor.html"); ?>

<head>
    <title>Indoor System - Dashboard</title>
</head>

<body>

    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
            <?php include 'navbar.php'; ?>
            <div class="size-9 bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="fluent--door-arrow-left-20-regular text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Indoor System</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Indoor Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 bg-stone-100 p-1.5 px-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-stone-600 font-bold leading-none" id="start-date"> -- --- ---- </span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 pl-6">
                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1">อัปเดตล่าสุด</span>
                <span class="text-sm font-bold text-stone-800" id="last-update"><?php echo $currentTime; ?></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-4 gap-4 overflow-hidden">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-4 h-full">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col gap-4">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="grid grid-cols-4 gap-4 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 5rem;">image</span>
                            <p class="text-xs mt-2 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-[10px] mt-1 text-stone-400">เครื่องมือ/อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                        แนวโน้มค่าออกซิเจนละลายน้ำ
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        Historical DO Data (24H)
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200">
                                    <button id="btnDoDay" class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                    <button id="btnDoMonth" class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
                                </div>
                            </div>
                            <div class="flex-1 min-h-0 relative border-l border-b border-stone-200 rounded-md">
                                <div id="do-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="flex gap-1">
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                    </div>
                                </div>
                                <canvas id="doTrendChart" class="absolute inset-0"></canvas>
                            </div>
                        </div>

                        <!-- กราฟที่ 2: Price Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-primary rounded-full"></span>
                                        แนวโน้มราคาตลาด
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price Trend</p>
                                </div>
                            </div>
                            <div class="flex-1 min-h-0 relative border-l border-b border-stone-100 bg-white">
                                <div id="price-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="flex gap-1">
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                    </div>
                                </div>
                                <canvas id="marketPriceChart" class="absolute inset-0"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
                <div class="grid grid-cols-5 gap-4 shrink-0" id="metrics-cards">
                    <?php
                    $keys = ['ec', 'ph', 'temp', 'humidity'];
                    $warnings = [
                        'ec' => 'ควรอยู่ระหว่าง 23K-45K μS/cm',
                        'ph' => 'ควรอยู่ระหว่าง 7.0-8.5',
                        'temp' => 'ควรอยู่ระหว่าง 28-32 °C',
                        'humidity' => 'ควรอยู่ระหว่าง 60-80 %'
                    ];

                    for ($i = 0; $i < count($metricTitles); $i++): ?>
                        <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm flex flex-col h-32 hover:ring-2 hover:ring-orange-400 transition-all duration-20 shrink-0" id="card-<?= $keys[$i] ?>">
                            <div class="w-full flex justify-between items-center">
                                <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                                    <?= $metricTitles[$i]['title'] ?>
                                </span>
                                <span class="px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase status" id="card-<?= $keys[$i] ?>">
                                    --
                                </span>
                            </div>
                            <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                                <?= $metricTitles[$i]['value'] ?>
                            </span>

                            <div class="flex-1 flex items-center justify-center">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-lg font-black text-black value">--</span>
                                    <span class="text-sm font-bold text-stone-400">
                                        <?= $metricTitles[$i]['unit'] ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Range Bar with Fixed Gradient -->
                            <div class="metric-range mt-2 hidden" data-key="<?= $keys[$i] ?>">
                                <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                    <span class="label-left"></span>
                                    <span class="label-right"></span>
                                </div>

                                <div class="relative h-1.5 rounded-full bar">
                                    <div class="fill"></div>
                                </div>

                                <!-- Warning text -->
                                <p class="text-[7px] text-stone-500 font-medium mt-3 text-center">
                                    <?= $warnings[$keys[$i]] ?>
                                </p>
                            </div>

                        </div>
                    <?php endfor; ?>
                </div>

            </div>

           <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
<div class="col-span-2 grid grid-rows-4 gap-3 h-full" id="stats-sidebar">

    <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
    <div class="bg-card-light dark:bg-card-dark rounded-2xl p-3 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-lg">assignment</span>
            </div>
            <h4 class="font-bold text-[10px] text-slate-900 dark:text-white">สิ่งที่ต้องทำวันนี้</h4>
        </div>
        <div class="space-y-2 px-1">
            <div class="flex items-start justify-between group">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">งาน</p>
                    <p class="text-[10px] font-semibold text-slate-900 dark:text-white" id="main-task">-</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">ดินที่เหมาะสม</p>
                    <p class="text-[10px] font-semibold text-slate-900 dark:text-white" id="ideal-soil">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: การให้น้ำและปุ๋ย -->
    <div class="bg-card-light dark:bg-card-dark rounded-2xl p-3 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
            </div>
            <h4 class="font-bold text-[10px] text-slate-900 dark:text-white">การให้น้ำและปุ๋ย</h4>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">การให้น้ำ</p>
                <p class="text-[10px] font-bold text-primary" id="watering-schedule">-</p>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">ปุ๋ย</p>
                <p class="text-[10px] font-bold text-slate-900 dark:text-white" id="fertilizer-status">-</p>
            </div>
        </div>
        <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <span class="text-[9px] text-red-500 font-bold uppercase">การแจ้งเตือนศัตรูพืช</span>
            <span class="text-[9px] font-medium text-slate-900 dark:text-white" id="pest-alert">-</span>
        </div>
    </div>

    <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
    <div class="bg-card-light dark:bg-card-dark rounded-2xl p-3 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-lg">monetization_on</span>
            </div>
            <h4 class="font-bold text-[10px] text-slate-900 dark:text-white">การใช้ทรัพยากร</h4>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 dark:bg-slate-900/50 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                <p class="text-[8px] text-slate-400 uppercase font-bold mb-0.5">การใช้น้ำ</p>
                <p class="text-sm font-black tracking-tight text-slate-900 dark:text-white" id="water-usage">-</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-900/50 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                <p class="text-[8px] text-slate-400 uppercase font-bold mb-0.5">ไฟฟ้า</p>
                <p class="text-sm font-black tracking-tight text-slate-900 dark:text-white" id="electricity-usage">-</p>
            </div>
        </div>
        <div class="mt-2 flex justify-between items-center bg-primary/5 p-1.5 rounded-lg">
            <span class="text-[8px] font-bold uppercase text-slate-500 dark:text-slate-400">ต้นทุนต้นกล้ารวม</span>
            <span class="text-[10px] font-bold text-slate-900 dark:text-white" id="seedling-cost">-</span>
        </div>
    </div>

    <!-- Card 4: สรุปรายวันทั้งหมด -->
    <div class="bg-card-light dark:bg-card-dark rounded-2xl p-3 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-lg">analytics</span>
            </div>
            <h4 class="font-bold text-[10px] text-slate-900 dark:text-white">สรุปรายวันทั้งหมด</h4>
        </div>
        <div class="space-y-1.5 text-[10px]">
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-400">ต้นทุนผันแปร</span>
                <span class="font-semibold text-slate-900 dark:text-white" id="variable-cost">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-400">น้ำและไฟฟ้า</span>
                <span class="font-semibold text-slate-900 dark:text-white" id="utilities-cost">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-400">ค่าใช้จ่ายอื่นๆ</span>
                <span class="font-semibold text-slate-900 dark:text-white" id="other-cost">-</span>
            </div>
            <div class="pt-1.5 mt-1.5 border-t border-slate-100 dark:border-slate-700 flex justify-between">
                <span class="font-bold uppercase tracking-wide text-slate-900 dark:text-white">ประมาณการรวม</span>
                <span class="font-black text-primary text-sm" id="total-estimate">-</span>
            </div>
        </div>
    </div>

</div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="px-6 py-2 border-t border-stone-200 bg-white flex justify-between flex-row-reverse shrink-0">
        <div class="flex flex-center gap-2">
            <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 2.0</span>
            <div class="h-3 w-px bg-stone-200"></div>
            <span class="text-[9px] font-bold text-primary uppercase">smart farm system</span>
        </div>
    </footer>

    <?php include "../scripts/js-indoor.html"; ?>
</body>

</html>