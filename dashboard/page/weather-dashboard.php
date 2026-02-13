<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "ทิศทางลม",
        "value" => "(Wind Direction)",
        "unit" => "∘"
    ],
    [
        "title" => "ดัชนีคุณภาพอากาศ",
        "value" => "(Air Quality)",
        "unit" => "ug/m3"
    ],
    [
        "title" => "แอมโมเนีย",
        "value" => "(Ammonia)",
        "unit" => "ppm"
    ],
    [
        "title" => "ออกซิเจน",
        "value" => "(Oxygen)",
        "unit" => "%"
    ],
    [
        "title" => "ปริมาณน้ำฝน",
        "value" => "(Rainfall)",
        "unit" => "mm/min"
    ],
    [
        "title" => "ความเข้มแสง",
        "value" => "(Sunlight intensity)",
        "unit" => "LUX"
    ],

];
?>

<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-weather.html"); ?>

<head>
    <title>Weather Dashboard</title>

</head>

<body>

    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
            <?php include 'navbar.php'; ?>
            <div
                class="size-9 bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="arcticons--weathercan text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Weather Dashboard</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Weather Dashboard Farm
                    Intelligence</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 bg-stone-100 p-1.5 px-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-stone-600 font-bold leading-none" id="start-date"> -- --- ---- </span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 pl-6">
                <span
                    class="text-[10px] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1">อัปเดตล่าสุด</span>
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
                <div class="grid grid-cols-8 gap-4 flex-1 min-h-0">

                    <!-- รูปภาพ 1 ส่วน (ซ้ายสุด) -->
                    <div class="col-span-1 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <div class="bg-white rounded-2xl p-4 flex flex-col h-full transition-all duration-200">

                            <!-- Wind Chill -->
                            <div class="mb-2 pb-2 border-b border-dashed border-stone-200 2xl:mb-16 2xl:pb-8 2xl:border-b-8">
                                <h3 class="text-stone-600 font-bold mb-1.5 uppercase tracking-wider"
                                    style="font-size: 0.5vw;">Wind Chill</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-windChill" class="text-orange-500 font-light"
                                        style="font-size: 1.2vw;">--°F</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400"
                                            style="font-size: 0.8vw;">thermostat</span>
                                        <span class="material-symbols-outlined text-stone-400"
                                            style="font-size: 0.8vw;">air</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Humidex -->
                            <div
                            class="mb-2 pb-2 border-b border-dashed border-stone-200 2xl:mb-16 2xl:pb-8 2xl:border-b-8">
                                <h3 class="text-stone-600 font-bold mb-1.5 uppercase tracking-wider"
                                    style="font-size: 0.5vw;">Humidex</h3>
                                <div class="flex items-center gap-2">
                                    <span id="widget-humidex" class="text-orange-500 font-light"
                                        style="font-size: 1.2vw;">--°F</span>
                                    <div class="flex gap-0.5">
                                        <span class="material-symbols-outlined text-stone-400"
                                            style="font-size: 0.8vw;">wb_sunny</span>
                                        <span class="material-symbols-outlined text-stone-400"
                                            style="font-size: 0.8vw;">water_drop</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Thermometer Display -->
                            <div class="flex-1 flex flex-col items-center justify-center pt-1">
                                <div class="relative mb-2
                                            w-[3.8vw] h-[30vh]
                                            2xl:w-[5vw] 2xl:h-[38vh]">
                                    <!-- Thermometer Body -->
                                    <div
                                    class="absolute right-0 top-0 bg-stone-300 rounded-t-full
                                            w-[1vw] h-[29.5vh]
                                            2xl:w-[2vw] 2xl:h-[36vh]"
                                    ></div>

                                    <!-- Mercury -->
                                    <div
                                    id="widget-mercury"
                                    class="absolute bg-orange-500 rounded-t-full
                                            right-0
                                            w-[0.9vw] bottom-[1.8vh]
                                            2xl:w-[1.3vw] 2xl:bottom-[2.4vh]
                                            transition-all duration-700 ease-in-out"
                                    style="height: 0%;"
                                    ></div>


                                    <!-- Bulb -->
                                    <div
                                    class="absolute bottom-0 bg-orange-500 rounded-full
                                            right-[-0.40vw] w-[1.8vw] h-[1.8vw]
                                            2xl:right-[-0.25vw] 2xl:w-[2.4vw] 2xl:h-[2.4vw]"
                                    ></div>

                                    <!-- Scale -->
                                    <div class="absolute left-0 top-0
         flex flex-col justify-between
         text-stone-500 pr-1.5 font-medium
         h-[29vh] text-[0.45vw]
         2xl:h-[36vh] 2xl:text-[0.65vw] 2xl:pr-3">
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
                                        style="font-size: 1.4vw;">--°F</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div
                        class="col-span-4 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                        <div class="grid grid-cols-9 gap-4 min-h-[500px] 2xl:min-h-[1400px]">

                    <!-- คอลัมน์ซ้าย (4 กรอบ) -->
                    <div class="col-span-1 flex flex-col gap-4">
                        <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                        </div>
                        <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                        </div>
                        <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                        </div>
                        <div class="bg-white rounded-2xl flex items-center justify-center transition-all flex-1">
                        </div>
                        <div class="bg-white rounded-2xl flex items-center justify-center transition-all flex-1">
                        </div>
                        <div class="bg-white rounded-2xl flex items-center justify-center transition-all flex-1">
                        </div>
                    </div>

                        <!-- คอลัมน์กลาง (รูปใหญ่) -->
                        <div class="col-span-7 bg-white rounded-2xl flex items-center justify-center transition-all min-h-0">
                                <img src="images/weather.jpeg" alt="เครื่องมือ/อุปกรณ์" class="w-full h-full object-cover rounded-xl">
                        </div>

                        <!-- คอลัมน์ขวา (3 กรอบ) -->
                        <div class="col-span-1 flex flex-col gap-4">
                            <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                                <div class="text-center text-stone-400">
                                    
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                                <div class="text-center text-stone-400">
                                    
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-2xl flex items-center justify-center transition-all flex-1">
                        </div>
                            <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 flex-1">
                                <div class="text-center text-stone-400">
                                    
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl flex items-center justify-center transition-all flex-1">
                        </div>
                        </div>
                    </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-3 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div
                            class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                        แนวโน้มค่า DO
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        24H Data
                                    </p>
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200">
                                    <button id="btnDoDay"
                                        class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600"
                                        type="button">1 วัน</button>
                                    <button id="btnDoMonth"
                                        class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50"
                                        type="button">1 เดือน</button>
                                </div>
                            </div>
                            <div class="2xl:p-8">
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
                        </div>

                        <!-- กราฟที่ 2: Price Trend Chart -->
                        <div
                            class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-primary rounded-full"></span>
                                        ราคาตลาด
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        Market Price</p>
                                </div>
                            </div>
                            <div class="2xl:p-8">
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

                </div>

                <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
                <div class="grid grid-cols-6 gap-4 h-50 shrink-0" id="metrics-cards">
                    <?php
                    $keys = ['do', 'ph', 'ec', 'temp'];
                    $warnings = [
                        'do' => 'ควรอยู่ระหว่าง 3.0-7.0 mg/L',
                        'ph' => 'ควรอยู่ระหว่าง 7.0-8.5',
                        'ec' => 'ควรอยู่ระหว่าง 23K-45K μS/cm',
                        'temp' => 'ควรอยู่ระหว่าง 28-32 °C'
                    ];

                    for ($i = 0; $i < count($metricTitles); $i++): ?>
                        <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm flex flex-col h-32 hover:ring-2 hover:ring-orange-400 transition-all duration-20 shrink-0"
                            id="card-<?= $keys[$i] ?>">
                            <div class="w-full flex justify-between items-center">
                                <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                                    <?= $metricTitles[$i]['title'] ?>
                                </span>
                                <span
                                    class="px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase status"
                                    id="card-<?= $keys[$i] ?>">
                                    --
                                </span>
                            </div>
                            <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                                <?= $metricTitles[$i]['value'] ?>
                            </span>

                            <div class="flex-1 flex items-center justify-center">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-sm font-black text-black value">--</span>
                                    <span class="text-xs font-bold text-stone-400">
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

                                <div class="relative h-1 rounded-full bar">
                                    <div class="fill"></div>
                                </div>

                                <!-- Warning text -->
                                <p class="text-[6px] text-stone-500 font-medium mt-3 text-center">
                                    <?= $warnings[$keys[$i]] ?>
                                </p>
                            </div>

                        </div>
                    <?php endfor; ?>
                </div>

            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 grid grid-rows-4 gap-3 h-full">

                <!-- Card 1: พยากรณ์อุณหภูมิ -->
                <div
                    class="bg-white p-3 rounded-2xl shadow-sm border border-stone-200 transition-all hover:ring-2 hover:ring-orange-400">
                    <div class="flex items-center gap-2 mb-4 2xl:p-8">
                        <span class="carbon--temperature-hot text-[#ff8021] text-sm"></span>
                        <h3 class="text-[10px] 2xl:text-xl font-bold text-slate-700">พยากรณ์อุณหภูมิ</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3 2xl:px-8">
  <!-- ต่ำสุด -->
  <div
    class="bg-cyan-50/50 p-3 rounded-xl text-center border border-cyan-100
           flex flex-col justify-center
           2xl:w-full 2xl:h-32"
  >
    <p class="text-[10px] text-cyan-600 font-bold uppercase mb-1">
      ต่ำสุด (°C)
    </p>
    <p class="text-2xl font-bold text-slate-800" id="temp-min">
      <span class="inline-block animate-pulse">--</span>
    </p>
  </div>

  <!-- สูงสุด -->
  <div
    class="bg-orange-50/50 p-3 rounded-xl text-center border border-orange-100
           flex flex-col justify-center
           2xl:w-full 2xl:h-32"
  >
    <p class="text-[10px] text-orange-600 font-bold uppercase mb-1">
      สูงสุด (°C)
    </p>
    <p class="text-2xl font-bold text-slate-800" id="temp-max">
      <span class="inline-block animate-pulse">--</span>
    </p>
  </div>
</div>

                </div>

                <!-- Card 2: พยากรณ์ลมรายชั่วโมง (ws10m) -->
                <div
                    class="bg-white p-3 2xl:p-14 rounded-2xl shadow-sm border border-stone-200 transition-all hover:ring-2 hover:ring-orange-400">
                    <div class="flex items-center gap-2 mb-4 2xl:p-8">
                        <span class="solar--wind-bold-duotone text-sm text-[#ff8021]"></span>
                        <h3 class="text-[10px] font-bold text-slate-700">พยากรณ์ลมรายชั่วโมง (ws10m)</h3>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-center 2xl:p-8" id="wind-forecast">
                        <!-- Loading state -->
                        <div class="bg-orange-50 p-2 rounded-lg border border-orange-100">
                            <p class="text-[9px] text-[#ff8021] font-bold mb-1">ตอนนี้</p>
                            <p class="text-xs font-bold text-slate-800">--</p>
                            <p class="text-[9px] text-slate-400">(--)</p>
                        </div>
                        <div class="p-2">
                            <p class="text-[9px] text-slate-400 font-bold mb-1">1 ชม.</p>
                            <p class="text-xs font-bold text-slate-600">--</p>
                            <p class="text-[9px] text-slate-300">(--)</p>
                        </div>
                        <div class="p-2">
                            <p class="text-[9px] text-slate-400 font-bold mb-1">2 ชม.</p>
                            <p class="text-xs font-bold text-slate-600">--</p>
                            <p class="text-[9px] text-slate-300">(--)</p>
                        </div>
                        <div class="p-2">
                            <p class="text-[9px] text-slate-400 font-bold mb-1">3 ชม.</p>
                            <p class="text-xs font-bold text-slate-600">--</p>
                            <p class="text-[9px] text-slate-300">(--)</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: พยากรณ์ความชื้นสัมพัทธ์ (rh) -->
                <div
                    class="bg-white border border-stone-200 rounded-2xl p-3 2xl:p-14 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:p-8">
                        <span class="wi--humidity text-sm text-[#ff8021]"></span>
                        <h3 class="text-[10px] font-bold text-slate-700">พยากรณ์ความชื้นสัมพัทธ์ (rh)</h3>
                    </div>
                    <div class="text-center py-2">
                        <span class="text-xl 2xl:text-xl font-black text-slate-800" id="humidity-current">
                            <span class="inline-block animate-pulse">--%</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full mb-4 overflow-hidden">
                        <div class="bg-[#ff8021] h-full rounded-full transition-all duration-500" id="humidity-bar"
                            style="width: 0%"></div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-[10px] text-center font-bold" id="humidity-forecast">
                        <div class="text-slate-400">1 ชม.<br /><span class="text-slate-700">--</span></div>
                        <div class="text-slate-400">2 ชม.<br /><span class="text-slate-700">--</span></div>
                        <div class="text-slate-400">3 ชม.<br /><span class="text-slate-700">--</span></div>
                        <div class="text-slate-400">4 ชม.<br /><span class="text-slate-700">--</span></div>
                    </div>
                </div>

                <!-- Card 4: พยากรณ์สภาพอากาศรายชั่วโมง -->
                <div
                    class="bg-white border border-stone-200 rounded-2xl p-3 2xl:p-14 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-4 2xl:p-8">
                        <span class="emojione-monotone--sun-behind-rain-cloud text-[#ff8021] text-sm"></span>
                        <h3 class="text-[10px] font-bold text-slate-700">พยากรณ์สภาพอากาศรายชั่วโมง</h3>
                    </div>

                    <!-- สภาพอากาศปัจจุบัน -->
                    <div class="flex items-center justify-between mb-2 bg-stone-50 p-3 2xl:p-8 rounded-xl border border-stone-100" 
                        id="current-weather">
                        <div class="flex items-center gap-3 2xl:p-8">
                            <span class="material-symbols-outlined text-yellow-500 text-2xl"
                                id="current-icon">wb_sunny</span>
                            <div>
                                <p class="text-[9px] 2xl:text-[14px] font-bold text-slate-400 uppercase">ตอนนี้</p>
                                <p class="font-bold text-xs text-slate-800" id="current-condition">--</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] 2xl:text-[14px] font-bold text-slate-400 uppercase">ปริมาณฝน</p>
                            <p class="font-bold text-xs text-[#ff8021]" id="current-rain">-- mm/hr</p>
                        </div>
                    </div>

                    <!-- พยากรณ์รายวัน -->
                    <div class="space-y-3 pt-2 2xl:p-8" id="daily-forecast">
                        <!-- Loading state -->
                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-slate-500">กำลังโหลด...</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-300 text-base">cloud_queue</span>
                                <span class="text-slate-700">-- mm/hr</span>
                            </div>
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

    <?php include "../scripts/js-weather.html"; ?>
</body>

</html>