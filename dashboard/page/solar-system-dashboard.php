<?php
$Title ="Container Dashboard";
$subTitle ="Container Intelligence Dashboard";
$classIconHeader = "boxicons--container";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>Container Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include"../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-900/20 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col justify-between gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-3 2xl:gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div
                        class="box col-span-2 flex flex-col bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="rounded-xl px-[1.5vw] flex flex-col items-center justify-center">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 dark:border-stone-500 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Solar System with IoT App.
                            </div>
                            <img src="images/solar_top.png" alt="เครื่องมือ/อุปกรณ์"
                                class="rounded-lg object-cover object-center" style="width: 130em; height: 30vh;" />
                        </div>

                        <div class="flex flex-col flex-1 px-[1.5vw]">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 dark:border-stone-500 font-bold mb-1 mt-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Solar System and Panel
                            </div>
                            <!-- เนื้อหาส่วนล่าง -->
                            <div class="flex justify-between flex-1 gap-1.5">
                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_left.php"); ?>
                                </div>
                                <div class=" rounded-lg p-0.5 flex items-center justify-center overflow-hidden min-h-0">
                                    <img src="images/Container.png" alt="เครื่องมือ/อุปกรณ์"
                                        class="object-contain rounded-md" style="max-height: 30vh;" />
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

                <!-- BOTTOM ROW: Sensor Metrics (6 columns) -->
                <div class="row-span-1 grid grid-cols-6 gap-3 shrink-0" id="metrics-cards"></div>

            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: Power Meter -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="mdi--power-meter"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">Power Meter
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-2 gap-2">
                            <div
                                class="bg-stone-50 dark:bg-stone-900/20 p-[0.25vw] rounded-xl border border-stone-100 dark:border-stone-900/10">
                                <p class="text-[0.65vw] text-slate-500 dark:text-slate-200 font-bold uppercase mb-1">
                                    Voltage</p>
                                <p class="font-bold">
                                    <span class="text-[0.75vw] dark:text-white" id="pwrm-voltage">--</span>
                                    <span class="text-[0.5vw] font-normal text-slate-400">V</span>
                                </p>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-900/20 p-[0.25vw] rounded-xl border border-stone-100 dark:border-stone-900/10">
                                <p class="text-[0.65vw] text-slate-500 dark:text-slate-200 font-bold uppercase mb-1">
                                    Current</p>
                                <p class="font-bold">
                                    <span class="text-[0.75vw] dark:text-white" id="pwrm-current">--</span>
                                    <span class="text-[0.5vw] font-normal text-slate-400">mA</span>
                                </p>
                            </div>
                        </div>
                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-2 gap-2">
                            <div
                                class="bg-stone-50 dark:bg-stone-900/20 p-[0.25vw] rounded-xl border border-stone-100 dark:border-stone-900/10">
                                <p class="text-[0.65vw] text-slate-500 dark:text-slate-200 font-bold uppercase mb-1">
                                    Power</p>
                                <p class="font-bold">
                                    <span class="text-[0.75vw] dark:text-white" id="pwrm-power">--</span>
                                    <span class="text-[0.5vw] font-normal text-slate-400">kW</span>
                                </p>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-900/20 p-[0.25vw] rounded-xl border border-stone-100 dark:border-stone-900/10">
                                <p class="text-[0.65vw] text-slate-500 dark:text-slate-200 font-bold uppercase mb-1">
                                    Energy</p>
                                <p class="font-bold">
                                    <span class="text-[0.75vw] dark:text-white" id="pwrm-energy">--</span>
                                    <span class="text-[0.5vw] font-normal text-slate-400">kWh</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Solar Supply/Load -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="ic--round-solar-power"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">Solar Supply/Load
                        </h3>
                    </div>
                    <div class="space-y-2">
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between text-[0.65vw] font-bold text-slate-500 dark:text-slate-200 uppercase tracking-wider px-1">
                            <span>Metrics</span>
                            <div class="flex gap-3">
                                <span class="w-10 text-center text-[#ff8021]">1h</span>
                                <span class="w-10 text-center text-[#ff8021]">3h</span>
                                <span class="w-10 text-center text-[#ff8021]">12h</span>
                            </div>
                        </div>

                        <!-- Supply Row -->
                        <div
                            class="flex items-center justify-between bg-stone-50 dark:bg-stone-900/20 p-0.1 rounded-lg border border-stone-100">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-green-600 text-[1vw]">arrow_upward</span>
                                <span class="text-[0.65vw] font-bold text-slate-700 dark:text-slate-200">Supply</span>
                            </div>
                            <div class="flex gap-3 text-[0.65vw] font-bold">
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="supply-1h">--</span>
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="supply-3h">--</span>
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="supply-12h">--</span>
                            </div>
                        </div>

                        <!-- Load Row -->
                        <div
                            class="flex items-center justify-between bg-stone-50 dark:bg-stone-900/20 p-0.1 rounded-lg border border-stone-100">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-red-600 text-[1vw]">arrow_downward</span>
                                <span class="text-[0.65vw] font-bold text-slate-700 dark:text-slate-200">Load</span>
                            </div>
                            <div class="flex gap-3 text-[0.65vw] font-bold">
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="load-1h">--</span>
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="load-3h">--</span>
                                <span class="w-10 text-center text-slate-800 dark:text-white" id="load-12h">--</span>
                            </div>
                        </div>

                        <!-- Balance Row -->
                        <div
                            class="flex items-center justify-between bg-stone-50 dark:bg-stone-900/20 p-0.1 rounded-lg border border-stone-100">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[#ff8021] text-[1vw]">balance</span>
                                <span class="text-[0.75vw] font-bold text-slate-700 dark:text-slate-200">Balance</span>
                            </div>
                            <div class="flex gap-3 text-[0.65vw] font-bold">
                                <span class="w-10 text-center text-[#ff8021]" id="balance-1h">--</span>
                                <span class="w-10 text-center text-[#ff8021]" id="balance-3h">--</span>
                                <span class="w-10 text-center text-[#ff8021]" id="balance-12h">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: เงินคืนทุน -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="game-icons--receive-money"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">เงินคืนทุน
                        </h3>
                    </div>
                    <div class="space-y-1">
                        <div
                            class="flex items-center justify-between p-1.5 bg-slate-50 dark:bg-slate-900/10 rounded-lg border border-transparent hover:border-slate-200 transition-all">
                            <span class="text-[0.65vw] font-bold text-slate-700 dark:text-slate-200">ทุนเริ่มต้น</span>
                            <div class="flex gap-2 text-[0.5vw] font-medium">
                                <span class="w-12 text-end text-slate-700 dark:text-slate-300"
                                    id="initial-capital">-</span>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between p-1.5 bg-slate-50 dark:bg-slate-900/10 rounded-lg border border-transparent hover:border-slate-200 transition-all">
                            <span class="text-[0.65vw] font-bold text-slate-700 dark:text-slate-200">คืนแล้ว</span>
                            <div class="flex gap-2 text-[0.5vw] font-medium">
                                <span class="w-12 text-end text-green-600 dark:text-green-300" id="refunded">-</span>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between p-1.5 bg-slate-50 dark:bg-slate-900/10 rounded-lg border border-transparent hover:border-slate-200 transition-all">
                            <span class="text-[0.65vw] font-bold text-slate-700 dark:text-slate-200">คงเหลือ</span>
                            <div class="flex gap-2 text-[0.5vw] font-medium">
                                <span class="w-12 text-end text-orange-600 dark:text-orange-300" id="remaining">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Performance Ranking -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="solar--graph-bold"></span>
                        </div>
                        <h3 class="ml-2 text-[1vw] font-bold text-stone-700 dark:text-stone-200">
                            ผลรวม
                        </h3>
                    </div>
                    <div id="card-4-list" class="space-y-1 mt-[0.5vh] flex-1 overflow-auto min-h-0"></div>
                    <div
                        class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รวมต้นทุน</span>
                        <span id="card-4-expense"
                            class="text-[0.65vw] font-bold text-slate-900 dark:text-white">฿0</span>
                    </div>
                    <div
                        class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รายได้คาดการณ์</span>
                        <span id="card-4-forecast"
                            class="text-[0.65vw] font-bold text-slate-900 dark:text-white">฿0</span>
                    </div>
                    <div
                        class="pt-[0.5vh] border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-[0.65vw] font-bold text-slate-500 uppercase">รายได้ทั้งหมด</span>
                        <span id="card-4-income"
                            class="text-[0.65vw] font-bold text-emerald-600 dark:text-emerald-400">฿0</span>
                    </div>
                    <div
                        class="bg-orange-50 dark:bg-orange-900/20 py-[0.25vw] px-[0.5vw] rounded-xl border border-orange-100 dark:border-orange-900/30">
                        <div class="flex justify-between items-end">
                            <div>
                                <p
                                    class="text-[0.65vw] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider mb-[0.5vh]">
                                    กำไรสุทธิ (Net Profit)</p>
                                <p id="card-4-remain"
                                    class="text-[0.8vw] font-bold font-display text-slate-900 dark:text-white leading-none">
                                    0</p>
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
    <?php include "../scripts/js-solar.html"; ?>
</body>

</html>