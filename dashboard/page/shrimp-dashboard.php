<?php
$Title = "Shrimp Dashboard";
$subTitle = "Smart Vertical Raised Shrimp Farming System(RAS) with IoT App";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>สถานะบ่อเลี้ยงกุ้ง - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 grid grid-rows-8 gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-3 2xl:gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="box col-span-2 flex flex-col bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="rounded-xl px-[1.5vw] flex flex-col items-center justify-center">
                            <div class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Smart Vertical Raised Shrimp Farming System(RAS) with IoT App
                            </div>
                            <img src="images/shrimp_main.jpg" alt="เครื่องมือ/อุปกรณ์" class="object-contain rounded-lg" style="max-height: 30vh;" />
                        </div>

                        <div class="flex flex-col flex-1 px-[1.5vw]">
                            <div class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Chicken Farm IoT System Architecture
                            </div>

                            <!-- เนื้อหาส่วนล่าง -->
                            <div class="flex justify-between flex-1 gap-1.5 ">

                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_left.php"); ?>
                                </div>
                                <div class=" rounded-lg p-0.5 flex items-center justify-center overflow-hidden min-h-0">
                                    <img src="images/shrimp.png" alt="เครื่องมือ/อุปกรณ์" class="object-contain rounded-md" style="max-height: 30vh;" />
                                </div>
                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_right.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-3 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-2 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-1 shrink-0">
                                <div>
                                    <h2 class="text-[1vw] font-bold text-stone-800 dark:text-stone-100 flex items-center gap-1.5">
                                        <span class="w-1 h-2 2xl:h-6 2xl:w-3 bg-orange-500 rounded-full"></span>
                                        <div id="title-graph-sensor">
                                            กำลังโหลดข้อมูล...
                                        </div>
                                    </h2>
                                    <p id="title-graph-sensor-sub" class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-0.5">
                                        Loading data...
                                    </p>
                                </div>
                            </div>
                            <div class="flex-1 min-h-0 relative border-l border-b border-stone-200 dark:border-stone-700 rounded-md">
                                <div id="do-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                    <div class="flex gap-1">
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                        <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                    </div>
                                </div>
                                <canvas id="TrendChart" class="absolute inset-0"></canvas>
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
                <div class="row-span-1 grid grid-cols-6 gap-1 shrink-0" id="metrics-cards">

                </div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <!-- <div class="col-span-2 grid grid-rows-4 gap-2.5 min-h-0"> -->
            <div class="col-span-2 flex flex-col gap-2.5 min-h-0">
                <!-- Card 1: การให้อาหารวันนี้ -->
                <div class="bg-white dark:bg-stone-900 flex-1 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[1vw]">restaurant</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4 ">การให้อาหารวันนี้</h3>
                    </div>
                    <div>
                        <div class="grid grid-cols-2 gap-2  " id="feeding-info">
                            <div class="bg-stone-50 dark:bg-stone-900 rounded-lg p-[0.25vw] flex flex-col justify-center h-full">
                                <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">จำนวนมื้อ</span>
                                <span class="text-[0.75vw] font-black text-slate-900 dark:text-white" id="meals-count">--</span>
                            </div>
                            <div class="bg-stone-50 dark:bg-stone-900 rounded-lg p-[0.25vw] flex flex-col justify-center 2xl:px-2 h-full">
                                <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปริมาณ(กรัม)</span>
                                <span class="text-[0.75vw] font-black text-slate-900 dark:text-white" id="volume-meal">-</span>
                            </div>
                            <div class="col-span-2 bg-primary/5 rounded-lg p-1.5 py-2 border border-primary/10 dark:border-primary/20 flex justify-between items-center">
                                <span class="text-[0.75vw] text-primary font-bold uppercase">ปริมาณรวมที่ต้องกิน(กรัม)</span>
                                <span class="text-[0.75vw] font-black text-primary" id="total-meal-per-day">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white dark:bg-stone-900 flex-1 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[2vw] clarity--coin-bag-line"></span>
                            <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">ต้นทุนทรัพยากรวันนี้</h3>
                        </div>
                        <div onclick="calculateElectricityAndWater('card-2')" class="flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-xl text-stone-500 dark:text-stone-400">refresh</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-[0.5vw] mt-[0.5vh]">
                        <div class="bg-stone-50 dark:bg-stone-900 rounded-lg p-[0.45vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-tight">ค่าน้ำประปา</span>
                            <div class="flex justify-center p-[0.45vw] text-center">
                                <span id="card-2-water-usage" class="text-[1vw] text-center font-black text-stone-800 dark:text-stone-100 leading-tight">-</span>
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-stone-400"> THB</span>
                            </div>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-900 rounded-lg p-[0.45vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-tight">ค่าไฟฟ้า</span>
                            <div class="flex justify-center p-[0.45vw] text-center">
                                <span id="card-2-electricity-usage" class="text-[1vw] text-center font-black text-stone-800 dark:text-stone-100 leading-tight">-</span>
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-stone-400"> THB</span>
                            </div>
                        </div>
                    </div>
                    <button class="w-full mt-[0.75vh] px-[0.75vw] py-[0.25vw] flex items-center justify-between text-[0.75vw] font-bold uppercase text-primary dark:hover:text-white border border-primary/40 hover:bg-orange-50 dark:hover:bg-orange-900 bg-white dark:bg-orange-900/20 rounded-xl transition-all shadow-sm">
                        <span>Export Report</span>
                        <span class="material-icons-round text-sm hover:translate-y-0.5 transition-transform">download</span>
                    </button>
                </div>

                <!-- Card 3: คุณภาพน้ำที่เหมาะสม -->
                <div class="bg-white dark:bg-stone-900 flex-1 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[1vw]">waves</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">คุณภาพน้ำที่เหมาะสม</h3>
                    </div>
                    <div>
                        <div>
                            <table class="w-full">
                                <tbody class="text-[0.75vw]  divide-y divide-stone-50">
                                    <tr class="">
                                        <td class="py-1 text-stone-500 text-[0.75vw] dark:text-slate-300 2xl;pl-4">DO</td>
                                        <td class="py-1 text-right  text-stone-700  dark:text-white 2xl;pr-4">3.0-7.0 mg/L</td>
                                    </tr>
                                    <tr class="">
                                        <td class="py-1 text-stone-500 text-[0.75vw] dark:text-slate-300 2xl;pl-4">pH</td>
                                        <td class="py-1 text-right  text-stone-700 dark:text-white 2xl;pr-4">7.5 - 8.5</td>
                                    </tr>
                                    <tr class="">
                                        <td class="py-1 text-stone-500 text-[0.75vw] dark:text-slate-300 2xl;pl-4">EC</td>
                                        <td class="py-1 text-right  text-stone-700 dark:text-white 2xl;pr-4">23K-45K μS/cm</td>
                                    </tr>
                                    <tr class="">
                                        <td class="py-1 text-stone-500 text-[0.75vw] dark:text-slate-300 2xl;pl-4">Temp</td>
                                        <td class="py-1 text-right  text-stone-700 dark:text-white 2xl;pr-4">28-32 °C</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 4: การปรับอาหาร -->
                <div class="bg-white dark:bg-stone-900 flex-1 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[1vw]">rule</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">การปรับอาหาร</h3>
                    </div>
                    <div class="flex flex-col gap-1 2xl:px-2 ">
                        <div class="flex items-center justify-between px-2 py-2 bg-success/5 border border-success/10 dark:bg-green-900 rounded-lg">
                            <span class="text-[0.75vw] font-bold text-success dark:text-green-400 uppercase 2xl:pl-4">หมดเกลี้ยง</span>
                            <span class="text-[0.75vw] text-stone-700 dark:text-white 2xl:pr-4">+5 ถึง +10%</span>
                        </div>
                        <div class="flex items-center justify-between px-2 py-2 bg-warning/5 border border-warning/10 dark:bg-yellow-800 rounded-lg">
                            <span class="text-[0.75vw] font-bold text-warning dark:text-yellow-400 uppercase 2xl:pl-4">เหลือเล็กน้อย</span>
                            <span class="text-[0.75vw] text-stone-700 dark:text-white 2xl:pr-4">คงที่ / -5%</span>
                        </div>
                        <div class="flex items-center justify-between px-2 py-2 bg-danger/5 border border-danger/10 dark:bg-red-900 rounded-lg">
                            <span class="text-[0.75vw] font-bold text-danger dark:text-red-100 uppercase 2xl:pl-4">เหลือเยอะ</span>
                            <span class="text-[0.75vw] text-stone-700 dark:text-white 2xl:pr-4">งด / -50%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-shrimp.html"; ?>
</body>

</html>