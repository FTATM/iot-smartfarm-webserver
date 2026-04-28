<?php
session_start();
$Title = "Expense Managements";
$subTitle = "Chicken Farm Intelligence Dashboard";
$classIconHeader = "hugeicons--bitcoin-up-01";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>รายจ่าย - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col">

    <!-- Main Content -->
    <main class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 dark:bg-stone-950">
        <?php include "../components/header.php"; ?>

        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== LEFT SECTION (10 columns) ========== -->
            <div class="col-span-10 gap-3 min-h-0">
                <div class="flex-grow space-y-6">
                    <div
                        class="bg-white box dark:bg-stone-900 p-4 rounded-2xl shadow-sm border border-stone-200 dark:border-stone-800 flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex gap-2">
                            <button onclick="showPopup('popup-add')"
                                class="bg-primary hover:bg-orange-600 text-white text-[0.75vw] px-4 py-2 rounded-xl font-medium flex items-center gap-2 transition-all">
                                <span class="material-icons-round text-[0.75vw]">add</span>
                                เพิ่มข้อมูล
                            </button>
                            <div class="flex bg-stone-100 dark:bg-stone-800 p-1 rounded-xl">
                                <button
                                    class="px-4 py-1.5 rounded-lg text-[0.75vw] font-medium hover:bg-white dark:hover:bg-stone-700 hover:shadow-sm transition-all text-stone-600 dark:text-stone-300">PDF</button>
                                <button
                                    class="px-4 py-1.5 rounded-lg text-[0.75vw] font-medium hover:bg-white dark:hover:bg-stone-700 hover:shadow-sm transition-all text-stone-600 dark:text-stone-300">Excel</button>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-[25vw]">
                            <span
                                class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-stone-400 text-[0.75vw]">
                                search
                            </span>

                            <input
                                class="w-full pl-10 pr-4 py-2 bg-stone-50 dark:bg-stone-700 border-none rounded-xl text-[0.75vw] focus:ring-2 focus:ring-primary/20"
                                placeholder="Search records..." type="text" />
                        </div>
                    </div>
                    <div
                        class="bg-white box dark:bg-stone-900 rounded-2xl shadow-sm border border-stone-200 dark:border-stone-800 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="table-view" class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="bg-stone-50 dark:bg-stone-800/50 border-b border-stone-200 dark:border-stone-800">
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase">
                                            Branch ID
                                        </th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase">
                                            Amount
                                            (฿)</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase">
                                            Category
                                        </th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase">
                                            Description</th>
                                        <th class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase">Date
                                            Range</th>
                                        <th
                                            class="px-6 py-4 text-[0.75vw] font-bold text-stone-500 uppercase text-center">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="px-6 py-4 flex items-center justify-between border-t border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900">
                            <div class="text-[0.75vw] text-stone-500 dark:text-stone-400 font-medium">
                                Showing <span id="prefix-pagination"
                                    class="text-stone-900 dark:text-white font-bold">1-10</span> of <span
                                    id="suffix-pagination" class="text-stone-900 dark:text-white font-bold">100</span>
                                entries
                            </div>
                            <div class="flex items-center gap-1  dark:text-white">
                                <button
                                    class="px-3 py-1.5 text-[0.75vw] font-medium text-stone-500 hover:text-primary transition-colors disabled:opacity-50"
                                    disabled="">
                                    Previous
                                </button>
                                <div class="flex items-center gap-1">
                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.5vw] font-bold bg-primary text-white shadow-sm transition-all">1</button>
                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.5vw] font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">2</button>
                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.5vw] font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">3</button>
                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.5vw] font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">4</button>
                                    <span class="px-2 text-stone-400">...</span>
                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-[0.5vw] font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-100 dark:hover:bg-stone-800 transition-all">10</button>
                                </div>
                                <button
                                    class="px-3 py-1.5 text-[0.5vw] font-medium text-stone-600 dark:text-stone-400 hover:text-primary transition-colors">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 flex flex-col gap-2 min-h-0">

                <!-- Card 1: รายรับวันนี้ -->
                <div
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl p-[1vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-orange-50 dark:bg-orange-900/20 text-primary rounded-lg flex items-center justify-center">
                                <span class="healthicons--low-income-level-outline"></span>
                            </div>
                            <h3 class="text-[1vw] font-bold text-stone-700 dark:text-stone-200">รายรับรวมเดือนนี้</h3>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span id="card-1-total"
                            class="text-[1.5vw] font-bold font-display text-stone-900 dark:text-white">0</span>
                        <span class="text-[0.75vw] text-stone-500 font-medium">THB</span>
                    </div>
                    <div
                        class="flex items-center gap-1.5 mt-2 p-1.5 px-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg w-fit">
                        <span class="text-[0.75vw] font-bold text-emerald-600 dark:text-emerald-400 flex items-center">
                            <span class="material-icons-round text-[0.75vw] mr-0.5">trending_up</span>
                            <span id="card-1-percent" class="text-[0.75vw] text-stone-500 font-medium">0%</span>
                        </span>
                        <span
                            class="text-[0.75vw] text-stone-500 dark:text-stone-400 font-medium uppercase tracking-tight">จากเดือนที่แล้ว</span>
                    </div>
                </div>

                <!-- Card 2: รายจ่ายวันนี้ -->
                <div
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl p-[1vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-[0.5vw] bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-lg flex items-center justify-center">
                            <span class="healthicons--low-expense-level-outline"></span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700  dark:text-stone-200">รายจ่ายเดือนนี้</h3>
                    </div>

                    <div class="flex items-baseline gap-2">
                        <span id="card-2-total"
                            class="text-[1.5vw] font-bold font-display text-stone-900 dark:text-white">0</span>
                        <span class="text-[0.75vw] text-stone-500 font-medium">THB</span>
                    </div>
                    <div class="space-y-[1vh]">
                        <div class="flex justify-between items-center text-[0.75vw] font-bold uppercase text-stone-500">
                            <span>การใช้จ่ายงบประมาณ (Budget)</span>
                            <span id="card-2-percent" class="text-stone-700 dark:text-stone-300">0%</span>
                        </div>
                        <div class="w-full bg-stone-100 dark:bg-stone-800 h-2 rounded-full overflow-hidden">
                            <div id="card-2-bar-process"
                                class="bg-orange-500 h-full ounded-full shadow-[0_0_8px_rgba(249,115,22,0.3)]">
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[0.75vw] text-stone-400">งบประมาณเดือนที่แล้ว: </span>
                            <span id="card-2-lastMonth" class="text-[0.75vw] text-stone-400">0</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div id="card3"
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl p-[1vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-lg flex items-center justify-center">
                            <span class="emojione-monotone--money-bag"></span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700  dark:text-stone-200"> กำไรสุทธิ (Net Profit)
                        </h3>
                    </div>

                    <div class="flex items-baseline gap-2 relative z-10">
                        <span id="card3-total"
                            class="text-[1.5vw] font-bold font-display text-emerald-600 dark:text-emerald-400">
                            0
                        </span>
                        <span class="text-[0.75vw] text-stone-500 font-medium">THB</span>
                    </div>

                    <!-- Weekly bars -->
                    <div id="card3-bars" class="mt-2 h-10 w-full flex items-end justify-between gap-1.5"></div>

                    <div class="flex justify-between text-[0.75vw] text-stone-400 mb-[0.1vw] uppercase font-bold">
                        <span>W1</span>
                        <span>W2</span>
                        <span>W3</span>
                        <span>W4</span>
                    </div>
                </div>

                <!-- Card 4 -->
                <div id="card4"
                    class="bg-white box dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-2xl p-[1vw] shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0 overflow-hidden">

                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-light--category-outline"></span>
                        </div>
                        <h3 class="text-[1vw] font-bold text-stone-700  dark:text-stone-200">
                            หมวดหมู่รายจ่ายสูงสุด
                        </h3>
                    </div>

                    <div id="card4-list" class="space-y-4"></div>

                    <div class="mt-5 pt-4 border-t border-stone-100 dark:border-stone-800">
                        <div id="card4-progress"
                            class="flex h-2.5 w-full rounded-full overflow-hidden bg-stone-100 dark:bg-stone-800">
                        </div>

                        <div class="flex justify-end mt-2 text-[0.7vw] text-stone-400 font-medium">
                            <span>รายได้จากสินค้า 3 อันดับแรก</span>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <?php include "../components/footer.php"; ?>
    <?php include "../components/popup.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-expense.html"; ?>
</body>

</html>