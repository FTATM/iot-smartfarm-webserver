<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');
?>

<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>รายรับ - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col">

    <!-- Header -->
    <header class="flex items-center justify-between p-4 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-3">
            <?php include 'navbar.php'; ?>
            <div class="size-9 w-[5rem] h-[5rem] bg-[#FF8021] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="emojione-monotone--chicken text-2xl text-white"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-[1.5vw] font-bold leading-none">Income Management</h1>
                <p class="text-[0.75vw] text-stone-500 font-medium uppercase tracking-wider mt-0.5">Chicken Farm Intelligence Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-stone-100 p-1 px-3 rounded-xl border border-stone-200">
                <div class="flex items-center gap-2">
                    <span class="text-[1vw] font-bold text-primary leading-none">อายุไก่ปัจจุบัน:</span>
                    <span class="text-[1vw] font-bold text-primary leading-none" id="day-age">--</span>
                    <span class="text-[1vw] font-bold text-primary leading-none">วัน</span>
                    <div class="w-px h-2.5 bg-stone-300"></div>
                    <span class="text-[1vw] text-stone-600 font-bold leading-none" id="start-date"> -- --- ---- </span>
                </div>
            </div>
            <div class="flex flex-col items-end border-l border-stone-200 pl-4">
                <span class="text-[1vw] font-bold text-stone-400 uppercase tracking-widest leading-none mb-0.5 2xl:mb-4">อัปเดตล่าสุด</span>
                <span class="text-[1vw] font-bold text-stone-800 text-center" id="last-update"><?php echo $currentTime; ?></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-3 gap-3 overflow-hidden min-h-0">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 gap-3 min-h-0">
                <div class="flex-grow space-y-6">
                    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex gap-2">
                            <button onclick="showPopup('popup-add')" class="bg-primary hover:bg-orange-600 text-white text-[0.75vw] px-4 py-2 rounded-xl font-medium flex items-center gap-2 transition-all">
                                <span class="material-icons-round text-[0.75vw]">add</span>
                                เพิ่มข้อมูล
                            </button>
                            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                                <button class="px-4 py-1.5 rounded-lg text-[0.75vw] font-medium hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all text-slate-600 dark:text-slate-300">PDF</button>
                                <button class="px-4 py-1.5 rounded-lg text-[0.75vw] font-medium hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all text-slate-600 dark:text-slate-300">Excel</button>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-[25vw]">
                            <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[0.75vw]">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-[0.75vw] focus:ring-2 focus:ring-primary/20" placeholder="Search records..." type="text" />
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="table-view" class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase">Branch ID</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase">Amount (฿)</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase">Category</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase">Description</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase">Date Range</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-slate-500 uppercase text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                            <div class="text-[0.75vw] text-slate-500 dark:text-slate-400 font-medium">
                                Showing <span id="prefix-pagination" class="text-slate-900 dark:text-white font-bold">1-10</span> of <span id="suffix-pagination" class="text-slate-900 dark:text-white font-bold">100</span> entries
                            </div>
                            <div class="flex items-center gap-1">
                                <button class="px-3 py-1.5 text-[0.75vw] font-medium text-slate-500 hover:text-primary transition-colors disabled:opacity-50" disabled="">
                                    Previous
                                </button>
                                <div class="flex items-center gap-1">
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.75vw] font-bold bg-primary text-white shadow-sm transition-all">1</button>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.75vw] font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">2</button>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.75vw] font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">3</button>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.75vw] font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">4</button>
                                    <span class="px-2 text-slate-400">...</span>
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.75vw] font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">10</button>
                                </div>
                                <button class="px-3 py-1.5 text-[0.75vw] font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: รายรับวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg">
                                <span class="material-icons-round">payments</span>
                            </div>
                            <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4">รายรับรวมเดือนนี้</h3>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span id="card-1-total" class="text-[1.5vw] font-bold font-display text-slate-900 dark:text-white">0</span>
                        <span class="text-[0.75vw] text-slate-500 font-medium">THB</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-2 p-1.5 px-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg w-fit">
                        <span class="text-[0.75vw] font-bold text-emerald-600 dark:text-emerald-400 flex items-center">
                            <span class="material-icons-round text-[0.75vw] mr-0.5">trending_up</span>
                            <span id="card-1-percent" class="text-[0.75vw] text-slate-500 font-medium">0%</span>
                        </span>
                        <span class="text-[0.75vw] text-slate-500 dark:text-slate-400 font-medium uppercase tracking-tight">จากเดือนที่แล้ว</span>
                    </div>
                </div>

                <!-- Card 2: รายจ่ายวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-3">
                        <div class="p-[0.5vw] bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-lg">
                            <span class="material-icons-round">shopping_cart</span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4">รายจ่ายเดือนนี้</h3>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <span id="card-2-total" class="text-[1.5vw] font-bold font-display text-slate-900 dark:text-white">0</span>
                        <span class="text-[0.75vw] text-slate-500 font-medium">THB</span>
                    </div>
                    <div class="space-y-[1vh]">
                        <div class="flex justify-between items-center text-[0.75vw] font-bold uppercase text-slate-500">
                            <span>การใช้จ่ายงบประมาณ (Budget)</span>
                            <span id="card-2-percent" class="text-slate-700 dark:text-slate-300">0%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div id="card-2-bar-process" class="bg-orange-500 h-full rounded-full shadow-[0_0_8px_rgba(249,115,22,0.3)]"></div>
                        </div>
                        <div class="text-right">
                            <span class="text-[0.75vw] text-slate-400">งบประมาณเดือนที่แล้ว: </span>
                            <span id="card-2-lastMonth" class="text-[0.75vw] text-slate-400">0</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div id="card3" class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-lg">
                            <span class="material-icons-round">account_balance_wallet</span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4"> กำไรสุทธิ (Net Profit) </h3>
                    </div>

                    <div class="flex items-baseline gap-2 relative z-10">
                        <span id="card3-total" class="text-3xl font-bold font-display text-emerald-600 dark:text-emerald-400">
                            0
                        </span>
                        <span class="text-[0.75vw] text-slate-500 font-medium">THB</span>
                    </div>

                    <!-- Weekly bars -->
                    <div id="card3-bars" class="mt-4 h-14 w-full flex items-end justify-between gap-1.5"></div>

                    <div class="flex justify-between text-[1vw] text-slate-400 mt-1 uppercase font-bold">
                        <span>W1</span>
                        <span>W2</span>
                        <span>W3</span>
                        <span>W4</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div id="card4" class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">

                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg">
                            <span class="material-icons-round">star</span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4">
                            หมวดหมู่รายรับสูงสุด
                        </h3>
                    </div>

                    <div id="card4-list" class="space-y-4"></div>

                    <div class="mt-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div id="card4-progress" class="flex h-2.5 w-full rounded-full overflow-hidden bg-slate-100 dark:bg-slate-800"></div>

                        <div class="flex justify-between mt-2 text-[1vw] text-slate-400 font-medium">
                            <span>รายได้จากสินค้า 3 อันดับแรก</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>



    <?php include "../components/footer.php"; ?>
    <?php include "../components/popup.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-income.html"; ?>
</body>

</html>