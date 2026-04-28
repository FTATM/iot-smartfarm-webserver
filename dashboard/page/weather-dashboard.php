<?php
session_start();
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
                                    style="font-size: clamp(0.55vw, 0.75vw, 2vw);">Wind Chill</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-windChill" class="text-orange-500 font-light"
                                        style="font-size: 1.2vw;">--°C</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(0.75vw, 1vw, 3vw);">thermostat</span>
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(0.75vw, 1vw, 3vw);">air</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Humidex -->
                            <div class="mb-2 pb-2 border-b border-dashed border-stone-200">
                                <h3 class="text-stone-600 dark:text-stone-200 font-bold mb-1.5 uppercase tracking-wider"
                                    style="font-size: clamp(0.55vw, 0.75vw, 2vw);">Humidex</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-humidex" class="text-orange-500 font-light"
                                        style="font-size: clamp(0.75vw, 1vw, 3vw);">--°C</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(0.75vw, 1vw, 3vw);">wb_sunny</span>
                                        <span class="material-symbols-outlined text-stone-400 dark:text-stone-200"
                                            style="font-size: clamp(0.75vw, 1vw, 3vw);">water_drop</span>
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
                                        class="absolute bg-orange-500 rounded-t-full right-0 w-[0.9vw] bottom-[1.8vh] transition-all duration-700 ease-in-out"
                                        style="height: 0%;"></div>

                                    <!-- Bulb -->
                                    <div class="absolute bottom-0 bg-orange-500 rounded-full
                                            right-[-0.40vw] w-[1.8vw] h-[1.8vw] "></div>

                                    <!-- Scale -->
                                    <div
                                        class="absolute left-0 top-0 flex flex-col justify-between text-stone-500 pr-1.5 font-medium h-[29vh] text-[0.75vw]">
                                        <span>140</span>
                                        <span>100</span>
                                        <span>60</span>
                                        <span>20</span>
                                        <span>-20</span>
                                        <span>-60</span>
                                    </div>
                                </div>

                                <!-- Temperature Reading with Icon -->
                                <div class="text-center flex items-center gap-2">
                                    <span id="widget-currentTemp" class="text-orange-500 font-light"
                                        style="font-size: clamp(0.75vw, 1vw, 3vw);">--°C</span>
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
                                        class="object-contain rounded-md" style="max-height: 30vh;" />
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
                            <div
                                class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
                                <canvas id="TrendChart" class="absolute inset-0 w-full h-full"></canvas>
                            </div>
                        </div>

                        <!-- ===== กราฟล่าง ===== -->
                        <div class="grid grid-cols-5 gap-3 flex-1 min-h-0">
                            <!-- Market Price (3 ส่วน) -->
                            <div
                                class="col-span-3 bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                                <div class="flex justify-between items-center mb-2 shrink-0">
                                    <div>
                                        <h2
                                            class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                            <span class="w-[0.25vw] h-4 2xl:h-6 bg-primary rounded-full"></span>
                                            แนวโน้มราคาตลาด
                                        </h2>
                                        <p
                                            class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                                            Market Price Trend
                                        </p>
                                    </div>
                                    <div id="types-MarketChart" class="flex items-center gap-2 flex-wrap"></div>
                                </div>
                                <div
                                    class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
                                    <canvas id="marketPriceChart" class="absolute inset-0 w-full h-full"></canvas>
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
                                            <p class="text-[0.75vw] font-bold text-slate-400 uppercase">Net Profit</p>
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
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">พยากรณ์อุณหภูมิวันนี้
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

                <!-- Card 2: พยากรณ์ลมรายชั่วโมง -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden flex-1">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="solar--wind-bold-duotone"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">พยากรณ์ลมรายชั่วโมง
                        </h3>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-center flex-1" id="wind-forecast">
                        <!-- Loading state -->
                        <div
                            class="bg-stone-50 flex flex-col justify-between dark:bg-stone-900/20 p-2 rounded-lg border border-stone-200 dark:border-orange-100/20">
                            <p class="text-[0.75vw] text-[#ff8021] font-bold mb-1">ตอนนี้</p>
                            <p id="wind-spd-now" class="text-[0.65vw] font-bold text-slate-800 dark:text-slate-100">--
                            </p>
                            <p id="wind-dir-now" class="text-[0.5vw] text-slate-400 dark:text-slate-200">(--)</p>
                        </div>
                        <div class="p-2 flex flex-col justify-between">
                            <p class="text-[0.75vw] text-slate-400 font-bold mb-1">1 ชม.</p>
                            <p id="wind-spd-1hr" class="text-[0.65vw] font-bold text-slate-600 dark:text-slate-200">--
                            </p>
                            <p id="wind-dir-1hr" class="text-[0.5vw] text-slate-300 dark:text-slate-200">(--)</p>
                        </div>
                        <div class="p-2 flex flex-col justify-between">
                            <p class="text-[0.75vw] text-slate-400 font-bold mb-1">2 ชม.</p>
                            <p id="wind-spd-2hr" class="text-[0.65vw] font-bold text-slate-600 dark:text-slate-200">--
                            </p>
                            <p id="wind-dir-2hr" class="text-[0.5vw] text-slate-300 dark:text-slate-200">(--)</p>
                        </div>
                        <div class="p-2 flex flex-col justify-between">
                            <p class="text-[0.75vw] text-slate-400 font-bold mb-1">3 ชม.</p>
                            <p id="wind-spd-3hr" class="text-[0.65vw] font-bold text-slate-600 dark:text-slate-200">--
                            </p>
                            <p id="wind-dir-3hr" class="text-[0.5vw] text-slate-300 dark:text-slate-200">(--)</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: พยากรณ์ความชื้นสัมพัทธ์ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden flex-1">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="mdi--humidity-outline"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">พยากรณ์ความชื้นสัมพัทธ์
                        </h3>
                    </div>
                    <div class="text-center py-2">
                        <span class="text-[1vw] font-black text-slate-800 dark:text-slate-200" id="humidity-current">
                            <span class="inline-block animate-pulse">--%</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full mb-4 overflow-hidden">
                        <div class="bg-[#ff8021] h-full rounded-full transition-all duration-500" id="humidity-bar"
                            style="width: 10%"></div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 flex-1 text-[0.75vw] text-center font-bold"
                        id="humidity-forecast">
                        <div class="text-slate-400 dark:text-slate-300">1 ชม.<br /><span
                                class="text-slate-700 dark:text-slate-100">--</span></div>
                        <div class="text-slate-400 dark:text-slate-300">2 ชม.<br /><span
                                class="text-slate-700 dark:text-slate-100">--</span></div>
                        <div class="text-slate-400 dark:text-slate-300">3 ชม.<br /><span
                                class="text-slate-700 dark:text-slate-100">--</span></div>
                        <div class="text-slate-400 dark:text-slate-300">4 ชม.<br /><span
                                class="text-slate-700 dark:text-slate-100">--</span></div>
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
                    <div class="flex items-center gap-2 mb-2">
                        <span class="emojione-monotone--sun-behind-rain-cloud text-[#ff8021] text-[2vw]"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 ">พยากรณ์สภาพอากาศ</h3>
                    </div>

                    <!-- สภาพอากาศปัจจุบัน -->
                    <div id="current-weather"
                        class="flex items-center justify-between mb-2 bg-stone-50 dark:bg-stone-900/20 p-[0.5vw] rounded-xl border border-stone-100">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-yellow-500 text-[1vw]"
                                id="current-icon">wb_sunny</span>
                            <div>
                                <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">ตอนนี้
                                </p>
                                <p class="font-bold text-[0.5vw] text-slate-800 dark:text-slate-400"
                                    id="current-condition">--</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[0.75vw] font-bold text-slate-400 dark:text-slate-200 uppercase">ปริมาณฝน</p>
                            <p class="font-bold text-[0.5vw] text-[#ff8021] dark:text-white" id="current-rain">-- mm/hr
                            </p>
                        </div>
                    </div>

                    <!-- พยากรณ์รายวัน -->
                    <div class="space-y-3 pt-2" id="daily-forecast">
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
    <?php include "../scripts/js-weather.html"; ?>
</body>

</html>