<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');
?>

<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>สถานะฟาร์มเลี้ยงไก่ - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">

    <!-- Header -->
    <header class="flex items-center p-[0.5vw] border-b border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 shrink-0 transition-colors duration-300">
        <div class="flex items-center gap-3">
            <?php include 'navbar.php'; ?>
            <div class="size-9 w-[3rem] h-[3rem] bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="emojione-monotone--chicken text-2xl text-white"></span>
            </div>
            <div>
                <h1 class="text-[#1d130c] dark:text-orange-300 text-[1.5vw] font-bold leading-none">Chicken Dashboard</h1>
                <p class="text-[0.75vw] text-stone-500 dark:text-stone-400 font-medium uppercase tracking-wider mt-0.5">Chicken Farm Intelligence Dashboard</p>
            </div>
        </div>

        <div class="flex items-center gap-4 ml-auto">
        <!-- Dark Mode Toggle Button -->
         <div class="flex flex-col items-end">
        <button onclick="toggleDarkMode()"
            class="flex items-center gap-2 bg-stone-100 hover:bg-stone-200 dark:bg-stone-700 dark:hover:bg-stone-600 border border-stone-200 dark:border-stone-600 px-3 py-2 rounded-xl transition-all duration-200">
            <!-- Moon icon (แสดงตอน Light mode) -->
            <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-stone-600 dark:text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>
            </svg>
            <!-- Sun icon (แสดงตอน Dark mode) -->
            <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-400" style="display:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
            <span id="mode-label" class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300">Dark</span>
        </button>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex flex-col items-center gap-2 bg-stone-100 dark:bg-stone-800 box p-1 px-3 rounded-xl border border-stone-200 dark:border-stone-700">
                <div class="flex items-center gap-2">
                    <span class="text-[0.75vw] font-bold text-primary leading-none">อายุไก่ปัจจุบัน:</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none" id="day-age">--</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none">วัน</span>
                    <div class="w-px h-2.5 bg-stone-300 dark:bg-stone-600"></div>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">เหลือ</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none" id="remain-live">-</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">ตัว</span>
                </div>
                <div class="h-px w-full bg-stone-300 dark:bg-stone-600"></div>
                <div class="flex items-center gap-2">
                    <span class="text-[0.75vw] font-bold text-primary leading-none">คาดการณ์รายได้:</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none" id="forecast-income">--</span>
                    <span class="text-[0.75vw] font-bold text-primary leading-none">บาท</span>
                    <div class="w-px h-2.5 bg-stone-300 dark:bg-stone-600"></div>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">น้ำหนัก</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none" id="kilogram">-</span>
                    <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 leading-none">กิโล/ตัว</span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 dark:border-stone-700 pl-4">
                <span class="text-[0.75vw] font-bold text-stone-400 uppercase tracking-widest leading-none mb-0.5">อัปเดตล่าสุด</span>
                <span class="text-[0.75vw] font-bold text-stone-600 dark:text-stone-300 text-center" id="last-update"><?php echo $currentTime; ?></span>
                <span class="text-[0.75vw] text-stone-600 dark:text-stone-300 font-bold leading-none" id="start-date"> -- --- ---- </span>
            </div>
        </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col justify-between gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-3 2xl:gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="box col-span-2 grid grid-rows-2 bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0 overflow-hidden">

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="row-span-1 rounded-xl p-[1.5vw] flex flex-col items-center justify-center overflow-hidden">
                            <div class="p-[0.5vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">Smart chicken farming system with AI health monitoring cameras and IoT app.</div>
                            <img src="images/chicken-top.png" alt="เครื่องมือ/อุปกรณ์" class="max-w-full max-h-full object-contain rounded-lg" />
                        </div>

                        <div class="row-span-1 flex flex-col min-h-0">

                            <!-- หัวข้อส่วนล่าง -->
                            <div class="px-[1.5vw]">
                             <div class="p-[0.5vw] pb-[2vw]">
                            <div class="p-[0.5vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Chicken Farm IoT System Architecture
                            </div>

                            <!-- เนื้อหาส่วนล่าง -->
                            <div class="flex justify-between gap-1.5 flex-1 min-h-0">
                                
                                <div class="flex flex-col justify-between">
                                <?php include("../components/sensors_left.php"); ?>
                            </div>
                            <div class=" rounded-lg p-0.5 flex items-center justify-center overflow-hidden min-h-0">
                                <img src="images/chicken-b.png" alt="เครื่องมือ/อุปกรณ์" class="max-w-full max-h-full object-contain rounded-md">
                            </div>
                            <div class="flex flex-col justify-between">
                                <?php include("../components/sensors_right.php"); ?>
                            </div>
                            </div>
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
                                    <h2 class="text-sm 2xl:text-base font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                        <span class="w-1 h-4 2xl:h-6 bg-orange-500 rounded-full"></span>
                                        <div id="title-graph-sensor">แนวโน้ม</div>
                                    </h2>
                                    <p id="title-graph-sensor-sub" class="text-xs text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
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
                                        <h2 class="text-sm 2xl:text-base font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2">
                                            <span class="w-1 h-4 2xl:h-6 bg-primary rounded-full"></span>
                                            แนวโน้มราคาตลาด
                                        </h2>
                                        <p class="text-xs text-stone-400 dark:text-stone-500 font-medium uppercase tracking-wider mt-1">
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
                                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white">สัดส่วนรายจ่าย</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Expense Distribution</p>
                                </div>
                                <div class="flex flex-col items-center justify-center flex-grow">
                                    <div id="donut" class="relative w-48 h-48 donut-chart shadow-xl">
                                        <div class="absolute inset-4 bg-white dark:bg-stone-900 rounded-full flex flex-col items-center justify-center text-center">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Net Profit</p>
                                            <p id="card-4-total" class="text-2xl font-bold font-display text-slate-900 dark:text-white leading-none">0</p>
                                            <p class="text-[10px] font-bold text-teal-500 mt-1">THB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM ROW: Sensor Metrics (6 columns) -->
                <div class="row-span-1 grid grid-cols-6 gap-1 shrink-0" id="metrics-cards"></div>

            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: การให้อาหารวันนี้ -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] fluent--food-20-regular"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">การให้อาหารวันนี้</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-[0.5vh]">
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg py-[1vh] px-[0.5vw] flex flex-col justify-center h-full">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">จำนวนมื้อ</span>
                            <span class="text-[0.75vw] font-black text-stone-800 dark:text-stone-100" id="meals-count">--</span>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg py-[1vh] px-[0.5vw] flex flex-col justify-center h-full">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปริมาณ</span>
                            <span class="text-[0.75vw] font-black text-stone-800 dark:text-stone-100" id="volume-meal">-</span>
                        </div>
                        <div class="col-span-2 bg-primary/5 dark:bg-primary/10 rounded-lg p-[0.5vw] py-[0.5vh] border border-primary/10 dark:border-primary/20 flex justify-between items-center">
                            <span class="text-[0.75vw] text-primary font-bold uppercase">ปริมาณรวมที่ต้องกิน(กรัม/วัน)</span>
                            <span class="text-[0.75vw] font-black text-primary" id="total-meal-per-day">-</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ความชื้นและแสงสว่าง -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] mage--light-bulb"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">ความชื้นและแสงสว่าง</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-[0.5vw] my-[1vh]">
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg py-[1vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ความชื้นสูงสุด</span>
                            <span class="text-[0.75vw] font-black text-stone-800 dark:text-stone-100" id="humidity">-</span>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg py-[1vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase">เวลาให้แสง</span>
                            <span class="text-[0.75vw] font-black text-primary" id="light-time">-</span>
                        </div>
                    </div>
                    <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.5vw] flex justify-between items-center">
                        <span class="text-[0.75vw] text-primary font-bold uppercase">ชั่วโมงรวมวันนี้</span>
                        <span class="text-[0.75vw] font-black text-stone-800 dark:text-stone-100" id="total-hours">-</span>
                    </div>
                </div>

                <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[2vw] clarity--coin-bag-line"></span>
                            <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">ต้นทุนทรัพยากรวันนี้</h3>
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
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-slate-400"> THB</span>
                            </div>
                        </div>
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.45vw] flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-tight">ค่าไฟฟ้า</span>
                            <div class="flex justify-center p-[0.45vw] text-center">
                                <span id="card-3-electricity-usage" class="text-[1vw] text-center font-black text-stone-800 dark:text-stone-100 leading-tight">-</span>
                                <span class="text-[0.6vw] flex items-end text-slate-500 dark:text-slate-400"> THB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Performance Ranking -->
                <div class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-primary">analytics</span>
                        <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200 2xl:pl-4">ผลรวม</h3>
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
    <?php include "../scripts/js-chicken.html"; ?>

    <script>
    function toggleDarkMode() {
        const isDark = document.documentElement.classList.toggle('dark');
        document.documentElement.classList.toggle('light', !isDark);
        document.getElementById('icon-moon').style.display = isDark ? 'none' : 'inline';
        document.getElementById('icon-sun').style.display  = isDark ? 'inline' : 'none';
        document.getElementById('mode-label').textContent  = isDark ? 'Light' : 'Dark';
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    /* โหลด preference จาก localStorage เมื่อเปิดหน้า */
    (function () {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            const moon  = document.getElementById('icon-moon');
            const sun   = document.getElementById('icon-sun');
            const label = document.getElementById('mode-label');
            if (moon)  moon.style.display  = 'none';
            if (sun)   sun.style.display   = 'inline';
            if (label) label.textContent   = 'Light';
        }
    })();
    </script>

</body>
</html>