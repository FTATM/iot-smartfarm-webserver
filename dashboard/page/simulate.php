<?php
session_start();
include '../components/session.php';
checkLogin();
$Title = $_SESSION['branch_name'] . " Simulate";
$subTitle = $_SESSION['branch_name'] . " simulate device system";
$classIconHeader = "gravity-ui--signal";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title><?php echo $Title; ?></title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <div class="grid grid-cols-12 h-full min-h-0">

            <div class="col-span-9 flex flex-col min-h-0 bg-white">
                <div class="flex justify-between items-center h-[10%] min-h-[50px] bg-stone-50 shadow border border-outline-variant px-2">
                    <div class="text-[2rem]">Simulation Workbench</div>
                    <div class="w-[30%] flex justify-end gap-1 p-2">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <span class="font-label-md text-secondary">Auto Active:</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input onclick="toggleSimulate()" id="switch-run" class="sr-only peer" type="checkbox" />
                                    <div class="w-11 h-6 bg-surface-dim peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                            <div class="h-4 w-[1px] bg-outline-variant"></div>
                            <div class="flex items-center gap-2">
                                <span class="font-label-md text-secondary">Interval (s):</span>
                                <input id="time-interval" class="w-16 h-8 bg-surface-container-low border border-outline-variant rounded px-2 font-mono-data text-sm focus:ring-1 focus:ring-primary focus:border-primary" type="number" value="5" min="1" max="100" step="1" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-1 min-h-0 bg-red-400 shadow">
                    <!-- Left Panel: Devices Selection -->
                    <section class="w-[30%] bg-surface-container-lowest border-r border-outline-variant flex flex-col">
                        <div class="p-4 border-b border-outline-variant bg-surface-container-low">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-label-md text-primary uppercase">Devices Selection</h3>
                                <div class="flex gap-1">
                                    <span id="devices-count" class="font-mono-data text-[10px] text-secondary">-</span>
                                    <span class="font-mono-data text-[10px] text-secondary"> devices</span>
                                </div>
                            </div>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-2 top-1.5 text-secondary text-sm">search</span>
                                <input class="w-full pl-8 pr-3 py-1.5 text-body-sm bg-surface-container-highest border-none rounded-lg focus:ring-1 focus:ring-primary" placeholder="Filter devices..." type="text" />
                            </div>
                        </div>
                        <div id="devices-list" class="flex-1 overflow-y-auto custom-scrollbar">
                            <!-- Device Row: Active -->
                            <div class="flex items-center p-3 border-b border-outline-variant hover:bg-surface-container transition-colors cursor-pointer bg-secondary-container/20 border-l-4 border-l-primary">
                                <input checked="" class="w-4 h-4 rounded border-outline text-primary focus:ring-primary" type="checkbox" />
                                <div class="ml-3 flex flex-col overflow-hidden">
                                    <span class="font-label-md truncate">NODE_EXT_TEMP_01</span>
                                    <span class="font-mono-data text-[10px] text-secondary">ID: 0x4A221 • RT_SYNC</span>
                                </div>
                                <span class="material-symbols-outlined ml-auto text-primary text-lg">chevron_right</span>
                            </div>

                        </div>
                        <div class="p-3 bg-surface-container-low border-t border-outline-variant">
                            <button onclick="updateDeviceList()" class="w-full bg-primary text-on-primary font-label-md text-label-md py-2 rounded flex items-center justify-center gap-2 hover:bg-primary-container transition-colors"><span class="material-symbols-outlined text-sm">save</span>Save Configuration</button>
                        </div>
                    </section>
                    <!-- Center Panel: Auto-Simulation Config -->
                    <section class="flex-1 flex flex-col bg-surface border-r border-outline-variant">
                        <div id="sim-info" class="p-panel-padding bg-surface-container-lowest border-b border-outline-variant">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded bg-primary-container/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-2xl">sensors</span>
                                </div>
                                <div>
                                    <h3 id="device-name" class="font-headline-lg">-</h3>
                                    <p class="font-body-sm text-secondary">Configuring auto-stochastic data stream for temperature sensing node.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Min (Number)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Minimum threshold for randomization.</p>
                                    <input id="sim-min" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Max (Number)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Maximum threshold for randomization.</p>
                                    <input id="sim-max" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Base (Number)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Constant starting value offset.</p>
                                    <input id="sim-base" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Pull (%)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Mean-reversion strength factor.</p>
                                    <input id="sim-pull" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Step (±)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Incremental change per iteration.</p>
                                    <input id="sim-step" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block font-label-md text-secondary uppercase tracking-wider">Noise Sigma (±)</label>
                                    <p class="text-[10px] text-secondary italic -mt-1">Gaussian variance coefficient.</p>
                                    <input id="sim-noise" class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-3 font-mono-data text-primary font-bold text-center input-focus transition-all" type="text" value="-" />
                                </div>
                            </div>
                        </div>
                        <!-- Result Area -->
                        <div class="flex-1 flex flex-col overflow-hidden">
                            <div class="px-panel-padding py-3 bg-surface-container-high border-b border-outline-variant flex justify-between items-center">
                                <span class="font-label-md text-on-surface-variant flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-primary">analytics</span>
                                    LIVE AUTO-SIMULATION LOG
                                </span>
                                <span class="font-mono-data text-[10px] text-secondary">UPS: 1.2Hz</span>
                            </div>
                            <div id="result-a" class="flex-1 overflow-y-auto custom-scrollbar bg-white p-4 space-y-1">
                                <div class="w-full h-full text-stone-400 flex justify-center items-center">
                                    it's not has Log(s) now.
                                </div>

                            </div>
                            <div class="py-2 px-4 bg-surface-container-low border-t border-outline-variant flex justify-between">
                                <div class="flex gap-2">
                                    <span class="px-3 py-1 rounded bg-green-100 text-green-700 font-label-md text-[10px]">TOTAL PUSHES: 1,402</span>
                                    <span class="px-3 py-1 rounded bg-red-100 text-red-700 font-label-md text-[10px]">DROPPED: 0</span>
                                </div>
                                <button class="text-primary font-label-md flex items-center gap-1 hover:underline">
                                    <span class="material-symbols-outlined text-sm">download</span>
                                    Export TSV
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="col-span-3 flex flex-col min-h-0 bg-white">
                <div class="p-panel-padding bg-surface border-b border-outline-variant">
                    <h3 class="font-headline-md mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">precision_manufacturing</span>
                        Manual Controller
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-label-md text-secondary mb-1">Target Device Selection</label>
                            <select id="devices-list-m" class="w-full bg-surface-container border border-outline-variant rounded-lg p-2 font-body-sm focus:ring-1 focus:ring-primary">
                                <option>-- Select Device --</option>
                                <option selected="">NODE_EXT_TEMP_01</option>
                                <option>VALVE_CTRL_BETA_2</option>
                            </select>
                        </div>
                        <div id="device-info" class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block font-label-md text-secondary text-[10px] uppercase">Group ID</label>
                                <div id="group_label" class="bg-surface-container-high px-2 py-1.5 rounded font-mono-data text-xs text-secondary border border-outline-variant">-</div>
                            </div>
                            <div class="space-y-1">
                                <label class="block font-label-md text-secondary text-[10px] uppercase">Device ID</label>
                                <div id="device_label" class="bg-surface-container-high px-2 py-1.5 rounded font-mono-data text-xs text-secondary border border-outline-variant">-</div>
                            </div>
                            <div class="space-y-1">
                                <label class="block font-label-md text-secondary text-[10px] uppercase">Type ID</label>
                                <div id="type_label" class="bg-surface-container-high px-2 py-1.5 rounded font-mono-data text-xs text-secondary border border-outline-variant">-</div>
                            </div>
                            <div class="space-y-1">
                                <label class="block font-label-md text-secondary text-[10px] uppercase">DataX ID</label>
                                <div id="datax_label" class="bg-surface-container-high px-2 py-1.5 rounded font-mono-data text-xs text-secondary border border-outline-variant">-</div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-outline-variant">
                            <label class="block font-headline-md text-on-surface mb-2">Simulate Value</label>
                            <div class="flex gap-2">
                                <input id="simulate-input" class="flex-1 bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 font-mono-data focus:ring-1 focus:ring-primary" placeholder="Enter value..." type="text" />
                                <button onclick="sendDevice()" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md hover:bg-surface-tint transition-all active:scale-95">
                                    SEND
                                </button>
                            </div>
                            <p class="font-label-md text-secondary text-[11px] mt-2 italic">Injects single data packet into the API middleware.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white flex flex-col flex-1">
                    <!-- Manual Log Result -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <div class="px-panel-padding py-2 bg-surface-container-high border-b border-outline-variant flex items-center justify-between">
                            <span class="font-label-md text-on-surface-variant">MANUAL LOG</span>
                            <button class="text-[10px] text-primary hover:underline">Clear</button>
                        </div>
                        <div id="result-m" class="flex-1 overflow-y-auto custom-scrollbar p-panel-padding space-y-3 bg-surface-container-low">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php include "../components/footer.php"; ?>

    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/components/js-overlay.html"; ?>
    <?php include "../scripts/js-simulate.html"; ?>
</body>

</html>