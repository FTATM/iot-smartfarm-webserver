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
                <h1 class="text-[#1d130c] text-[1.5vw] font-bold leading-none">Income Dashboard</h1>
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
                            <button class="bg-primary hover:bg-orange-600 text-white px-4 py-2 rounded-xl font-medium flex items-center gap-2 transition-all" onclick="showPopup('add-popup')">
                                <span class="material-icons-round text-[0.75vw]">add</span>
                                เพิ่มข้อมูล
                            </button>
                            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                                <button class="px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all text-slate-600 dark:text-slate-300">PDF</button>
                                <button class="px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-white dark:hover:bg-slate-700 hover:shadow-sm transition-all text-slate-600 dark:text-slate-300">Excel</button>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-72">
                            <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search records..." type="text" />
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">ID</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Branch ID</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Amount (฿)</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Category</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Description</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Date Range</th>
                                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">#0012</td>
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">BR-KORAT-01</td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400">12,500.00</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">Sales</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 truncate max-w-xs">Bulk chicken meat sale - Lot A42</td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">15/02 - 18/02</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"><span class="material-icons-round text-lg">edit</span></button>
                                                <button class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"><span class="material-icons-round text-lg">delete</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">#0011</td>
                                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">BR-KORAT-01</td>
                                        <td class="px-6 py-4 text-sm font-bold text-emerald-600 dark:text-emerald-400">8,200.00</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">Eggs</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 truncate max-w-xs">Egg collection - Week 7</td>
                                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">10/02 - 17/02</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"><span class="material-icons-round text-lg">edit</span></button>
                                                <button class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"><span class="material-icons-round text-lg">delete</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">

                <!-- Card 1: รายรับวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] fluent--food-20-regular"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4">รายรับวันนี้</h3>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <span class="text-[2vw] font-bold font-display text-slate-900 dark:text-white">45,800</span>
                        <span class="text-[0.5vw] text-slate-500 font-medium">THB</span>
                    </div>
                    <p class="text-[0.75vw] text-emerald-500 mt-2 font-medium flex items-center gap-1">
                        <span class="material-icons-round text-sm">trending_up</span> +12% from yesterday
                    </p>
                </div>

                <!-- Card 2: รายจ่ายวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] mage--light-bulb"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4">รายจ่ายวันนี้</h3>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <span class="text-[2vw] font-bold font-display text-slate-900 dark:text-white">12,400</span>
                        <span class="text-[0.5vw] text-slate-500 font-medium">THB</span>
                    </div>
                    <p class="text-[0.75vw] text-slate-400 mt-2 font-medium">Normal spending range</p>
                </div>

                <!-- Card 3:  -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-[2vw] clarity--coin-bag-line"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4"></h3>
                    </div>
                    <!-- <div class="grid grid-cols-2 gap-1.5 mb-1.5 ">
                        <div class="bg-stone-50 rounded-lg p-1 flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 font-bold uppercase leading-tight">ค่าน้ำประปา</span>
                            <span class="text-[0.75vw] text-center font-black text-stone-800 leading-tight" id="water-usage">-</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1 flex flex-col justify-center">
                            <span class="text-[0.75vw] text-stone-400 font-bold uppercase leading-tight">ค่าไฟฟ้า</span>
                            <span class="text-[0.75vw] text-center font-black text-stone-800 leading-tight" id="electricity-usage">-</span>
                        </div>
                    </div> -->
                </div>

                <!-- Card 4: ต้นทุนรวมทั้งหมด -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-2 mb-2 2xl:p-2">
                        <span class="material-symbols-outlined text-primary text-[1vw] emojione-monotone--money-bag"></span>
                        <h3 class="text-[1vw] font-bold text-stone-700 2xl:pl-4"></h3>
                    </div>

                    <div class="space-y-1.5 ">
                        <!-- Total -->
                        <div class="pt-1 mt-0.5 border-t border-slate-200 flex justify-between items-center">
                            <span class="font-bold text-slate-800 uppercase text-[0.75vw] tracking-wider 2xl:pr-2">Total</span>
                            <span class="font-bold text-[1vw] text-primary 2xl:pr-4 pr-2" id="expense-total">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="add-popup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-[2rem] shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2 class="text-2xl font-bold font-display text-slate-900 dark:text-white">เพิ่มรายการรายได้ใหม่</h2>
                <button onclick="hidePopup('add-popup')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <span class="material-icons-round text-2xl">close</span>
                </button>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 font-display">สาขา (Branch)</label>
                        <div class="relative">
                            <select id="select-branch" class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                                <option selected value="0">-- เลือกฟาร์ม --</option>
                            </select>
                            <span class="material-icons-round absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 font-display">จำนวนเงิน (Amount)</label>
                        <div class="relative">
                            <input id="amount" class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="0.00"  type="text" />
                            <!-- <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">THB</span> -->
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 font-display">หมวดหมู่ (Category)</label>
                        <div class="relative">
                            <select id="select-category" class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                            </select>
                            <span class="material-icons-round absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 font-display">วันที่ทำรายการ (Income Date)</label>
                        <div class="relative">
                            <input id="start-date" class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all " type="date" />
                            <!-- <span class="material-icons-round absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">calendar_today</span> -->
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 font-display">รายละเอียด (Description)</label>
                    <textarea id="description" class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none" placeholder="ระบุรายละเอียดเพิ่มเติม..." rows="3"></textarea>
                </div>
            </div>
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <button onclick="hidePopup('add-popup')" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all font-display">
                    ยกเลิก (Cancel)
                </button>
                <button onclick="saveIncome('add-popup')" class="px-8 py-2.5 rounded-xl text-sm font-semibold text-white bg-primary hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition-all font-display">
                    บันทึกข้อมูล (Save Data)
                </button>
            </div>
        </div>
    </div>


    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>

    <?php include "../scripts/js-income.html"; ?>
</body>

</html>