<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "แรงดันไฟฟ้า",
        "value" => "(Voltage Solar Supply)",
        "unit"  => "V"
    ],
    [
        "title" => "กระแสไฟฟ้า",
        "value" => "(Current Solar Supply)",
        "unit"  => "mA"
    ],
    [
        "title" => "กำลังไฟฟ้า",
        "value" => "(Power Solar Supply)",
        "unit"  => "W"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Solar System - Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ff8021",
                        "background-light": "#fcfaf8",
                        "danger": "#ef4444",
                        "warning": "#f59e0b",
                        "success": "#22c55e",
                    },
                    fontFamily: {
                        "sans": ["Inter", "Noto Sans Thai", "sans-serif"]
                    }
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-background-light text-[#1d130c] h-screen flex flex-col overflow-hidden;
                font-family: 'Inter', 'Noto Sans Thai', sans-serif;
            }
        }
        .ph--solar-panel-duotone {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 256 256'%3E%3Cg fill='%23000'%3E%3Cpath d='M232 216H24l40.7-72h126.6Z' opacity='0.35'/%3E%3Cpath d='M32 104a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H40a8 8 0 0 1-8-8m39.43-45.25a8 8 0 0 0 11.32-11.32L71.43 36.12a8 8 0 0 0-11.31 11.31ZM128 40a8 8 0 0 0 8-8V16a8 8 0 0 0-16 0v16a8 8 0 0 0 8 8m50.91 21.09a8 8 0 0 0 5.66-2.34l11.31-11.32a8 8 0 0 0-11.31-11.31l-11.32 11.31a8 8 0 0 0 5.66 13.66M192 104a8 8 0 0 0 8 8h16a8 8 0 0 0 0-16h-16a8 8 0 0 0-8 8m-104 8a8 8 0 0 0 8-8a32 32 0 0 1 64 0a8 8 0 0 0 16 0a48 48 0 0 0-96 0a8 8 0 0 0 8 8m150.91 108a8 8 0 0 1-6.91 4H24a8 8 0 0 1-7-11.94l40.69-72a8 8 0 0 1 7-4.06H191.3a8 8 0 0 1 7 4.06l40.69 72a8 8 0 0 1-.08 7.94m-52.27-68h-24.37l3.48 16h29.93Zm-37.26 16l-3.48-16h-35.8l-3.48 16Zm-46.24 16l-5.21 24h60.14l-5.21-24Zm-42.82-16h29.93l3.48-16H69.36Zm-22.61 40h43.84l5.22-24H51.28Zm180.58 0l-13.57-24h-35.49l5.22 24Z'/%3E%3C/g%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FFF3E9;
            border-radius: 10px;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .loading-dot {
            animation: pulse-dot 1.4s ease-in-out infinite;
        }
        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        .metric-range .bar {
            position: relative;
            background: #e7e5e4;
            overflow: hidden;
            border-radius: 999px;
        }

        .metric-range .fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            border-radius: 999px;
            clip-path: inset(0 100% 0 0);
            transition: clip-path 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

</head>

<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-3 sm:px-6 py-3 border-b border-stone-200 bg-white shrink-0 gap-3 sm:gap-0">
        <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <?php include 'navbar.php'; ?>
            <div class="size-8 sm:size-9 bg-[#FFD7B6] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20 shrink-0">
                <span class="ph--solar-panel-duotone text-xl sm:text-2xl text-[#ff8021]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-sm sm:text-lg font-bold leading-tight text-[#1d130c] truncate">Solar System Dashboard</h1>
                <p class="text-[9px] sm:text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-0.5">Solar Energy Monitor</p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-2 sm:gap-6 w-full sm:w-auto flex-nowrap">
            <div class="flex items-center gap-6 w-auto">
                <div class="flex flex-col items-end border-l border-stone-200 pl-3 sm:pl-6">
                    <span class="text-[9px] sm:text-[10px] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1 whitespace-nowrap">
                        อัปเดตล่าสุด
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-stone-800 whitespace-nowrap" id="last-update">
                        <?php echo $currentTime; ?>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-2 sm:p-4 overflow-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

            <!-- LEFT SECTION (Main Content) -->
            <div class="lg:col-span-10 flex flex-col gap-3 sm:gap-4">

                <!-- TOP ROW: รูปภาพ + กราฟ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-10 gap-3 sm:gap-4">

                    <!-- รูปภาพ -->
                    <div class="sm:col-span-2 lg:col-span-4 lg:row-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center min-h-[200px] sm:min-h-[250px] lg:min-h-full hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 3rem; sm:font-size: 5rem;">image</span>
                            <p class="text-xs mt-2 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-[10px] mt-1 text-stone-400">Solar Panels / Equipment</p>
                        </div>
                    </div>

                    <!-- กราฟ DO -->
                    <div class="sm:col-span-2 lg:col-span-6 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                    <span class="truncate">แนวโน้มค่าออกซิเจนละลายน้ำ</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5 hidden sm:block">
                                    Historical DO Data (24H)
                                </p>
                            </div>
                            <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200 shrink-0">
                                <button id="btnDoDay" class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                <button id="btnDoMonth" class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-200 rounded-md min-h-[150px]">
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

                    <!-- กราฟ Price -->
                    <div class="sm:col-span-2 lg:col-span-6 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex justify-between items-center mb-2 shrink-0">
                            <div>
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-primary rounded-full"></span>
                                    <span class="truncate">แนวโน้มราคาตลาด</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5 hidden sm:block">Market Price Trend</p>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-100 bg-white min-h-[150px]">
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

                <!-- BOTTOM ROW: Sensor Metrics (3 cards) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4" id="metrics-cards">
                    <?php
                    $keys = ['voltage', 'current', 'power'];
                    $warnings = [
                        'voltage' => 'แรงดันไฟฟ้าปกติ 12-48 V',
                        'current' => 'กระแสไฟฟ้าปกติ 100-5000 mA',
                        'power' => 'กำลังไฟฟ้าปกติ 10-250 W'
                    ];

                    for ($i = 0; $i < count($metricTitles); $i++): ?>
                        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-<?= $keys[$i] ?>">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest"><?= $metricTitles[$i]['title'] ?></span>
                                <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                            </div>
                            <div class="flex-1 flex items-center justify-center py-2">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-base sm:text-lg font-black text-black value">--</span>
                                    <span class="text-xs sm:text-sm font-bold text-stone-400"><?= $metricTitles[$i]['unit'] ?></span>
                                </div>
                            </div>
                            <div class="metric-range mt-2 hidden" data-key="<?= $keys[$i] ?>">
                                <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                    <span class="label-left"></span>
                                    <span class="label-right"></span>
                                </div>
                                <div class="relative h-1.5 rounded-full bar">
                                    <div class="fill"></div>
                                </div>
                                <p class="text-[7px] text-stone-500 font-medium mt-2 text-center"><?= $warnings[$keys[$i]] ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

            </div>

            <!-- RIGHT SECTION (Sidebar) -->
            <div class="lg:col-span-2 flex h-full">
                <div class="bg-white border border-stone-200 rounded-2xl p-2.5 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 w-full">
                    
                    <!-- Header -->
                    <div class="flex items-center gap-1.5 mb-1.5 flex-shrink-0">
                        <span class="material-symbols-outlined text-primary text-xs">bolt</span>
                        <h3 class="text-[9px] font-bold text-stone-700">การใช้พลังงานและทรัพยากร</h3>
                    </div>
                    
                    <!-- Content Grid - 1 Column ยืดเต็มที่ -->
                    <div class="flex flex-col gap-1 flex-1">
                        
                        <!-- ช่วงเวลาการชาร์จ -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200 rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-md transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-blue-500 text-xs">schedule</span>
                                </div>
                                <span class="text-[8px] text-blue-700 font-bold">ช่วงเวลาการชาร์จ</span>
                            </div>
                            <div class="pl-7">
                                <span class="text-[10px] font-black text-blue-900" id="charge-time">-- ชม./วัน</span>
                            </div>
                        </div>
                        
                        <!-- ปริมาณการเก็บ -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 border border-purple-200 rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-md transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-purple-500/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-purple-500 text-xs">battery_charging_full</span>
                                </div>
                                <span class="text-[8px] text-purple-700 font-bold">ปริมาณการเก็บ</span>
                            </div>
                            <div class="pl-7">
                                <span class="text-[10px] font-black text-purple-900" id="storage-amount">4-6 ชม./วัน</span>
                            </div>
                        </div>
                        
                        <!-- การใช้พลังงาน -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 border border-amber-200 rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-md transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-amber-500 text-xs">flash_on</span>
                                </div>
                                <span class="text-[8px] text-amber-700 font-bold">การใช้พลังงาน</span>
                            </div>
                            <div class="pl-7">
                                <span class="text-[10px] font-black text-amber-900" id="energy-usage">3-4 ชม./วัน</span>
                            </div>
                        </div>
                        
                        <!-- โหลดรวม -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 border-2 border-primary rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-lg transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-xs">power</span>
                                </div>
                                <span class="text-[8px] text-primary font-black">โหลดรวม</span>
                            </div>
                            <div class="pl-7">
                                <span class="text-[10px] font-black text-primary" id="total-load">-- kW</span>
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-stone-300 to-transparent flex-shrink-0"></div>
                        
                        <!-- ค่าไฟทั้งหมด -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100/50 border border-red-200 rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-md transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-red-500/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-red-500 text-xs">electric_bolt</span>
                                </div>
                                <span class="text-[8px] text-red-700 font-bold">ค่าไฟทั้งหมด</span>
                            </div>
                            <div class="pl-7 flex items-baseline gap-0.5">
                                <span class="text-sm font-black text-red-900" id="electricity-cost">--</span>
                                <span class="text-[8px] text-red-600 font-medium">บาท/หน่วย</span>
                            </div>
                        </div>
                        
                        <!-- การใช้น้ำทั้งหมด -->
                        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100/50 border border-cyan-200 rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-md transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-cyan-500/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-cyan-500 text-xs">water_drop</span>
                                </div>
                                <span class="text-[8px] text-cyan-700 font-bold">การใช้น้ำทั้งหมด</span>
                            </div>
                            <div class="pl-7 flex items-baseline gap-0.5">
                                <span class="text-sm font-black text-cyan-900" id="water-usage">--</span>
                                <span class="text-[8px] text-cyan-600 font-medium">ลบ.ม.</span>
                            </div>
                        </div>
                        
                        <!-- ค่าพลังงานทดแทน -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100/50 border-2 border-success rounded-lg p-1.5 flex flex-col gap-0.5 hover:shadow-lg transition-all flex-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-lg bg-success/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-success text-xs">eco</span>
                                </div>
                                <span class="text-[8px] text-success font-black">ค่าพลังงานทดแทน</span>
                            </div>
                            <div class="pl-7 flex items-baseline gap-0.5">
                                <span class="text-sm font-black text-success" id="renewable-cost">--</span>
                                <span class="text-[8px] text-green-600 font-medium">บาท</span>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="px-3 sm:px-6 py-2 border-t border-stone-200 bg-white flex items-center gap-2 shrink-0">
    <div class="flex items-center gap-2 ml-auto">
        <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 1.0</span>
        <div class="h-3 w-px bg-stone-200"></div>
        <span class="text-[9px] font-bold text-primary uppercase">Smart Farm</span>
    </div>
</footer>


    <!-- JavaScript -->
    <script>
        const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const today = new Date();
        const formattedDate = `${today.getDate()} ${thaiMonths[today.getMonth()]} ${today.getFullYear() + 543}`;

        document.addEventListener('DOMContentLoaded', () => {
            loadSensorData();
            loadDoTrendData();

            setInterval(() => {
                loadSensorData();
                loadDoTrendData();
            }, 30000);
        });

        async function loadSensorData() {
            try {
                const res = await fetch('/dashboard/api/generate_sensor.php');
                const rows = await res.json();

                console.log('✅ raw sensor data:', rows);

                const sensor = {};
                rows.forEach(item => {
                    if (!item.divice_name) return;
                    sensor[item.divice_name.toLowerCase()] = parseFloat(item.datax_value);
                });

                console.log('✅ mapped sensor:', sensor);

                setCardValue('voltage', sensor.voltage);
                setCardValue('current', sensor.current);
                setCardValue('power', sensor.power);

            } catch (err) {
                console.error('❌ loadSensorData error:', err);
            }
        }

        function setBadge(statusEl, text, type) {
            const map = {
                success: 'status px-2 py-0.5 rounded-full bg-green-100 text-green-600 text-[9px] font-bold uppercase',
                warning: 'status px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[9px] font-bold uppercase',
                danger: 'status px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[9px] font-bold uppercase',
                info: 'status px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[9px] font-bold uppercase',
                na: 'status px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase',
            };

            statusEl.textContent = text;
            statusEl.className = map[type] || map.na;
        }

        function getStatusByKey(key, v) {
            if (key === 'voltage') {
                if (v >= 12 && v <= 48) return { text: 'ปกติ', type: 'success' };
                if (v < 12) return { text: 'ต่ำ', type: 'warning' };
                return { text: 'สูง', type: 'danger' };
            }

            if (key === 'current') {
                if (v >= 100 && v <= 5000) return { text: 'ปกติ', type: 'success' };
                if (v < 100) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'warning' };
            }

            if (key === 'power') {
                if (v >= 10 && v <= 250) return { text: 'ปกติ', type: 'success' };
                if (v < 10) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'warning' };
            }

            return { text: 'N/A', type: 'na' };
        }

        function setCardValue(key, value) {
            const card = document.getElementById(`card-${key}`);
            if (!card) return;

            const valueEl = card.querySelector('.value');
            const statusEl = card.querySelector('.status');

            if (value === null || value === undefined || isNaN(value)) {
                valueEl.textContent = '--';
                setBadge(statusEl, 'N/A', 'na');
                return;
            }

            const v = Number(value);

            valueEl.textContent = v.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            const st = getStatusByKey(key, v);
            setBadge(statusEl, st.text, st.type);
        }

        let doChart = null;

        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');

            try {
                const res = await fetch('/dashboard/api/monitor_trend.php', {
                    cache: 'no-store'
                });
                const data = await res.json();

                console.log('📊 DO Trend API Response:', data);

                const doData = data.find(item => item.device_id === 2);

                if (!doData || !doData.points || doData.points.length === 0) {
                    console.warn('⚠️ ไม่มีข้อมูล DO');
                    renderDoChart([], []);
                    return;
                }

                const labels = doData.points.map(p => {
                    const d = new Date(p.time);
                    return `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
                });

                const values = doData.points.map(p => parseFloat(p.value));

                renderDoChart(labels, values);

            } catch (err) {
                console.error('❌ loadDoTrendData error:', err);
                renderDoChart([], []);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function renderDoChart(labels, values) {
            const ctx = document.getElementById('doTrendChart');
            if (!ctx) return;

            if (doChart) {
                doChart.destroy();
            }

            doChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'DO (mg/L)',
                        data: values,
                        borderColor: '#ff8021',
                        backgroundColor: 'rgba(255,128,33,0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 2,
                        pointBackgroundColor: '#ff8021'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 9
                                },
                                color: '#78716c',
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 10,
                            ticks: {
                                font: {
                                    size: 9
                                },
                                color: '#78716c',
                                callback: v => v.toFixed(1)
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });
        }

        async function loadMarketPriceTrend() {
            const loading = document.getElementById('price-loading');

            try {
                const res = await fetch('/dashboard/api/market_price_Tred.php', {
                    cache: 'no-store'
                });
                const raw = await res.json();

                console.log('📈 price trend raw:', raw);

                if (!Array.isArray(raw) || raw.length === 0) return;

                const labels = raw.map(r => r.event_month);

                const price50 = raw
                    .filter(r => r.data_table_id === "19")
                    .map(r => Number(r.event_price));

                const price70 = raw
                    .filter(r => r.data_table_id === "20")
                    .map(r => Number(r.event_price));

                renderMarketPriceChart(labels, price50, price70);

            } catch (err) {
                console.error('❌ price trend error:', err);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        let marketPriceChart;

        function renderMarketPriceChart(labels, price50, price70) {
            const ctx = document.getElementById('marketPriceChart');
            if (!ctx) return;

            if (marketPriceChart) {
                marketPriceChart.destroy();
            }

            marketPriceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                            label: '50 ตัว/กก.',
                            data: price50,
                            borderColor: '#ff8021',
                            backgroundColor: 'rgba(255,128,33,0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2.5
                        },
                        {
                            label: '70 ตัว/กก.',
                            data: price70,
                            borderColor: 'rgba(255,128,33,0.4)',
                            backgroundColor: 'rgba(255,128,33,0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.raw} บาท/กก.`
                            }
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
                                color: '#78716c'
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 10
                                },
                                color: '#78716c',
                                callback: v => v + ' ฿'
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadMarketPriceTrend();
        });
    </script>
</body>

</html>