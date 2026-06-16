<?php
session_start();
include '../components/session.php';
checkLogin();
$Title = "Container Dashboard";
$subTitle = "Plant hydroponic indoor intellegent system";
$classIconHeader = "tdesign--ai";
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
            <div class="h-[5%] mb-auto flex justify-between items-center">
                <h2 class="font-headline-md text-headline-md text-on-surface">AI ตัดสินใจ</h2>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input checked="" class="sr-only peer" type="checkbox" value="" />
                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
                </label>
            </div>
            <div class="h-[80%]">
                <span class="font-label-md text-label-md text-outline uppercase tracking-wider block mb-4">อุปกรณ์ที่เลือกได้</span>
                <div id="device_list" class="space-y-1 flex flex-col overflow-y-auto h-[100%]">
                </div>
            </div>
            <div class="h-[10%] mt-auto bg-white pt-6 border-t border-surface-variant">
                <p class="font-body-sm text-body-sm text-secondary thai-leading">
                    รายการอุปกรณ์ที่สามารถเลือกให้ AI นำไปวิเคราะห์เพื่อประกอบการตัดสินใจโดยอัตโนมัติ
                </p>
            </div>
        </section>
        <!-- Middle Column: Activity Log -->
        <section class="col-span-5 bg-surface-container-lowest border border-surface-variant flex flex-col overflow-hidden">
            <div class="p-panel-padding border-b border-surface-variant">
                <h2 class="font-headline-md text-headline-md text-on-surface">บันทึกการทำงานของ AI (AI Activity Log)</h2>
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
                <h2 class="font-headline-md text-headline-md text-on-surface">รายละเอียดการทำงานของ AI</h2>
            </div>
            <div id="detail-log" class="p-panel-padding space-y-6">
                <!-- Detail Grid -->
                <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">วันเวลา</span>
                        <span id="datetime" class="font-mono-data text-mono-data text-on-secondary-fixed-variant">xxxx/xx/xx --:--:--</span>
                    </div>
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">สถานะ</span>
                        <span id="status" class="font-body-sm text-body-sm text-tertiary font-semibold">-</span>
                    </div>
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">คำอธิบาย</span>
                        <p id="description" class="font-body-sm text-body-sm text-on-surface">-</p>
                    </div>
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">โมเดล AI</span>
                        <span id="model" class="font-body-sm text-body-sm text-tertiary font-semibold">-</span>
                    </div>
                </div>
                <div>
                    <span class="font-label-md text-label-md text-outline block mb-1 uppercase">= คำถาม =</span>
                    <div
                        class="p-3 bg-surface-container-low rounded border border-surface-variant">
                        <p id="question" class="font-body-sm text-body-sm text-on-surface thai-leading">-</p>
                    </div>
                </div>
                <div>
                    <span class="font-label-md text-label-md text-outline block mb-2 uppercase">= ข้อความสั่งการให้กรองข้อมูล =</span>
                    <div class="relative">
                        <pre id="prompt"
                            class="mt-2 p-2 border border-surface-variant font-mono-data text-[0.65vw] text-secondary whitespace-pre-wrap break-words overflow-hidden transition-all duration-300"
                            style="max-height: 8em;">-
                        </pre>
                        <div id="fade"
                            class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white to-transparent pointer-events-none">
                        </div>
                    </div>

                    <button
                        id="toggle-prompt"
                        type="button"
                        class="mt-1 text-xs text-primary hover:underline hidden">
                        ดูเพิ่มเติม
                    </button>
                </div>
                <div class="space-y-2">
                    <span class="font-label-md text-label-md text-outline uppercase tracking-wider block">= ข้อความตอบกลับ =</span>
                    <div class="p-3 bg-surface-container-lowest border border-surface-variant rounded space-y-3">
                        <span class="font-label-md text-[0.5vw] text-outline uppercase">เหตุผล</span>
                        <div id="response-reason" class="font-body-sm text-body-sm text-on-surface">-</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3 bg-surface-container rounded border border-surface-variant flex flex-col">
                                <span class="font-label-md text-[0.5vw] text-primary text-outline uppercase">เครื่องมือที่จำเป็น</span>
                                <div id="response-required-tools" class="flex flex-wrap gap-2">
                                    <span class="text-outline">-</span>
                                </div>
                            </div>
                            <div class="p-3 bg-surface-container rounded border border-surface-variant flex flex-col">
                                <span class="font-label-md text-[0.5vw] text-primary text-outline uppercase">อุปกรณ์ที่จำเป็น</span>
                                <div id="response-required-devices" class="flex flex-wrap gap-2">
                                    <span class="text-outline">-</span>
                                </div>
                            </div>
                        </div>
                        <details class="group">
                            <summary class="list-none cursor-pointer text-[0.75vw] text-secondary flex items-center gap-1 font-label-md">
                                <span class="material-symbols-outlined text-[0.75vw] transition-transform group-open:rotate-180">expand_more</span>
                                รายละเอียด
                            </summary>
                            <pre id="response-detail" class="mt-2 p-2 border border-surface-variant font-mono-data text-[0.65vw] text-secondary whitespace-pre-wrap break-words">
                                -
                            </pre>
                        </details>
                    </div>
                </div>
                <div>
                    <span class="font-label-md text-label-md text-outline block mb-2 uppercase">= ข้อมูลที่ AI ร้องขอ =</span>
                    <div class="relative">
                        <pre id="tool-data"
                            class="mt-2 p-2 border border-surface-variant font-mono-data text-[0.65vw] text-secondary whitespace-pre-wrap break-words overflow-hidden transition-all duration-300"
                            style="max-height: 8em;">-</pre>
                        <div id="fade"
                            class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white to-transparent pointer-events-none">
                        </div>
                    </div>

                    <button
                        id="toggle-tool-data"
                        type="button"
                        class="mt-1 text-xs text-primary hover:underline hidden">
                        ดูเพิ่มเติม
                    </button>
                </div>
                <div>
                    <span class="font-label-md text-label-md text-outline block mb-2 uppercase">= ข้อความสั่งการให้ตัดสินใจ =</span>
                    <div class="relative">
                        <pre id="decision-prompt"
                            class="mt-2 p-2 border border-surface-variant font-mono-data text-[0.65vw] text-secondary whitespace-pre-wrap break-words overflow-hidden transition-all duration-300"
                            style="max-height: 8em;">-</pre>
                        <div id="fade"
                            class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white to-transparent pointer-events-none">
                        </div>
                    </div>

                    <button
                        id="toggle-decision-prompt"
                        type="button"
                        class="mt-1 text-xs text-primary hover:underline hidden">
                        ดูเพิ่มเติม
                    </button>
                </div>
                <div class="space-y-2">
                    <span class="font-label-md text-label-md text-outline block mb-2 uppercase">= ข้อความตอบกลับจากการตัดสินใจ =</span>
                    <div class="p-3 bg-surface-container-lowest border border-surface-variant rounded space-y-3">
                        <span class="font-label-md text-[0.5vw] text-outline uppercase">เหตุผล</span>
                        <div id="decision-response-reason" class="font-body-sm text-body-sm text-on-surface">-</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3 bg-surface-container rounded border border-surface-variant flex flex-col">
                                <span class="font-label-md text-[0.5vw] text-primary text-outline uppercase">เครื่องมือที่ใช้สั่งงาน</span>
                                <div id="decision-response-required-tools" class="flex flex-wrap gap-2">
                                    <span class="text-outline">-</span>
                                </div>
                            </div>
                            <div class="p-3 bg-surface-container rounded border border-surface-variant flex flex-col">
                                <span class="font-label-md text-[0.5vw] text-primary text-outline uppercase">อุปกรณ์ที่สั่งงาน</span>
                                <div id="decision-response-required-devices" class="flex flex-wrap gap-2">
                                    <span class="text-outline">-</span>
                                </div>
                            </div>
                        </div>
                        <details class="group">
                            <summary class="list-none cursor-pointer text-[0.75vw] text-secondary flex items-center gap-1 font-label-md">
                                <span class="material-symbols-outlined text-[0.75vw] transition-transform group-open:rotate-180">expand_more</span>
                                รายละเอียด
                            </summary>
                            <div id="decision-response-detail" class="mt-2 p-2 border border-surface-variant font-mono-data text-[0.65vw] text-secondary">
                                -
                            </div>
                        </details>
                    </div>
                </div>
                <div>
                    <span class="font-label-md text-label-md text-outline block mb-2 uppercase">= ผลการดำเนินการ =</span>
                    <div id="execution" class="bg-surface-variant/30 p-3 rounded font-mono-data text-[0.6vw]
 text-on-secondary-fixed-variant border border-surface-variant">
                        -
                    </div>
                </div>
            </div>

        </section>
    </main>

    <!-- </main> -->

    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/components/js-ai.html"; ?>
    <?php include "../scripts/js-ai-management.html"; ?>
</body>

</html>