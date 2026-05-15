<?php
session_start();
include '../components/session.php';
checkLogin();
$Title = "Weather Dashboard";
$subTitle = "Weather Dashboard Farm Intelligence";
$classIconHeader = "arcticons--weathercan";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>Weather - Dashboard</title>
    <style>
    :root {
        --fs-xs: clamp(9px, 0.5vw, 14px);
        --fs-sm: clamp(11px, 0.75vw, 18px);
        --fs-md: clamp(13px, 1vw, 22px);
        --fs-lg: clamp(16px, 1.5vw, 28px);
    }

    /* 4K: 3840×2160 */
    @media (min-width: 3840px) {
        :root {
            --fs-xs: clamp(20px, 0.55vw, 28px);
            --fs-sm: clamp(26px, 0.8vw, 36px);
            --fs-md: clamp(34px, 1.1vw, 48px);
            --fs-lg: clamp(44px, 1.6vw, 64px);
        }

        [class*="text-[0.4vw]"],
        [class*="text-[0.45vw]"],
        [class*="text-[0.5vw]"] {
            font-size: var(--fs-xs) !important;
        }

        [class*="text-[0.55vw]"],
        [class*="text-[0.6vw]"],
        [class*="text-[0.65vw]"],
        [class*="text-[0.75vw]"],
        [class*="text-[0.8vw]"],
        [class*="text-[0.9vw]"] {
            font-size: var(--fs-sm) !important;
        }

        [class*="text-[1vw]"],
        [class*="text-[1.1vw]"] {
            font-size: var(--fs-md) !important;
        }

        [class*="text-[1.5vw]"],
        [class*="text-[2vw]"] {
            font-size: var(--fs-lg) !important;
        }

        /* inline style font-size vw */
        .material-symbols-outlined {
            font-size: var(--fs-sm) !important;
        }

        /* padding */
        [class*="p-[0.3vw]"] {
            padding: clamp(8px, 0.35vw, 14px) !important;
        }

        [class*="p-[0.4vw]"] {
            padding: clamp(10px, 0.45vw, 18px) !important;
        }

        [class*="p-[0.5vw]"] {
            padding: clamp(14px, 0.55vw, 22px) !important;
        }

        /* gap */
        [class*="gap-1"] {
            gap: clamp(8px, 0.3vw, 14px) !important;
        }

        [class*="gap-2"] {
            gap: clamp(12px, 0.4vw, 20px) !important;
        }

        [class*="gap-3"] {
            gap: clamp(16px, 0.5vw, 26px) !important;
        }

        [class*="gap-[0.3vw]"] {
            gap: clamp(8px, 0.35vw, 14px) !important;
        }

        /* space-y */
        [class*="space-y-[0.3vw]"]>*+* {
            margin-top: clamp(8px, 0.35vw, 14px) !important;
        }

        /* progress bar */
        [class*="h-[0.3vw]"] {
            height: clamp(6px, 0.3vw, 10px) !important;
        }

        /* rounded */
        .rounded-xl {
            border-radius: clamp(12px, 0.5vw, 20px) !important;
        }

        .rounded-2xl {
            border-radius: clamp(16px, 0.6vw, 26px) !important;
        }
    }
    </style>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col justify-between gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="grid grid-cols-8 gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 1 ส่วน (ซ้ายสุด) -->
                    <div
                        class="col-span-1 bg-white box dark:bg-stone-900 p-[0.5vw] rounded-2xl border border-stone-200 shadow-sm flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <div class="rounded-2xl p-[0.25vw] flex flex-col h-full transition-all duration-200">

                            <!-- Wind Chill -->
                            <div class="mb-2 pb-2 border-b border-dashed border-stone-200">
                                <h3 class="text-stone-600 dark:text-stone-200 font-bold mb-1.5 uppercase tracking-wider"
                                    style="font-size: clamp(11px, 0.75vw, 60px);">Wind Chill</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-windChill" class="text-orange-500 font-light"
                                        style="font-size: clamp(13px, 1.2vw, 80px);">--°C</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(13px, 1vw, 72px);">thermostat</span>
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(13px, 1vw, 72px);">air</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Humidex -->
                            <div class="mb-2 pb-2 border-b border-dashed border-stone-200">
                                <h3 class="text-stone-600 dark:text-stone-200 font-bold mb-1.5 uppercase tracking-wider"
                                    style="font-size: clamp(11px, 0.75vw, 60px);">Humidex</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-humidex" class="text-orange-500 font-light"
                                        style="font-size: clamp(13px, 1vw, 72px);">--°C</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(13px, 1vw, 72px);">wb_sunny</span>
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(13px, 1vw, 72px);">water_drop</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Thermometer Display -->
                            <div class="flex-1 flex flex-col items-center justify-center pt-1">
                                <div class="relative mb-2 w-[3.8vw] h-[30vh]">
                                    <!-- Thermometer Body -->
                                    <div class="absolute right-0 top-0 bg-stone-300 rounded-t-full w-[1vw] h-[29.5vh] ">
                                    </div>

                                    <!-- Mercury -->
                                    <div id="widget-mercury"
                                        class="absolute bg-orange-500 rounded-t-full right-0 w-[1vw] bottom-[1.8vh] transition-all duration-700 ease-in-out"
                                        style="height: 0%;"></div>

                                    <!-- Bulb -->
                                    <div class="absolute bottom-0 bg-orange-500 rounded-full
                                            right-[-0.40vw] w-[1.8vw] h-[1.8vw] "></div>

                                    <!-- Scale -->
                                    <div
                                        class="absolute left-0 top-0 flex flex-col justify-between text-stone-500 pr-1.5 font-medium h-[29vh] text-[0.75vw]">
                                        <span>70</span>
                                        <span>50</span>
                                        <span>30</span>
                                        <span>10</span>
                                        <span>-5</span>
                                        <span>-20</span>
                                    </div>
                                </div>

                                <!-- Temperature Reading with Icon -->
                                <div class="text-center flex items-center gap-2">
                                    <span id="widget-currentTemp" class="text-orange-500 font-light"
                                        style="font-size: clamp(13px, 1vw, 72px);">--°C</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div
                        class="box col-span-4 flex flex-col bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="rounded-xl px-[1.5vw] flex flex-col items-center justify-center">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 dark:border-stone-500 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Weather System with IoT App.
                            </div>
                            <img src="images/weather_top-3.png" alt="เครื่องมือ/อุปกรณ์"
                                class="object-contain rounded-lg dark:bg-stone-600"
                                style="width: 130em; height: 30vh;" />
                        </div>

                        <div class=" flex flex-col flex-1 px-[1.5vw]">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 dark:border-stone-500 font-bold mb-1 mt-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Weather System Architecture
                            </div>

                            <div class="flex justify-between flex-1 gap-1.5 ">
                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_left.php"); ?>

                                </div>
                                <div class="col-span-8 rounded-2xl p-2 flex items-center justify-center transition-all">
                                    <img src="images/Weather-2.png" alt="เครื่องมือ/อุปกรณ์"
                                        class="object-contain rounded-md" style="max-height: 28vh;" />
                                </div>

                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_right.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-3 flex flex-col justify-between gap-3 min-h-0">

                        <!-- ===== กราฟบน ===== -->
                        <div
                            class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col flex-1 min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                            <div class="flex justify-between items-center mb-2 shrink-0">
                                <div>
                                    <h2
                                        class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                        <span class="w-[0.25vw] h-4 2xl:h-6 bg-orange-500 rounded-full"></span>
                                        <div id="title-graph-sensor">แนวโน้ม</div>
                                    </h2>
                                    <p id="title-graph-sensor-sub"
                                        class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                                        Historical Data
                                    </p>
                                </div>
                            </div>
                            <!-- กราฟ + Sensor Toggle Panel -->
                            <div class="flex flex-1 min-h-0 gap-2">
                                <!-- Canvas -->
                                <div
                                    class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
                                    <canvas id="TrendChart" class="absolute inset-0 w-full h-full"></canvas>
                                </div>
                                <!-- Sensor Toggle Button Panel -->
                                <div id="sensor-checkbox-panel"
                                    class="flex flex-col gap-[0.35vh] justify-start bg-stone-50 dark:bg-stone-800 rounded-xl px-[0.4vw] py-[0.5vh] shrink-0 border border-stone-200 dark:border-stone-700 overflow-y-auto">
                                    <p
                                        class="text-[0.45vw] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-widest mb-[0.2vh] text-center">
                                        Sensors</p>
                                    <!-- Sensor toggle buttons จะถูก inject โดย JS -->
                                </div>
                            </div>
                        </div>

                        <!-- ===== กราฟล่าง ===== -->
                        <div class="grid grid-cols-5 gap-3 flex-1 min-h-0">
                            <!-- Market Price (3 ส่วน) -->
                            <div class="col-span-3 bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-700
                            rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400
                            transition-all duration-200">

                                <!-- Header -->
                                <div
                                    class="flex items-center gap-2 pb-2.5 border-b border-stone-200 dark:border-stone-700 flex-shrink-0">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <span class="text-sm font-semibold text-stone-800 dark:text-stone-100">AI
                                        Assistant</span>
                                    <span class="ml-auto text-xs text-stone-400">พร้อมใช้งาน</span>
                                </div>

                                <!-- Messages -->
                                <div id="chatMessages"
                                    class="flex-1 overflow-y-auto py-3 flex flex-col gap-3 scrollbar-thin scrollbar-thumb-stone-200 dark:scrollbar-thumb-stone-700">
                                    <!-- AI welcome -->
                                    <div class="flex items-end gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 flex items-center justify-center text-[10px] font-semibold text-stone-500 flex-shrink-0">
                                            AI</div>
                                        <div
                                            class="max-w-[78%] px-3 py-2 rounded-2xl rounded-bl-sm bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-100 text-[13px] leading-relaxed">
                                            สวัสดีครับ ผมช่วยอะไรได้บ้าง?
                                        </div>
                                    </div>
                                </div>

                                <!-- Input -->
                                <div
                                    class="flex items-end gap-2 pt-2.5 border-t border-stone-200 dark:border-stone-700 flex-shrink-0">
                                    <textarea id="chatInput" rows="1" placeholder="พิมพ์ข้อความ..."
                                        onkeydown="chatOnKey(event)" oninput="chatResize(this)"
                                        class="flex-1 bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-xl px-3 py-2 text-[13px] text-stone-800 dark:text-stone-100 placeholder-stone-400 resize-none outline-none leading-relaxed max-h-[90px] focus:border-orange-400 dark:focus:border-orange-500 transition-colors duration-150"></textarea>
                                    <button id="chatSend" onclick="chatSend()"
                                        class="w-8 h-8 rounded-full bg-orange-500 hover:bg-orange-600 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center flex-shrink-0 transition-all duration-150">
                                        <svg class="w-3.5 h-3.5 fill-white" viewBox="0 0 24 24">
                                            <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- ช่องขวา (2 ส่วน) -->
                            <div
                                class="col-span-2 bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                                <div>
                                    <h2
                                        class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                        <span class="w-[0.25vw] h-4 2xl:h-6 bg-primary rounded-full"></span>
                                        สัดส่วนรายจ่าย
                                    </h2>
                                    <p
                                        class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                                        Distribution
                                    </p>
                                </div>
                                <div class="flex flex-col items-center justify-center flex-grow">
                                    <div id="donut" class="relative w-[10vw] h-[10vw] donut-chart shadow-xl">
                                        <div
                                            class="absolute inset-4 bg-white dark:bg-stone-900 rounded-full flex flex-col items-center justify-center text-center">
                                            <p class="text-[0.75vw] font-bold text-slate-400 uppercase">Net Profit
                                            </p>
                                            <p id="card-4-total"
                                                class="text-[1vw] font-bold font-display text-slate-900 dark:text-white leading-none">
                                                0</p>
                                            <p class="text-[0.75vw] font-bold text-teal-500 mt-1">THB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
                <div class="grid grid-cols-6 gap-3 shrink-0" id="metrics-cards"></div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: พยากรณ์อุณหภูมิ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="streamline-ultimate--temperature-thermometer-medium-bold"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">
                            พยากรณ์อุณหภูมิวันนี้
                        </h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- ต่ำสุด -->
                        <div
                            class="bg-cyan-50/50 dark:bg-cyan-900/20 p-[0.5vw] rounded-xl text-center border border-cyan-100 dark:border-cyan-100/10 flex flex-col justify-center">
                            <p class="text-[0.75vw] text-cyan-600 dark:text-blue-400 font-bold uppercase mb-1">
                                ต่ำสุด (°C)
                            </p>
                            <p class="text-[1vw] font-bold text-slate-800" id="temp-min">
                                <span class="inline-block dark:text-white animate-pulse">--</span>
                            </p>
                        </div>

                        <!-- สูงสุด -->
                        <div
                            class="bg-orange-50/50 dark:bg-orange-900/20 p-[0.5vw] rounded-xl text-center border border-orange-100 dark:border-orange-100/10 flex flex-col justify-center">
                            <p class="text-[0.75vw] text-orange-600 dark:text-orange-400 font-bold uppercase mb-1">
                                สูงสุด (°C)
                            </p>
                            <p class="text-[1vw] font-bold text-slate-800" id="temp-max">
                                <span class="inline-block dark:text-white animate-pulse">--</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ลมที่ระดับความกดอากาศ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden flex-1">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="solar--wind-bold-duotone"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">
                            ลมที่ระดับความกดอากาศ
                        </h3>
                    </div>

                    <!-- วันนี้ highlight (เหมือน current-weather ใน Card4) -->
                    <div id="wind-today"
                        class="flex items-center justify-between mb-2 bg-stone-50 dark:bg-stone-900/20 p-[0.5vw] rounded-xl border border-stone-100 dark:border-stone-100">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#ff8021] text-[1vw]">air</span>
                            <div>
                                <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">
                                    วันนี้ ·
                                    925 hPa</p>
                                <p class="font-bold text-[0.5vw] text-slate-800 dark:text-slate-400"
                                    id="wind-today-dir">--
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">ความเร็ว
                            </p>
                            <p class="font-bold text-[0.5vw] text-[#ff8021] dark:text-white" id="wind-today-spd">--
                                kts
                            </p>
                        </div>
                    </div>

                    <!-- list ระดับอื่น (เหมือน daily-forecast ใน Card4) -->
                    <div class="space-y-[0.3vw]" id="wind-pressure-table">
                        <div class="flex justify-between items-center text-[0.5vw] font-medium">
                            <span class="text-slate-400 dark:text-slate-500">กำลังโหลด...</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: พยากรณ์ความชื้น ฝน เมฆ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden flex-1">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="mdi--humidity-outline"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">ความชื้น · ฝน · เมฆ
                        </h3>
                    </div>

                    <!-- วันนี้ highlight -->
                    <div
                        class="flex items-center justify-between mb-2 bg-stone-50 dark:bg-stone-900/20 p-[0.5vw] rounded-xl border border-stone-100 dark:border-stone-100">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#ff8021] text-[1vw]">humidity_percentage</span>
                            <div>
                                <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">
                                    วันนี้
                                </p>
                                <p class="font-bold text-[0.5vw] text-slate-800 dark:text-slate-400">
                                    ความชื้น <span id="humidity-current" class="animate-pulse">--%</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right space-y-[0.2vw]">
                            <p class="flex items-center gap-1 text-[0.5vw] text-slate-400 dark:text-slate-200">
                                <span class="material-symbols-outlined text-blue-400 text-[0.75vw]">water_drop</span>
                                <span id="humidity-rain" class="font-bold text-[#ff8021]">-- mm</span>
                            </p>

                            <p class="flex items-center gap-1 text-[0.5vw] text-slate-400 dark:text-slate-200">
                                <span class="material-symbols-outlined text-slate-400 text-[0.75vw]">cloud</span>
                                <span id="humidity-cloud"
                                    class="font-bold text-slate-600 dark:text-slate-300">--%</span>
                            </p>
                        </div>
                    </div>

                    <!-- progress bar ความชื้น -->
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-[0.3vw] rounded-full mb-2 overflow-hidden">
                        <div class="bg-[#ff8021] h-full rounded-full transition-all duration-500" id="humidity-bar"
                            style="width:10%"></div>
                    </div>

                    <!-- list รายวันถัดไป -->
                    <div class="space-y-[0.3vw]" id="humidity-forecast">
                        <div class="flex justify-between items-center text-[0.5vw] font-medium">
                            <span class="text-slate-400 dark:text-slate-500">กำลังโหลด...</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: พยากรณ์สภาพอากาศ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden flex-1">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="f7--cloud-sun-rain-fill"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">พยากรณ์สภาพอากาศ
                        </h3>
                    </div>

                    <!-- สภาพอากาศปัจจุบัน -->
                    <div id="current-weather"
                        class="flex items-center justify-between mb-2 bg-stone-50 dark:bg-stone-900/20 p-[0.5vw] rounded-xl border border-stone-100">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-yellow-500 text-[1vw]"
                                id="current-icon">wb_sunny</span>
                            <div>
                                <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">
                                    ตอนนี้
                                </p>
                                <p class="font-bold text-[0.5vw] text-slate-800 dark:text-slate-400"
                                    id="current-condition">
                                    --</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">ปริมาณฝน
                            </p>
                            <p class="font-bold text-[0.5vw] text-[#ff8021] dark:text-white" id="current-rain">--
                                mm/hr
                            </p>
                        </div>
                    </div>

                    <!-- พยากรณ์รายวัน -->
                    <div class="space-y-[0.3vw] pt-2" id="daily-forecast">
                        <!-- Loading state -->
                        <div class="flex justify-between items-center text-[0.5vw] font-medium">
                            <span class="text-slate-500 dark:text-slate-400">กำลังโหลด...</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-300 text-[0.75vw]">cloud_queue</span>
                                <span class="text-slate-700 dark:text-slate-400">-- mm/hr</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/components/js-ai.html"; ?>
    <?php include "../scripts/js-weather.html"; ?>
</body>

</html>