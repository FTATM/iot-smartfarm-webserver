<?php
session_start();
include '../components/session.php';
checkLogin();
$Title = $_SESSION['branch_name'] . " Dashboard";
$subTitle = $_SESSION['branch_name'] . " intellegent system";
$classIconHeader = "material-symbols--schedule-outline";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>AI - Dashboard</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main class="flex-1 min-h-0 overflow-hidden w-full grid grid-cols-12 gap-4 p-4 bg-background">
        <!-- Left Column: Device Selection (AI Decision Control) -->
        <section class="col-span-3 bg-surface-container-lowest border border-surface-variant p-6 flex flex-col flex-1 overflow-hidden">
            <div class="h-[40%] flex flex-col w-full max-w-md bg-surface-container-lowest rounded-xl overflow-hidden transition-all duration-300">
                <!-- Widget Header Area (Internal) -->
                <div class="px-3 py-2 flex items-center justify-between border-b border-surface-container">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary-fixed p-2 rounded-lg">
                            <span class="material-symbols-outlined text-primary text-[20px]">terminal</span>
                        </div>
                        <div>
                            <h3 class="text-on-surface font-headline font-semibold text-lg leading-none">Schedule Script</h3>
                        </div>
                    </div>
                    <!-- Status Badge -->
                    <div id="statusBadge" class="flex items-center gap-2 px-3 py-1 rounded-full bg-stone-200/20 text-stone-500 transition-colors" id="statusBadge">
                        <span class="relative flex h-2 w-2">
                            <span id="statusBg" class="status-pulse absolute inline-flex h-full w-full rounded-full bg-stone-500 opacity-75"></span>
                        </span>
                        <span id="status" class="text-xs font-bold uppercase tracking-wider leading-none">Unknown</span>
                    </div>
                </div>
                <!-- Widget Content -->
                <div class="px-3 py-2 space-y-1 flex flex-col flex-1">
                    <!-- Data Grid -->
                    <div class="grid grid-cols-1 gap-3">
                        <!-- Started At -->
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant text-[1vw]">play_circle</span>
                                <span class="text-on-surface-variant text-[0.75vw] font-label">Started At</span>
                            </div>
                            <code id="started_at" class="font-mono text-on-surface bg-surface-container px-2 py-1 rounded text-[0.75vw]">
                                --:--:--
                            </code>
                        </div>
                        <!-- Updated At -->
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant text-[1vw]">sync</span>
                                <span class="text-on-surface-variant text-[0.75vw] font-label">Updated At</span>
                            </div>
                            <code id="updated_at" class="font-mono text-on-surface bg-surface-container px-2 py-1 rounded text-[0.75vw]">
                                --:--:--
                            </code>
                        </div>
                        <!-- Last At -->
                        <div class="mt-auto flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-on-surface-variant text-[1vw]">history</span>
                                <span class="text-on-surface-variant text-[0.75vw] font-label">Last At</span>
                            </div>
                            <code id="finished_at" class="font-mono font-semibold px-2 py-1 rounded text-[0.75vw]">
                                --:--:--
                            </code>
                        </div>
                    </div>
                    <!-- Visual Separator -->
                    <!-- <div class="h-px w-full bg-gradient-to-r from-transparent via-outline-variant to-transparent opacity-50"></div> -->
                </div>

            </div>
            
            <div class="h-[10%] mt-auto bg-white pt-3 border-t border-surface-variant">
                    <!-- Action Area -->
                    <div class="flex items-center gap-3 pt-2">
                        <button onclick="toggleScript()"
                            id="start-script-btn"
                            class="flex-1 bg-primary text-on-primary font-semibold py-1.5 px-2 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95 hover:bg-primary-container">
                            <span class="material-symbols-outlined text-[1vw]" id="actionIcon">play_circle</span>
                            <span id="actionText">Start Script</span>
                        </button>
                        <button onclick="resetScript()"
                            id="reset-script-btn"
                            class="bg-surface-container-high text-on-surface-variant font-semibold py-1.5 px-2 rounded-lg flex items-center justify-center gap-2 transition-all active:scale-95 hover:bg-surface-container-highest border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[1vw]">restart_alt</span>
                            <span>Reset History</span>
                        </button>
                    </div>
            </div>
        </section>
        <!-- Middle Column: Activity Log -->
        <section class="col-span-5 bg-surface-container-lowest border border-surface-variant flex flex-col overflow-hidden">
            <div class="p-panel-padding border-b border-surface-variant">
                <h2 class="font-headline-md text-headline-md text-on-surface">บันทึกการทำงานของ Schedule (Schedule Activity Log)</h2>
            </div>
            <div class="flex-1 overflow-y-auto h-full">
                <table id="table-Logs" class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-surface-container-lowest z-10">
                        <tr class="border-b border-surface-variant">
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase">เวลา</th>
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase">เหตุการณ์</th>
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase text-right">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                    </tbody>
                </table>
            </div>
        </section>
        <!-- Right Column: Execution Details -->
        <section class="col-span-4 bg-surface-container-lowest border border-surface-variant flex flex-col overflow-y-auto">
            <div class="p-panel-padding border-b border-surface-variant">
                <h2 class="font-headline-md text-headline-md text-on-surface">รายละเอียดการทำงานของ Schedule</h2>
            </div>

        </section>
    </main>

    <!-- </main> -->

    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/components/js-ai.html"; ?>
    <?php include "../scripts/js-schedule-management.html"; ?>
</body>

</html>