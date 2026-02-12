<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "ความชื้นในดิน",
        "value" => "(Soil Moisture)",
        "unit"  => "%RH"
    ],
    [
        "title" => "ความนำไฟฟ้า",
        "value" => "(EC)",
        "unit"  => "µS/cm"
    ],
    [
        "title" => "ค่า",
        "value" => "(PH)",
        "unit"  => " "
    ],
    [
        "title" => "อุณหภูมิ",
        "value" => "(Temperature)",
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
<?php include("../styles/css-outdoor.html"); ?>

<head>
    <title>สถานะบ่อเลี้ยงกุ้ง - Dashboard</title>

</head>

<body>

    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
            <?php include 'navbar.php'; ?>
            <div class="size-9 bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="fluent--door-arrow-right-28-regular text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Outdoor Dashboard</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Outdoor Intelligence Dashboard</p>
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
    <div class="grid grid-cols-6 gap-4 w-full">
        <!-- คอลัมน์ซ้าย (เว้นว่าง) -->
        <div class="col-span-1"></div>
        
        <!-- คอลัมน์กลาง (รูปใหญ่ + แถวล่าง 5 กรอบ) -->
        <div class="col-span-4 flex flex-col gap-4">
            <!-- แถวบน 2 กรอบ (ตำแหน่ง 1, 3) -->
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 aspect-[4/3]">
                </div>
                <div class="col-span-1"></div>
                <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 aspect-[4/3]">
                </div>
                <div class="col-span-1"></div>
            </div>
            
            <!-- รูปภาพกลางใหญ่ -->
            <div class="bg-white rounded-2xl p-4 flex items-center justify-center transition-all aspect-[16/9]">
                <img src="images/outdoor.jpeg" alt="เครื่องมือ/อุปกรณ์" class="w-full h-full object-cover rounded-xl">
            </div>
            
            <!-- แถวล่าง 2 กรอบ (ตำแหน่ง 2, 4) -->
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1"></div>
                <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 aspect-[4/3]">
                </div>
                <div class="col-span-1"></div>
                <div class="bg-white rounded-2xl border border-stone-200 2xl:border-stone-800 shadow-sm p-4 flex items-center justify-center transition-all duration-200 aspect-[4/3]">
                </div>
            </div>
        </div>
        
        <!-- คอลัมน์ขวา (เว้นว่าง) -->
        <div class="col-span-1"></div>
    </div>
</div>
                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
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
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
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
                    $keys = ['do', 'ph', 'ec', 'temp'];
                    $warnings = [
                        'do' => 'ควรอยู่ระหว่าง 3.0-7.0 mg/L',
                        'ph' => 'ควรอยู่ระหว่าง 7.0-8.5',
                        'ec' => 'ควรอยู่ระหว่าง 23K-45K μS/cm',
                        'temp' => 'ควรอยู่ระหว่าง 28-32 °C'
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
  
  <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
  <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
    <div class="flex items-center gap-2 mb-2 2xl:px-8 2xl:py-4">
      <span class="material-symbols-outlined text-primary text-xs">checklist</span>
      <h3 class="text-[10px] font-bold text-stone-700">สิ่งที่ต้องทำวันนี้</h3>
    </div>
    <div id="tasks-container" class="space-y-2 2xl:space-y-8 2xl:gap-8 2xl:px-8">
      <!-- สิ่งที่ต้องทำ -->
      <div class="bg-stone-50 p-2 rounded-lg border border-stone-100 2xl:px-8">
        <p class="text-[7px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">สิ่งที่ต้องทำ</p>
        <p class="font-medium text-[9px] text-slate-800" id="task-todo">-</p>
      </div>
      
      <!-- ดินที่ควรจะเป็น -->
      <div class="flex items-center justify-between bg-stone-50 p-1.5 2xl:px-8 rounded-lg border border-stone-100">
        <div>
          <p class="text-[7px] text-slate-500 font-bold uppercase tracking-wider">ดินที่ควรจะเป็น</p>
          <p class="text-[9px] font-bold text-orange-600" id="soil-moisture">-</p>
        </div>
        <div class="text-right">
          <p class="text-[7px] text-slate-500 font-bold uppercase tracking-wider">pH Level</p>
          <p class="text-[9px] font-bold text-slate-700" id="soil-ph">-</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: การให้น้ำและปุ๋ย -->
  <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
    <div class="flex items-center gap-2 mb-2 2xl:p-8">
      <span class="material-symbols-outlined text-primary text-xs">water_drop</span>
      <h3 class="text-[10px] font-bold text-stone-700">การให้น้ำและปุ๋ย</h3>
    </div>
    <div id="watering-container" class="space-y-2 2xl:px-8">
      <!-- Grid 2 Columns -->
      <div class="grid grid-cols-2 gap-1 2xl:gap-8 2xl:mb-8">
        <div class="bg-blue-50 p-2 2xl:px-8 rounded-lg border border-blue-100">
          <p class="text-[7px] text-blue-500 font-bold uppercase mb-0.5">การให้น้ำ</p>
          <p class="text-[9px] font-bold text-blue-600" id="watering-schedule">-</p>
        </div>
        <div class="bg-stone-50 p-2 2xl:px-8 rounded-lg border border-stone-100">
          <p class="text-[7px] text-slate-500 font-bold uppercase mb-0.5">การใส่ปุ๋ย</p>
          <p class="text-[9px] font-bold text-slate-800" id="fertilizer-schedule">-</p>
        </div>
      </div>
      
      <!-- โรค/ศัตรูพืช -->
      <div class="bg-red-50 p-2 2xl:px-8 rounded-lg border border-red-100">
        <p class="text-[7px] text-red-500 font-bold uppercase tracking-wider mb-0.5">โรค/ศัตรูพืชที่ต้องระวัง</p>
        <p class="text-[9px] font-bold text-red-600" id="pest-warning">-</p>
      </div>
    </div>
  </div>

  <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
  <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
    <div class="flex items-center gap-2 mb-2 2xl:p-8">
      <span class="material-symbols-outlined text-primary text-xs">analytics</span>
      <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนทรัพยากรวันนี้</h3>
    </div>
    <div id="resource-cost-container" class="space-y-1">
      <!-- Grid 2 Columns -->
      <div class="grid grid-cols-2 gap-1 2xl:gap-8 2xl:p-8">
        <div class="bg-stone-50 p-2 2xl:px-8 rounded-lg border border-stone-100">
          <p class="text-[7px] text-slate-500 font-bold uppercase mb-0.5">ค่าน้ำประปา</p>
          <p class="text-[10px] font-bold text-slate-800 flex justify-center">
            <span id="water-usage">-</span> 
            <span class="text-[8px] font-normal text-slate-400">m³</span>
          </p>
        </div>
        <div class="bg-stone-50 p-2 2xl:px-8 rounded-lg border border-stone-100">
          <p class="text-[7px] text-slate-500 font-bold uppercase mb-0.5">ค่าไฟฟ้า</p>
          <p class="text-[10px] font-bold text-slate-800 flex justify-center">
            <span id="electricity-usage">-</span> 
            <span class="text-[8px] font-normal text-slate-400">kWh</span>
          </p>
        </div>
      </div>
      
      <!-- เป้าหมาย -->
      <div class="text-[8px] text-slate-400 text-start 2xl:px-8">
        <span>เป้าหมายวันนี้: <span id="resource-target">-</span></span>
      </div>
    </div>
  </div>

  <!-- Card 4: ต้นทุนรวมวันนี้ -->
  <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
    <div class="flex items-center gap-2 mb-2 2xl:p-8">
      <span class="material-symbols-outlined text-primary text-xs">payments</span>
      <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนรวมวันนี้</h3>
    </div>
    <div id="total-cost-container" class="space-y-1 2xl:space-y-4 2xl:px-8">
      <!-- รายการค่าใช้จ่าย -->
      <div class="flex items-center justify-between p-1 2xl:px-4 bg-green-50 rounded-md border border-green-100">
        <div class="flex items-center gap-1 2xl:px-1">
                    <span class="material-symbols-outlined text-green-600 text-[8px]">check_circle</span>
                    <span class="text-[7px] 2xl:px-4 font-bold text-green-700 uppercase">ของใช้วันนี้</span>
                </div>
        <span class="text-[7px] font-bold text-green-700" id="daily-supplies">-</span>
      </div>

      <div class="flex items-center justify-between p-1 2xl:px-4 bg-yellow-50 rounded-md border border-yellow-100">
        <div class="flex items-center gap-1 2xl:px-1">
                    <span class="material-symbols-outlined text-yellow-600 text-[8px]">bolt</span>
                    <span class="text-[7px] 2xl:px-4 font-bold text-yellow-700 uppercase">น้ำและไฟ</span>
                </div>
        <span class="text-[7px] font-bold text-yellow-700" id="utility-cost">-</span>
      </div>

      <div class="flex items-center justify-between p-1 2xl:px-4 bg-red-50 rounded-md border border-red-100">
        <div class="flex items-center gap-1 2xl:px-1">
                    <span class="material-symbols-outlined text-red-600 text-[8px]">more_horiz</span>
                    <span class="text-[7px] 2xl:px-4 font-bold text-red-700 uppercase">อื่นๆ</span>
                </div>
        <span class="text-[7px] font-bold text-red-700" id="other-cost">-</span>
      </div>

      <!-- Total -->
      <div class="pt-1.5 mt-0.75 2xl:px-4 border-t border-slate-200 flex justify-between items-center">
        <span class="font-bold text-slate-800 uppercase text-[6px] tracking-wider">รวม</span>
        <span class="font-bold text-[11px] text-primary" id="total-cost">-</span>
      </div>
    </div>
  </div>
  </div>
  </div>
  </main>

    <!-- Footer -->
    <footer class="px-6 py-2 border-t border-stone-200 bg-white flex justify-between flex-row-reverse shrink-0">
        <div class="flex flex-center gap-2">
            <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 1.0</span>
            <div class="h-3 w-px bg-stone-200"></div>
            <span class="text-[9px] font-bold text-primary uppercase">smart farm system</span>
        </div>
    </footer>

    <?php include "../scripts/js-outdoor.html"; ?>
</body>

</html>