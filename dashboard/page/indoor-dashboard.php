<?php
$Title = "Indoor Dashboard";
$subTitle = "Plant hydroponic intellegent system";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>Indoor System - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col justify-between gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-3 2xl:gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="box col-span-2 flex flex-col bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <div class="rounded-xl px-[1.5vw] flex flex-col items-center justify-center">
                            <div class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Indoor Farm with IoT App.
                            </div>
                            <img src="images/indoor_edited.png" alt="เครื่องมือ/อุปกรณ์" class="object-contain rounded-lg" style="max-height: 30vh;" />
                        </div>
                        <div class="flex flex-col flex-1 px-[1.5vw]">
                            <div class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Indoor Farm with IoT App Architecture
                            </div>

                            <!-- เนื้อหาส่วนล่าง -->
                            <div class="flex justify-between flex-1 gap-1.5 ">

                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_left.php"); ?>
                                </div>
                                <div class=" rounded-lg p-0.5 flex items-center justify-center overflow-hidden min-h-0">
                                    <img src="images/indoor_pieces.jpeg" alt="เครื่องมือ/อุปกรณ์" class="object-contain rounded-md" style="max-height: 30vh;" />
                                </div>
                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_right.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col justify-between gap-3 min-h-0">
                        <!-- ===== กราฟบน ===== -->
                        <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col flex-1 min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                            <div class="flex justify-between items-center mb-2 shrink-0">
                                <div>
                                    <h2 class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                        <span class="w-[0.25vw] h-4 2xl:h-6 bg-orange-500 rounded-full"></span>
                                        <div id="title-graph-sensor">แนวโน้ม</div>
                                    </h2>
                                    <p id="title-graph-sensor-sub" class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                                        Historical Data
                                    </p>
                                </div>
                            </div>
                            <div class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
                                <canvas id="TrendChart" class="absolute inset-0 w-full h-full"></canvas>
                            </div>
                        </div>

                        <!-- ===== กราฟล่าง ===== -->
                        <div class="grid grid-cols-5 gap-3 flex-1 min-h-0">
                            <!-- Market Price (3 ส่วน) -->
                            <div class="col-span-3 bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                                <div class="flex justify-between items-center mb-2 shrink-0">
                                    <div>
                                        <h2 class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                            <span class="w-[0.25vw] h-4 2xl:h-6 bg-primary rounded-full"></span>
                                            แนวโน้มราคาตลาด
                                        </h2>
                                        <p class="text-[0.5vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
                                            Market Price Trend
                                        </p>
                                    </div>
                                    <div id="types-MarketChart" class="flex items-center gap-2 flex-wrap"></div>
                                </div>
                                <div class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md overflow-hidden">
                                    <canvas id="marketPriceChart" class="absolute inset-0 w-full h-full"></canvas>
                                </div>
                            </div>

                            <!-- ช่องขวา (2 ส่วน) -->
                            <div class="col-span-2 bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-3 shadow-sm flex flex-col min-h-0 hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                                <div>
                                    <h3 class="text-[1vw] font-bold font-display text-slate-900 dark:text-white">สัดส่วนรายจ่าย</h3>
                                    <p class="text-[0.5vw] font-bold text-slate-400 uppercase tracking-widest">Expense Distribution</p>
                                </div>
                                <div class="flex flex-col items-center justify-center flex-grow">
                                    <div id="donut" class="relative w-[10vw] h-[10vw] donut-chart shadow-xl">
                                        <div class="absolute inset-4 bg-white dark:bg-stone-900 rounded-full flex flex-col items-center justify-center text-center">
                                            <p class="text-[0.75vw] font-bold text-slate-400 uppercase">Net Profit</p>
                                            <p id="card-4-total" class="text-[1vw] font-bold font-display text-slate-900 dark:text-white leading-none">0</p>
                                            <p class="text-[0.75vw] font-bold text-teal-500 mt-1">THB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
                <div class="row-span-1 grid grid-cols-6 gap-3 shrink-0" id="metrics-cards"></div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">assignment</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200">สิ่งที่ต้องทำวันนี้</h3>
                    </div>

                    <div class="flex justify-between items-center bg-stone-50 dark:bg-stone-900 rounded-lg border border-stone-100 dark:border-stone-400/20">
                        <div class="py-[1vh] px-[0.5vw]">
                            <p class="text-[0.75vw] text-slate-500 font-bold uppercase tracking-wider">งานหลัก</p>
                            <p class="text-[1vw] font-bold text-slate-800 dark:text-white" id="main-task">-</p>
                        </div>
                        <div class="py-[1vh] px-[0.5vw]">
                            <p class="text-[0.75vw] text-[#ff8021] font-bold uppercase tracking-wider">ดินที่แนะนำ</p>
                            <p class="text-[1vw] font-bold text-slate-800 dark:text-white" id="recommended-soil">-</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: การให้น้ำและปุ๋ย -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">opacity</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200">การให้น้ำและปุ๋ย</h3>
                    </div>
                    <div class="space-y-2">
                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-600/20">
                                <p class="text-[0.75vw] text-blue-500 dark:text-blue-200 font-bold uppercase mb-0.5">ตารางเวลา</p>
                                <p class="text-[1vw] font-bold text-blue-600 dark:text-white" id="watering-schedule">-</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/10 p-2 rounded-lg border border-stone-100 dark:border-stone-600/20">
                                <p class="text-[0.75vw] text-yellow-500 dark:text-yellow-200 font-bold uppercase mb-0.5">ปุ๋ย</p>
                                <p class="text-[1vw] font-bold text-stone-600 dark:text-white" id="fertilizer-status">-</p>
                            </div>
                        </div>

                        <!-- Alert Row -->
                        <div class="flex items-center justify-between bg-red-50 dark:bg-red-900/10 p-2 rounded-lg border border-red-100 dark:border-red-600/20">
                            <div class="flex items-center gap-1 ">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-500 text-[1vw]">warning</span>
                                <span class="text-[0.75vw] font-bold text-red-700 dark:text-white">ศัตรูพืช</span>
                            </div>
                            <span class="text-[0.75vw] font-medium text-red-600 dark:text-white" id="pest-alert">-</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[2vw] clarity--coin-bag-line"></span>
                            <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200">ต้นทุนทรัพยากรวันนี้</h3>
                        </div>
                        <div onclick="calculateElectricityAndWater('card-3')" class="flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-xl text-stone-500 dark:text-stone-400">refresh</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-[0.5vw] mt-[0.5vh]">
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.45vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-tight">ค่าน้ำประปา</span>
                            <div class="flex justify-center p-[0.45vw] text-center">
                                <span id="card-3-water-usage" class="text-[1vw] text-center font-black text-stone-800 dark:text-stone-100 leading-tight">-</span>
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-stone-400"> THB</span>
                            </div>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.45vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-tight">ค่าไฟฟ้า</span>
                            <div class="flex justify-center p-[0.45vw] text-center">
                                <span id="card-3-electricity-usage" class="text-[1vw] text-center font-black text-stone-800 dark:text-stone-100 leading-tight">-</span>
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-stone-400"> THB</span>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-[0.75vh] px-[0.75vw] py-[0.25vw] flex items-center justify-between text-[0.75vw] font-bold uppercase text-primary dark:hover:text-white border border-primary/40 hover:bg-orange-50 dark:hover:bg-orange-900 bg-white dark:bg-orange-900/20 rounded-xl transition-all shadow-sm">
                        <span>Export Report</span>
                        <span class="material-icons-round text-sm hover:translate-y-0.5 transition-transform">download</span>
                    </button>
                </div>

                <!-- Card 4: Performance Ranking -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-primary">analytics</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200">ผลรวม</h3>
                    </div>
                    <div id="card-4-list" class="space-y-1 mt-[0.5vh] flex-1 overflow-auto min-h-0"></div>
                    <div class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รวมต้นทุน</span>
                        <span id="card-4-expense" class="text-[0.65vw] font-bold text-slate-900 dark:text-white">฿0</span>
                    </div>
                    <div class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รายได้คาดการณ์</span>
                        <span id="card-4-forecast" class="text-[0.65vw] font-bold text-slate-900 dark:text-white">฿0</span>
                    </div>
                    <div class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รายได้ทั้งหมด</span>
                        <span id="card-4-income" class="text-[0.65vw] font-bold text-emerald-600 dark:text-emerald-400">฿0</span>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 p-[0.5vw] rounded-xl border border-orange-100 dark:border-orange-900/30">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[0.65vw] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider mb-[0.5vh]">กำไรสุทธิ (Net Profit)</p>
                                <p id="card-4-remain" class="text-[0.8vw] font-bold font-display text-slate-900 dark:text-white leading-none">0</p>
                            </div>
                            <span class="text-[0.8vw] font-bold text-slate-500 dark:text-slate-400">฿</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-indoor.html"; ?>
</body>

</html>