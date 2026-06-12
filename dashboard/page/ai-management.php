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
                <div class="space-y-1 flex flex-col overflow-y-auto h-[100%]">
                    <!-- Device Item -->
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">Temp Sensor</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">RH Sensor</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">RH Sensor</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">LED_01</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">LED_02</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">Pump_L1</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">Temp Sensor</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">RH Sensor</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">LED_01</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">LED_02</span>
                    </label>
                    <label class="flex items-center gap-3 p-2 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <input checked="" class="w-4 h-4 text-primary-container border-outline rounded focus:ring-primary-container" type="checkbox" />
                        <span class="font-body-sm text-body-sm text-on-surface group-hover:text-primary transition-colors">Pump_L1</span>
                    </label>
                </div>
            </div>
            <div class="h-[10%] mt-auto pt-6 border-t border-surface-variant">
                <p class="font-body-sm text-body-sm text-secondary thai-leading">
                    รายการอุปกรณ์ที่สามารถเลือกให้ AI นำไปวิเคราะห์เพื่อประกอบการตัดสินใจโดยอัตโนมัติ
                </p>
            </div>
        </section>
        <!-- Middle Column: Activity Log -->
        <section class="col-span-5 bg-surface-container-lowest border border-surface-variant flex flex-col overflow-hidden">
            <div class="p-panel-padding border-b border-surface-variant">
                <h2 class="font-headline-md text-headline-md text-on-surface">Log การทำงานของ AI</h2>
            </div>
            <div class="flex-1 overflow-y-auto h-full">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-surface-container-lowest z-10">
                        <tr class="border-b border-surface-variant">
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase">เวลา</th>
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase">เหตุการณ์</th>
                            <th class="px-gutter py-3 font-label-md text-label-md text-on-surface-variant bg-surface-container-low uppercase text-right">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-variant">
                        <!-- Active Row -->
                        <tr class="bg-primary-fixed/30 border-l-4 border-l-primary-container cursor-pointer transition-colors">
                            <td class="px-gutter py-4 font-mono-data text-mono-data">14:20:45</td>
                            <td class="px-gutter py-4 font-body-sm text-body-sm">Pump_L1 Activated</td>
                            <td class="px-gutter py-4 text-right">
                                <span class="px-2 py-0.5 rounded-full bg-tertiary-container/20 text-tertiary font-label-md text-[10px] uppercase">Success</span>
                            </td>
                        </tr>
                        <!-- Normal Rows -->
                        <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                            <td class="px-gutter py-4 font-mono-data text-mono-data text-secondary">14:15:22</td>
                            <td class="px-gutter py-4 font-body-sm text-body-sm">LED_01 Off</td>
                            <td class="px-gutter py-4 text-right">
                                <span class="px-2 py-0.5 rounded-full bg-tertiary-container/20 text-tertiary font-label-md text-[10px] uppercase">Success</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                            <td class="px-gutter py-4 font-mono-data text-mono-data text-secondary">14:10:05</td>
                            <td class="px-gutter py-4 font-body-sm text-body-sm">Sensor Sync Error</td>
                            <td class="px-gutter py-4 text-right">
                                <span class="px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-md text-[10px] uppercase">Failed</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                            <td class="px-gutter py-4 font-mono-data text-mono-data text-secondary">14:05:33</td>
                            <td class="px-gutter py-4 font-body-sm text-body-sm">AI Reasoning: Ventilation</td>
                            <td class="px-gutter py-4 text-right">
                                <span class="px-2 py-0.5 rounded-full bg-tertiary-container/20 text-tertiary font-label-md text-[10px] uppercase">Success</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                            <td class="px-gutter py-4 font-mono-data text-mono-data text-secondary">13:58:12</td>
                            <td class="px-gutter py-4 font-body-sm text-body-sm">Auto-Watering Cycle</td>
                            <td class="px-gutter py-4 text-right">
                                <span class="px-2 py-0.5 rounded-full bg-tertiary-container/20 text-tertiary font-label-md text-[10px] uppercase">Success</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Right Column: Execution Details -->
        <section class="col-span-4 bg-surface-container-lowest border border-surface-variant flex flex-col overflow-y-auto">
            <div class="p-panel-padding border-b border-surface-variant">
                <h2 class="font-headline-md text-headline-md text-on-surface">ผลลัพธ์การทำงานของ AI ที่เลือก</h2>
            </div>
            <div class="p-panel-padding space-y-6">
                <!-- Detail Grid -->
                <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">เวลา</span>
                        <span class="font-mono-data text-mono-data text-on-secondary-fixed-variant">14:20:45 --:--:--</span>
                    </div>
                    <div>
                        <span class="font-label-md text-label-md text-outline block mb-1">Success</span>
                        <span class="font-body-sm text-body-sm text-tertiary font-semibold">TRUE</span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-label-md text-label-md text-outline block mb-1">Description</span>
                        <p class="font-body-sm text-body-sm text-on-surface">สั่งเปิดเครื่องสูบน้ำอัตโนมัติเนื่องจากค่าความชื้นต่ำกว่าเกณฑ์</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="p-3 bg-surface-container-low rounded border border-surface-variant">
                        <span class="font-label-md text-label-md text-outline block mb-1 uppercase">Question / Context</span>
                        <p class="font-body-sm text-body-sm text-on-surface thai-leading">ความชื้นในโซน 7 ลดลงเหลือ 42% ควรเปิดระบบน้ำหรือไม่?</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="font-label-md text-label-md text-outline block mb-2 uppercase">Prompt</span>
                            <div class="bg-surface-variant/30 p-3 rounded font-mono-data text-[12px] text-secondary border border-surface-variant">
                                [SYSTEM]: Monitor RH values... [USER]: Current RH: 42%, Threshold: 45%...
                            </div>
                        </div>
                        <div>
                            <span class="font-label-md text-label-md text-outline block mb-2 uppercase">Response</span>
                            <div class="bg-surface-variant/30 p-3 rounded font-mono-data text-[12px] text-on-secondary-fixed-variant border border-surface-variant">
                                { "action": "activate", "target": "Pump_L1", "reason": "humidity_threshold_breached" }
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-surface-container rounded border border-surface-variant">
                            <span class="font-label-md text-label-md text-outline block mb-1">Tool-data</span>
                            <span class="font-mono-data text-mono-data text-primary">ID: 0x44A2</span>
                        </div>
                        <div class="p-3 bg-surface-container rounded border border-surface-variant">
                            <span class="font-label-md text-label-md text-outline block mb-1">Execution</span>
                            <span class="font-body-sm text-body-sm">342ms</span>
                        </div>
                    </div>
                </div>
                <!-- Reason Section -->
                <div class="bg-primary/5 border border-primary-container/20 p-4 rounded-lg">
                    <div class="flex items-center gap-2 mb-2 text-primary">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                        <h3 class="font-label-md text-label-md uppercase">Reasoning Analysis</h3>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant thai-leading">
                        AI วิเคราะห์จากแนวโน้มค่าความชื้นที่ลดลงอย่างรวดเร็ว (Δ-3% ใน 10 นาที) และพยากรณ์อากาศที่อุณหภูมิจะสูงขึ้นในช่วงบ่าย จึงตัดสินใจสั่งเริ่มการทำงานของ Pump_L1 เพื่อรักษาสมดุลความชื้นของวัสดุปลูกให้อยู่ในระดับที่กำหนดล่วงหน้า
                    </p>
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