<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');
?>

<!DOCTYPE html>
<html class="light" lang="th">

<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-icon.html"); ?>
<?php include("../styles/css-outdoor.html"); ?>

<head>
    <title>Outdoor System - Dashboard</title>
</head>

<body>

    <!-- Header -->
    <header class="flex items-center justify-between p-4 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
            <?php include 'navbar.php'; ?>
            <div class="size-9 w-[5rem] h-[5rem] bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="fluent--door-arrow-right-28-regular text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-[1.75vw] text-lg font-bold leading-none">Outdoor Dashboard</h1>
                <p class="text-[1vw] text-stone-500 font-medium uppercase tracking-wider mt-1">Outdoor Intelligence Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 bg-stone-100 p-1.5 px-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <span class="text-[1vw] text-stone-600 font-bold leading-none" id="start-date"> -- --- ---- </span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 pl-6">
                <span class="text-[1vw] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1">อัปเดตล่าสุด</span>
                <span class="text-[1vw] font-bold text-stone-800" id="last-update"><?php echo $currentTime; ?></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-4 gap-4 overflow-hidden">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-4 h-full">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 grid grid-rows-8 gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-4 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex-row items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 ">
                        <!-- <div class="flex-row "> -->

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="col-span-8 bg-white rounded-2xl p-2 flex-column items-center justify-center transition-all min-h-[300px]">
                            <div class="p-2 bg-stone-100 rounded-xl font-bold mb-2">Outdoor Farm with IoT App.</div>
                            <img src="images/outdoor_main.png" alt="เครื่องมือ/อุปกรณ์" class="w-full max-h-[400px] object-cover rounded-xl">
                        </div>

                        <div class="grid grid-cols-12 gap-4 2xl:gap-1 2xl:min-h-[300px]">
                            <?php include("../components/sensors_left.php"); ?>

                            <div class="col-span-8 bg-white rounded-2xl p-2 flex items-center justify-center transition-all">
                            <img src="images/outdoor.jpeg" alt="เครื่องมือ/อุปกรณ์" class="w-full max-h-[400px] object-cover rounded-xl">
                            </div>

                            <?php include("../components/sensors_right.php"); ?>
                        </div>
                    </div>
                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
                                <div>
                                    <h2 id="title-graph-sensor" class="text-[1vw] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                        กำลังโหลดข้อมูล...
                                    </h2>
                                    <p id="title-graph-sensor-sub" class="text-[0.75vw] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        Loading data...
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200">
                                    <button id="btnDoDay" class="px-2 py-0.5 text-[1vw] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                    <button id="btnDoMonth" class="px-2 py-0.5 text-[1vw] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
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
                                <canvas id="TrendChart" class="absolute inset-0"></canvas>
                            </div>
                        </div>

                        <!-- กราฟที่ 2: Price Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-8 shrink-0">
                                <div>
                                    <h2 class="text-[1vw] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-primary rounded-full"></span>
                                        แนวโน้มราคาตลาด
                                    </h2>
                                    <p class="text-[0.75vw] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price Trend</p>
                                </div>
                                <div id="types-MarketChart" class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-md border border-stone-200 px-2 py-0.5 text-[1vw] ">
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
                <div class="grid grid-cols-6 gap-1 shrink-0" id="metrics-cards">
                </div>

            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 grid grid-rows-4 gap-3 h-full">

                <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:px-8 2xl:py-4">
                        <span class="material-symbols-outlined text-primary text-xs">checklist</span>
                        <h3 class="text-[1vw] font-bold text-stone-700">สิ่งที่ต้องทำวันนี้</h3>
                    </div>
                    <div id="tasks-container" class="space-y-2 2xl:space-y-8 2xl:gap-8 2xl:px-8">
                        <!-- สิ่งที่ต้องทำ -->
                        <div class="bg-stone-50 p-2 rounded-lg border border-stone-100 2xl:px-8">
                            <p class="text-[0.75vw] text-slate-500 font-bold uppercase tracking-wider mb-0.5">สิ่งที่ต้องทำ</p>
                            <p class="font-medium text-[1vw] text-slate-800" id="task-todo">-</p>
                        </div>

                        <!-- ดินที่ควรจะเป็น -->
                        <div class="flex items-center justify-between bg-stone-50 p-1.5 2xl:px-8 rounded-lg border border-stone-100">
                            <div>
                                <p class="text-[0.75vw] text-slate-500 font-bold uppercase tracking-wider">ดินที่ควรจะเป็น</p>
                                <p class="text-[1vw] font-bold text-orange-600" id="soil-moisture">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[0.75vw] text-slate-500 font-bold uppercase tracking-wider">pH Level</p>
                                <p class="text-[1vw] font-bold text-slate-700" id="soil-ph">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: การให้น้ำและปุ๋ย -->
                <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-xs">water_drop</span>
                        <h3 class="text-[1vw] font-bold text-stone-700">การให้น้ำและปุ๋ย</h3>
                    </div>
                    <div id="watering-container" class="space-y-2 2xl:px-8">
                        <!-- Grid 2 Columns -->
                        <div class="grid grid-cols-2 gap-1 2xl:gap-8 2xl:mb-8">
                            <div class="bg-blue-50 p-2 2xl:px-8 rounded-lg border border-blue-100">
                                <p class="text-[0.75vw] text-blue-500 font-bold uppercase mb-0.5">การให้น้ำ</p>
                                <p class="text-[1vw] font-bold text-blue-600" id="watering-schedule">-</p>
                            </div>
                            <div class="bg-stone-50 p-2 2xl:px-8 rounded-lg border border-stone-100">
                                <p class="text-[0.75vw] text-slate-500 font-bold uppercase mb-0.5">การใส่ปุ๋ย</p>
                                <p class="text-[1vw] font-bold text-slate-800" id="fertilizer-schedule">-</p>
                            </div>
                        </div>

                        <!-- โรค/ศัตรูพืช -->
                        <div class="bg-red-50 p-2 2xl:px-8 rounded-lg border border-red-100">
                            <p class="text-[0.75vw] text-red-500 font-bold uppercase tracking-wider mb-0.5">โรค/ศัตรูพืชที่ต้องระวัง</p>
                            <p class="text-[1vw] font-bold text-red-600" id="pest-warning">-</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] clarity--coin-bag-line"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700">ต้นทุนทรัพยากรไฟฟ้าและน้ำทั้งหมด</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2  2xl:px-8">
                        <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center">
                            <span class="text-[1vw] text-stone-400 font-bold uppercase">ค่าน้ำประปา</span>
                            <span class="text-[1vw] text-center font-black text-stone-800" id="water-usage">-</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center">
                            <span class="text-[1vw] text-stone-400 font-bold uppercase">ค่าไฟฟ้า</span>
                            <span class="text-[1vw] text-center font-black text-stone-800" id="electricity-usage">-</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: ต้นทุนรวมวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-xs">payments</span>
                        <h3 class="text-[1vw] font-bold text-stone-700">ต้นทุนรวมวันนี้</h3>
                    </div>
                    <div id="total-cost-container" class="space-y-1 2xl:space-y-4 2xl:px-8">
                        <!-- รายการค่าใช้จ่าย -->
                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-green-50 rounded-md border border-green-100">
                            <div class="flex items-center gap-1 2xl:px-1">
                                <span class="material-symbols-outlined text-green-600 text-[1vw]">check_circle</span>
                                <span class="text-[0.75vw] 2xl:px-4 font-bold text-green-700 uppercase">ของใช้วันนี้</span>
                            </div>
                            <span class="text-[0.75vw] font-bold text-green-700" id="daily-supplies">-</span>
                        </div>

                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-yellow-50 rounded-md border border-yellow-100">
                            <div class="flex items-center gap-1 2xl:px-1">
                                <span class="material-symbols-outlined text-yellow-600 text-[1vw]">bolt</span>
                                <span class="text-[0.75vw] 2xl:px-4 font-bold text-yellow-700 uppercase">น้ำและไฟ</span>
                            </div>
                            <span class="text-[0.75vw] font-bold text-yellow-700" id="utility-cost">-</span>
                        </div>

                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-red-50 rounded-md border border-red-100">
                            <div class="flex items-center gap-1 2xl:px-1">
                                <span class="material-symbols-outlined text-red-600 text-[1vw]">more_horiz</span>
                                <span class="text-[0.75vw] 2xl:px-4 font-bold text-red-700 uppercase">อื่นๆ</span>
                            </div>
                            <span class="text-[0.75vw] font-bold text-red-700" id="other-cost">-</span>
                        </div>

                        <!-- Total -->
                        <div class="pt-1.5 mt-0.75 2xl:px-4 border-t border-slate-200 flex justify-between items-center">
                            <span class="font-bold text-slate-800 uppercase text-[0.75vw] tracking-wider">รวม</span>
                            <span class="font-bold text-[1vw] text-primary" id="total-cost">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-outdoor.html"; ?>
</body>

</html>