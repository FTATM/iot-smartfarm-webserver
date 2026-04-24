<?php
$Title = "Indoor Dashboard";
$subTitle = "Plant hydroponic intellegent system";
$classIconHeader = "fluent--door-arrow-left-20-regular";
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
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 flex flex-col justify-between gap-3 min-h-0">

                <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
                <div class="row-span-7 grid grid-cols-4 gap-3 2xl:gap-3 flex-1 min-h-0">

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div
                        class="box col-span-2 flex flex-col bg-white dark:bg-stone-900 rounded-2xl border border-stone-200 dark:border-stone-700 shadow-sm p-2 2xl:p-3 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">

                        <div class="rounded-xl px-[1.5vw] flex flex-col items-center justify-center">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Indoor Farm with IoT App.
                            </div>
                            <img src="images/indoor_top.png" alt="เครื่องมือ/อุปกรณ์"
                                class="rounded-lg object-cover object-center" style="width: 130em; height: 30vh;" />
                        </div>
                        <div class="flex flex-col flex-1 px-[1.5vw]">
                            <div
                                class="p-[0.25vw] rounded-lg border border-stone-200 font-bold mb-1 mt-1 text-[0.9vw] text-center w-full text-stone-700 dark:text-stone-300">
                                Indoor Farm with IoT App Architecture
                            </div>

                            <!-- เนื้อหาส่วนล่าง -->
                            <div class="flex justify-between flex-1 gap-1.5 ">

                                <div class="flex flex-col justify-between">
                                    <?php include("../components/sensors_left.php"); ?>
                                </div>
                                <div class=" rounded-lg p-0.5 flex items-center justify-center overflow-hidden min-h-0">
                                    <img src="images/Indoor.png" alt="เครื่องมือ/อุปกรณ์"
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

                <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
                <div class="row-span-1 grid grid-cols-6 gap-3 shrink-0" id="metrics-cards"></div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- ===== กรอบที่ 1: ข้อมูลการปลูก / โครงสร้าง / ผลผลิต ===== -->
                <div
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.75vw] shadow-sm flex flex-col overflow-hidden hover:ring-2 hover:ring-orange-400 transition-all duration-200">

                    <div class="flex items-center mb-[0.8vh]">
                        <div
                            class="p-[0.3vw] bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                            <span class="mdi--information-outline text-[0.9vw]"></span>
                        </div>
                        <h3 class="ml-2 text-[0.75vw] font-bold text-stone-700 dark:text-stone-200">ข้อมูลการปลูก /
                            โครงสร้าง / ผลผลิต</h3>
                    </div>

                    <!-- ระยะการปลูก -->
                    <div class="grid grid-cols-2 gap-2 mb-[0.8vh]">
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span
                                class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ระยะการปลูก</span>
                            <span id="planting-stage"
                                class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                        </div>
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ช่วงวัน
                            </span>
                            <span id="planting-days"
                                class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                        </div>
                    </div>

                    <!-- โครงสร้างพื้นที่ -->
                    <div class="grid grid-cols-3 gap-2 mb-[0.8vh]">
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">พื้นที่
                                (ก × ย )</span>
                            <span id="planting-area"
                                class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.3">—</span>
                        </div>
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">
                                ต้น/พื้นที่</span>
                            <span id="planting-density"
                                class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.3">—</span>
                        </div>
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ผลผลิต
                                (kg/ต้น)</span>
                            <span id="planting-yield"
                                class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.3">—</span>
                        </div>
                    </div>

                    <!--  -->
                    <div class="border-t border-stone-100 dark:border-stone-800 mb-[0.2vh]">
                        <p
                            style="font-size: 11px; color: #888; font-weight: 200; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 4px;">
                            ระยะการเติบโต</p>

                        <div style="position: relative;">

                            <!-- Track + Dots -->
                            <div style="display: flex; align-items: center; position: relative;">
                                <!-- Background track -->
                                <div
                                    style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: #ddd; border-radius: 2px; transform: translateY(-50%); z-index: 0;">
                                </div>
                                <!-- Fill -->
                                <div id="fill"
                                    style="position: absolute; top: 50%; left: 0; height: 4px; background: #639922; border-radius: 2px; transform: translateY(-50%); z-index: 1; transition: width 0.3s ease; width: 0%;">
                                </div>

                                <!-- Dots -->
                                <div
                                    style="display: flex; justify-content: space-between; width: 100%; position: relative; z-index: 2;">
                                    <div onclick="setActive(0)"
                                        style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <div class="dot" data-index="0"
                                            style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #639922; background: #639922; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <span class="dot-num"
                                                style="font-size: 8px; font-weight: 500; color: #fff;">1</span>
                                        </div>
                                    </div>
                                    <div onclick="setActive(1)"
                                        style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <div class="dot" data-index="1"
                                            style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <span class="dot-num"
                                                style="font-size: 8px; font-weight: 500; color: #888;">2</span>
                                        </div>
                                    </div>
                                    <div onclick="setActive(2)"
                                        style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <div class="dot" data-index="2"
                                            style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <span class="dot-num"
                                                style="font-size: 8px; font-weight: 500; color: #888;">3</span>
                                        </div>
                                    </div>
                                    <div onclick="setActive(3)"
                                        style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <div class="dot" data-index="3"
                                            style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <span class="dot-num"
                                                style="font-size: 8px; font-weight: 500; color: #888;">4</span>
                                        </div>
                                    </div>
                                    <div onclick="setActive(4)"
                                        style="display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        <div class="dot" data-index="4"
                                            style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <span class="dot-num"
                                                style="font-size: 8px; font-weight: 500; color: #888;">5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Labels -->
                            <div style="display: flex; justify-content: space-between;">
                                <div onclick="setActive(0)" style="width: 28px; text-align: center; cursor: pointer;">
                                    <span class="label" data-index="0"
                                        style="font-size: 8px; font-weight: 700; color: #27500A;">ต้นกล้า</span>
                                </div>
                                <div onclick="setActive(1)"
                                    style="width: 60px; text-align: center; cursor: pointer; margin-left: -16px;">
                                    <span class="label" data-index="1"
                                        style="font-size: 8px; font-weight: 300; color: #888;">เจริญเติบโต</span>
                                </div>
                                <div onclick="setActive(2)" style="width: 50px; text-align: center; cursor: pointer;">
                                    <span class="label" data-index="2"
                                        style="font-size: 8px; font-weight: 300; color: #888;">ออกดอก</span>
                                </div>
                                <div onclick="setActive(3)" style="width: 40px; text-align: center; cursor: pointer;">
                                    <span class="label" data-index="3"
                                        style="font-size: 8px; font-weight: 300; color: #888;">ติดผล</span>
                                </div>
                                <div onclick="setActive(4)"
                                    style="width: 50px; text-align: center; cursor: pointer; margin-right: -11px;">
                                    <span class="label" data-index="4"
                                        style="font-size: 8px; font-weight: 300; color: #888;">เก็บเกี่ยว</span>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>


                <!-- ===== กรอบที่ 2: สภาพแวดล้อม / ปุ๋ย / การดูแล ===== -->
                <div
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.75vw] shadow-sm flex flex-col overflow-hidden hover:ring-2 hover:ring-orange-400 transition-all duration-200">

                    <!-- header -->
                    <div class="flex flex-col mb-[0.8vh] gap-[0.4vh]">
                        <!-- บรรทัดล่าง: ปุ่ม -->
                        <div class="flex gap-[0.3vw] w-full">
                            <button id="tab-env2" onclick="switchCard2(0)"
                                class="flex-1 tab2-btn tab2-active py-[0.2vh] px-[0.4vw] rounded-lg text-[0.55vw] font-bold border transition-all duration-150">
                                สภาพแวดล้อม
                            </button>

                            <button id="tab-fert2" onclick="switchCard2(1)"
                                class="flex-1 tab2-btn tab2-inactive py-[0.2vh] px-[0.4vw] rounded-lg text-[0.55vw] font-bold border transition-all duration-150">
                                ปุ๋ย
                            </button>

                            <button id="tab-care2" onclick="switchCard2(2)"
                                class="flex-1 tab2-btn tab2-inactive py-[0.2vh] px-[0.4vw] rounded-lg text-[0.55vw] font-bold border transition-all duration-150">
                                การดูแล
                            </button>
                        </div>

                    </div>

                    <!-- Panel A: สภาพแวดล้อม -->
                    <div id="panel2-0" class="flex flex-col gap-2">
                        <div class="grid grid-cols-4 gap-2">
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ค่า
                                    EC</span>
                                <span id="ec"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ระดับ
                                    pH</span>
                                <span id="ph"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">Temp
                                    (°C)</span>
                                <span id="temperature"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">HR
                                    (%)</span>
                                <span id="humidity"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">แสง
                                    (LUX outdoor)</span>
                                <span id="light"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span
                                    class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">O₂</span>
                                <span id="o2"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span
                                    class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">CO₂</span>
                                <span id="co2"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                        </div>
                    </div>

                    <!-- Panel B: ปุ๋ย -->
                    <div id="panel2-1" class="hidden flex flex-col gap-1">
                        <div class="grid grid-cols-2 gap-1">
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span
                                    class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปุ๋ยเคมี
                                    (g/L)</span>
                                <span id="fert-chemical"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span
                                    class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปุ๋ยชีวภาพ
                                    (ml/L)</span>
                                <span id="fert-bio"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span
                                    class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปุ๋ยอินทรีย์
                                    (ml/L)</span>
                                <span id="fert-organic"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                            <div
                                class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                                <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">ปุ๋ย
                                    AB (ml/L)</span>
                                <span id="fert-ab"
                                    class="text-[0.65vw] font-black text-stone-800 dark:text-stone-100 mt-0.5">—</span>
                            </div>
                        </div>
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span
                                class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">วิธีผสมปุ๋ย</span>
                            <span id="fert-mix"
                                class="text-[0.60vw] font-bold text-stone-800 dark:text-stone-100 mt-0.5 leading-relaxed">—</span>
                        </div>
                    </div>

                    <!-- Panel C: การดูแล -->
                    <div id="panel2-2" class="hidden flex flex-col gap-2">
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">โรค /
                                ปัญหา</span>
                            <span id="care-disease"
                                class="text-[0.60vw] font-bold text-stone-800 dark:text-stone-100 mt-0.5 leading-relaxed">—</span>
                        </div>
                        <div
                            class="bg-stone-50 dark:bg-stone-800 rounded-xl py-[0.5vh] px-[0.5vw] flex flex-col justify-center">
                            <span
                                class="text-[0.50vw] text-stone-400 dark:text-stone-500 font-bold uppercase">การดูแล</span>
                            <span id="care-note"
                                class="text-[0.60vw] font-bold text-stone-800 dark:text-stone-100 mt-0.5 leading-relaxed">—</span>
                        </div>
                    </div>

                </div>

                <!-- Card 4: ต้นทุนทรัพยากรวันนี้ -->
                <div
                    class="bg-white dark:bg-stone-900 box border border-stone-200 dark:border-stone-700 rounded-2xl p-[0.5vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group overflow-hidden">
                    <div class="flex justify-between">
                        <div class="flex items-center mb-2">
                            <div
                                class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                                <span class="clarity--coin-bag-line"></span>
                            </div>
                            <h3 class="ml-2 text-[0.7vw] font-bold text-stone-700 dark:text-stone-200">
                                ต้นทุนทรัพยากรวันนี้
                            </h3>
                        </div>
                        <div onclick="calculateElectricityAndWater('card-3')"
                            class="flex items-center gap-2 cursor-pointer">
                            <span
                                class="material-symbols-outlined text-xl text-stone-500 dark:text-stone-400">refresh</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.5vw] flex flex-col justify-center">
                            <span
                                class="text-[0.55vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-none">
                                ค่าน้ำประปา
                            </span>
                            <div class="flex justify-center text-center leading-none">
                                <span id="card-3-water-usage"
                                    class="text-[0.45vw] font-black text-stone-800 dark:text-stone-100 leading-none">-</span>
                                <span class="text-[0.45vw] flex items-end text-slate-500 dark:text-stone-400">
                                    THB
                                </span>
                            </div>
                        </div>

                        <div class="bg-stone-50 dark:bg-stone-800 rounded-lg p-[0.5vw] flex flex-col justify-center">
                            <span
                                class="text-[0.55vw] text-stone-400 dark:text-stone-500 font-bold uppercase leading-none">
                                ค่าไฟฟ้า
                            </span>
                            <div class="flex justify-center text-center leading-none">
                                <span id="card-3-electricity-usage"
                                    class="text-[0.45vw] font-black text-stone-800 dark:text-stone-100 leading-none">-</span>
                                <span class="text-[0.45vw] flex items-end text-slate-500 dark:text-stone-400">
                                    THB
                                </span>
                            </div>
                        </div>
                    </div>
                    <button
                        class="w-full mt-[0.75vh] px-[0.75vw] py-[0.25vw] flex items-center justify-between text-[0.50vw] font-bold uppercase text-primary dark:hover:text-white border border-primary/40 hover:bg-orange-50 dark:hover:bg-orange-900 bg-white dark:bg-orange-900/20 rounded-xl transition-all shadow-sm">
                        <span>Export Report</span>
                        <span
                            class="material-icons-round text-sm hover:translate-y-0.5 transition-transform">download</span>
                    </button>
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
                        class="bg-orange-50 dark:bg-orange-900/20 p-[0.5vw] rounded-xl border border-orange-100 dark:border-orange-900/30">
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
    <?php include "../scripts/js-indoor-SweetBasil.html"; ?>
</body>

</html>