<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

?>

<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>

<head>
    <title>สถานะบ่อเลี้ยงกุ้ง - Dashboard</title>
</head>

<body>
    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
            <?php include 'navbar.php'; ?>
            <div class="size-9 bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="emojione-monotone--shrimp text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Shrimp Dashboard</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Shrimp Farm Intelligence Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 bg-stone-100 p-1.5 px-4 rounded-xl border border-stone-200">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-primary leading-none">อายุไก่ปัจจุบัน:</span>
                    <span class="text-sm font-bold text-primary leading-none" id="day-age">--</span>
                    <span class="text-sm font-bold text-primary leading-none">วัน</span>
                    <div class="w-px h-3 bg-stone-300"></div>
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
                    <div class="col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex-row items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 ">
                        <!-- <div class="flex-row "> -->

                        <!-- พื้นที่กลาง (รูปบน) -->
                        <div class="col-span-8 bg-white rounded-2xl p-2 flex-column items-center justify-center transition-all min-h-[300px]">
                            <div class="p-2 bg-stone-100 rounded-xl font-bold mb-2">Smart Vertical Raised Shrimp Farming System(RAS) with IoT App</div>
                            <img src="images/shrimp_main.jpg" alt="เครื่องมือ/อุปกรณ์" class="w-full object-cover rounded-xl">
                            <!-- <img src="images/shrimp_main.jpg" alt="เครื่องมือ/อุปกรณ์" class=""> -->
                        </div>

                        <div class="grid grid-cols-12 gap-4 2xl:gap-1 2xl:min-h-[300px]">
                            <?php include("../components/sensors_left.php"); ?>

                            <div class="col-span-8 bg-white rounded-2xl p-2 flex items-center justify-center transition-all">
                                <img src="images/shrimp.jpeg" alt="เครื่องมือ/อุปกรณ์" class="w-full object-cover rounded-xl">
                            </div>
                            <?php include("../components/sensors_right.php"); ?>

                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 2xl:p-2 shrink-0">
                                <div>
                                    <h2 id="title-graph-sensor" class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                        กำลังโหลดข้อมูล...
                                    </h2>
                                    <p id="title-graph-sensor-sub" class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        Loading data...
                                    </p>
                                </div>
                                <!-- <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200">
                                    <button id="btnDoDay" class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                    <button id="btnDoMonth" class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
                                </div> -->
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
                            <div class="flex justify-between items-center mb-2 2xl:p-2 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-primary rounded-full"></span>
                                        แนวโน้มราคาตลาด
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price Trend</p>
                                </div>
                                <div id="types-MarketChart" class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-md border border-stone-200 px-2 py-0.5 text-[10px] ">
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
                <div class="grid grid-cols-6 gap-4 shrink-0" id="metrics-cards">

                </div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 grid grid-rows-4 gap-3">
                <!-- Card 1: การให้อาหารวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-sm">restaurant</span>
                        <h3 class="text-[10px] font-bold text-stone-700">การให้อาหารวันนี้</h3>
                    </div>
                    <div class="2xl:px-2">
                        <div class="grid grid-cols-2 gap-2 2xl:gap-4" id="feeding-info">
                            <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center 2xl:p-2 h-full">
                                <span class="text-[9px] text-stone-400 font-bold uppercase">จำนวนมื้อ</span>
                                <span class="text-[10px] font-black text-stone-800" id="meals-count">--</span>
                            </div>
                            <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center 2xl:px-2 h-full">
                                <span class="text-[9px] text-stone-400 font-bold uppercase">ปริมาณ(กรัม)</span>
                                <span class="text-[10px] font-black text-stone-800" id="volume-meal">-</span>
                            </div>
                            <div class="col-span-2 bg-primary/5 rounded-lg p-1.5 2xl:py-8 border border-primary/10 flex justify-between items-center">
                                <span class="text-[9px] text-primary font-bold uppercase">ปริมาณรวมที่ต้องกิน(กรัม)</span>
                                <span class="text-[10px] font-black text-primary" id="total-meal-per-day">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-sm clarity--coin-bag-line"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนทรัพยากรทั้งหมด</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2 2xl:gap-4 2xl:px-2">
                        <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center">
                            <span class="text-[9px] text-stone-400 font-bold uppercase">ค่าน้ำประปา</span>
                            <span class="text-[13px] text-center font-black text-stone-800" id="water-usage">-</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5 flex flex-col justify-center">
                            <span class="text-[9px] text-stone-400 font-bold uppercase">ค่าไฟฟ้า</span>
                            <span class="text-[13px] text-center font-black text-stone-800" id="electricity-usage">-</span>
                        </div>
                    </div>
                    <div class="space-y-2 2xl:px-8 2xl:space-y-4">
                        <!-- Hardware -->
                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-green-50 rounded-md border border-green-100">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-green-600 text-[8px]">check_circle</span>
                                <span class="text-[7px] font-bold text-green-700 uppercase 2xl:px-4">Hardware</span>
                            </div>
                            <span class="text-[7px] font-bold text-green-700" id="expense-hardware">-</span>
                        </div>

                        <!-- Infrastructure -->
                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-yellow-50 rounded-md border border-yellow-100">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-yellow-600 text-[8px]">warning</span>
                                <span class="text-[7px] font-bold text-yellow-700 uppercase 2xl:px-4">Infrastructure</span>
                            </div>
                            <span class="text-[7px] font-bold text-yellow-700" id="expense-infrastructure">-</span>
                        </div>

                        <!-- Miscellaneous -->
                        <div class="flex items-center justify-between p-1 2xl:px-4 bg-red-50 rounded-md border border-red-100">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-red-600 text-[8px]">error</span>
                                <span class="text-[7px] font-bold text-red-700 uppercase 2xl:px-4">Miscellaneous</span>
                            </div>
                            <span class="text-[7px] font-bold text-red-700" id="expense-miscellaneous">-</span>
                        </div>

                        <!-- Total -->
                        <div class="pt-1.5 2xl:px-8 mt-0.75 border-t border-slate-200 flex justify-between items-center">
                            <span class="font-bold text-slate-800 uppercase text-[6px] tracking-wider">Total</span>
                            <span class="font-bold text-[11px] text-primary" id="expense-total">-</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: คุณภาพน้ำที่เหมาะสม -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-sm">waves</span>
                        <h3 class="text-[10px] font-bold text-stone-700">คุณภาพน้ำที่เหมาะสม</h3>
                    </div>
                    <div>
                        <div class="2xl:px-2">
                            <table class="w-full">
                                <tbody class="text-[9px] 2xl:text-sm divide-y divide-stone-50">
                                    <tr class="2xl:h-16">
                                        <td class="py-0.5 2xl:py-3 text-stone-500 font-medium">DO</td>
                                        <td class="py-0.5 2xl:py-3 text-right font-bold text-stone-700">3.0-7.0 mg/L</td>
                                    </tr>
                                    <tr class="2xl:h-16">
                                        <td class="py-0.5 2xl:py-3 text-stone-500 font-medium">pH</td>
                                        <td class="py-0.5 2xl:py-3 text-right font-bold text-stone-700">7.5 - 8.5</td>
                                    </tr>
                                    <tr class="2xl:h-16">
                                        <td class="py-0.5 2xl:py-3 text-stone-500 font-medium">EC</td>
                                        <td class="py-0.5 2xl:py-3 text-right font-bold text-stone-700">23K-45K μS/cm</td>
                                    </tr>
                                    <tr class="2xl:h-16">
                                        <td class="py-0.5 2xl:py-3 text-stone-500 font-medium">Temp</td>
                                        <td class="py-0.5 2xl:py-3 text-right font-bold text-stone-700">28-32 °C</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 4: การปรับอาหาร -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-sm">rule</span>
                        <h3 class="text-[10px] font-bold text-stone-700">การปรับอาหาร</h3>
                    </div>
                    <div class="flex flex-col gap-1 2xl:px-2 2xl:space-y-4">
                        <div class="flex items-center justify-between px-2 py-1 bg-success/5 border border-success/10 rounded-lg">
                            <span class="text-[9px] font-bold text-success uppercase">หมดเกลี้ยง</span>
                            <span class="text-[9px] font-normal text-stone-700">+5 ถึง +10%</span>
                        </div>
                        <div class="flex items-center justify-between px-2 py-1 bg-warning/5 border border-warning/10 rounded-lg">
                            <span class="text-[9px] font-bold text-warning uppercase">เหลือเล็กน้อย</span>
                            <span class="text-[9px] font-normal text-stone-700">คงที่ / -5%</span>
                        </div>
                        <div class="flex items-center justify-between px-2 py-1 bg-danger/5 border border-danger/10 rounded-lg">
                            <span class="text-[9px] font-bold text-danger uppercase">เหลือเยอะ</span>
                            <span class="text-[9px] font-normal text-stone-700">งด / -50%</span>
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