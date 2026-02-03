<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "อุณหภูมิ",
        "value" => "(Temp)",
        "unit"  => "°C"
    ],
    [
        "title" => "ความชื้น",
        "value"=> "(Humidity)",
        "unit"  => "%"
    ],
    [
        "title" => "แอมโมเนีย",
        "value"=> "(Ammonia)",
        "unit"  => "ppm"
    ],
    [
        "title" => "ออกซิเจน",
        "value"=> "(Oxygen)",
        "unit"  => "%"
    ],
    [
        "title" => "คาร์บอนไดออกไซด์",
        "value"=> "(CO₂)",
        "unit"  => "ppm"
    ],
    [
        "title" => "แสง",
        "value"=> "(Luminance)",
        "unit"  => "Lux"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
    <title>สถานะบ่อเลี้ยงไก่ - Dashboard</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ff8021",
                        "background-light": "#fcfaf8",
                        "danger": "#ef4444",
                        "warning": "#f59e0b",
                        "success": "#22c55e",
                    },
                    fontFamily: {
                        "sans": ["Inter", "Noto Sans Thai", "sans-serif"]
                    }
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            * {
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
            }
            
            html {
                @apply w-full h-full overflow-x-hidden;
            }
            
            body {
                @apply bg-background-light text-[#1d130c] w-full h-full flex flex-col overflow-x-hidden;
                font-family: 'Inter', 'Noto Sans Thai', sans-serif;
                margin: 0;
                padding: 0;
            }
        }
        
        .emojione-monotone--chicken {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='m62 29.167l-.327-1.213c-.09-.329-1.794-6.409-6.707-9.129c4.475-3.601 5.453-8.119 5.502-8.357l.269-1.319l-1.083-.753c-.291-.202-2.982-1.982-7.537-1.982c-3.678 0-7.488 1.16-11.326 3.448c-3.697 2.204-4.594 5.703-5.385 8.789c-1.006 3.932-1.824 7.056-7.806 8.482a33 33 0 0 1-1.463-.215l2.124-2s-4.656 1.5-6.412.003c-.1-.085-.211-.27-.203-.667c.016-.726.992-7.256.994-9.148c4.165-.976 6.219-5.271 4.447-6.351c-1.251-.764-2.824 1.219-5.736 1.859c.027-.383.035-.79-.006-1.242c-.227-2.526 3.335-3.503 2.903-4.805c-.561-1.689-5.144-.647-7.347 3.071a6 6 0 0 0-.61 1.465a7.4 7.4 0 0 0-.403-1.162c-1.025-2.31 2.176-4.398 1.197-5.522c-1.295-1.486-5.063 1.135-5.949 5.393a6.2 6.2 0 0 0 .07 2.696c-.088-.077-.161-.153-.257-.231c-1.628-1.298.027-4.048-1.194-4.556c-1.74-.727-3.314 2.85-2.366 6.347c.2.736.606 1.473 1.126 2.036c-3.282.371-5.704 3.53-5.811 3.674L2 18.712l1.141-.131c.013-.002.279-.03.689-.03c.573 0 1.359.068 2.132.3c-1.081 1.144-2.095 2.536-2.73 4.167c-1.465 3.758 1.86 1.811 2.537 1.893c0 0 .321.462.725.77c-.004.225-.021.436-.021.665c0 2.933.607 11.413.633 11.772l.182 2.524l1.508-1.931c2.675 7.763 8.889 14.146 16.18 15.771c.91 1.119 2.32 2.298 3.919 2.797c-2.45 3.485-6.984 3.83-6.984 4.721h13.886c0-.921-2.468-.701-4.164-4.698c1.734-.585 3.303-2.302 4.338-4.013c4.563-2.061 8.415-5.941 10.926-10.631c2.637.883 4.91 1.008 5.094 1.016l1.451.063l.467-1.419c.076-.23 1.578-4.879.965-9.325c3.662-.733 6.088-2.888 6.209-2.997zM6.749 18.071c-.88-.35-1.803-.483-2.534-.513c.958-.968 2.749-2.428 4.808-2.483q-.48.841-.884 1.815c-.446.344-.916.739-1.39 1.181m1.663 8.274c0-5.458 1.335-9.306 3.244-11.716c.068-.054.139-.104.207-.168c.548-.515 1.906-1.326 2.732-1.554c.616-.169 2.162-.101 3.132.587c.892.637 1.547 1.753 2.849 1.808c.041.002.079-.004.12-.003c-.037 1.815-.97 8.055-.988 8.91c-.019.841.243 1.57.756 2.11q.632.664 1.71.918l-2.159 1.482l1.771 2.147l-4.604-.493l.684 4.097l-4.182-3.104l-.576 4.729l-2.416-2.999l-1.824 2.336c-.188-2.816-.456-7.208-.456-9.087m26.516 25.228l-.297.129l-.164.286c-1.192 2.085-2.853 3.54-4.037 3.54c-1.321 0-3.026-1.054-4.147-2.561l-.224-.301l-.36-.073c-7.158-1.438-13.236-7.949-15.461-15.729l.477-.609l3.759 4.665l.727-5.965l5.38 3.996l-1.053-6.315l6.516.699c-2.584 1.981-3.816 4.976-3.247 8.088c.665 3.629 3.632 6.326 7.558 6.872c.908.126 1.807.189 2.674.189c6.772 0 9.977-3.809 10.109-3.972l.377-.459l-.217-.559c-.529-1.364-1.602-2.396-2.484-3.061c1.992-1.629 3.151-3.834 3.209-3.945l.496-.956l-.964-.433c-.964-.433-3.321-.742-5.056-.922a18 18 0 0 0 1.021-1.565l.653-1.149l-1.254-.324c-.07-.019-1.743-.444-4.081-.444c-2.904 0-5.485.635-7.67 1.888c-.326.188-.623.399-.918.614l-3.346-4.06l.881-.604c2.041.369 3.803.688 4.914.688c4.137 0 8.021-1.21 11.143-2.183c2.222-.691 4.141-1.29 5.55-1.29c1.007 0 2.692 0 2.692 4.811c-.001 8.887-5.534 17.724-13.156 21.014m1.541-15.569c1.456.11 3.724.349 5.166.626c-.642.919-1.707 2.201-3.074 2.952l-1.688.93l1.727.855c.02.01 1.748.879 2.613 2.302c-.987.898-3.628 2.815-8.186 2.815c-.781 0-1.593-.058-2.414-.172c-3.744-.521-5.508-3.059-5.912-5.262c-.486-2.659.819-5.234 3.409-6.72c1.894-1.085 4.157-1.636 6.728-1.636c.855 0 1.613.064 2.211.14c-.331.468-.744 1-1.195 1.466l-1.493 1.544zm15.993-4.77c1.603 4.387-.391 10.441-.391 10.441s-1.973-.09-4.296-.819c1.435-3.23 2.247-6.76 2.247-10.297c0-2.923-.48-6.811-4.632-6.811c-1.696 0-3.741.638-6.11 1.375c-1.877.585-3.904 1.211-6.048 1.626c5.401-4.245 2.722-11.694 8.528-15.157c4.104-2.446 7.617-3.179 10.355-3.179c4.094 0 6.453 1.641 6.453 1.641s-1.3 6.354-8.643 9.476c7.474.065 9.877 8.961 9.877 8.961s-3.035 2.743-7.34 2.743'/%3E%3Cellipse cx='6.905' cy='16.349' fill='%23000' rx='.65' ry='.326'/%3E%3Ccircle cx='12.335' cy='16.675' r='1.5' fill='%23000'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FFF3E9;
            border-radius: 10px;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .loading-dot {
            animation: pulse-dot 1.4s ease-in-out infinite;
        }
        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        .metric-range .bar {
            position: relative;
            background: #e7e5e4;
            overflow: hidden;
            border-radius: 999px;
        }

        .metric-range .fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            border-radius: 999px;
            clip-path: inset(0 100% 0 0);
            transition: clip-path 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Gradient สำหรับแต่ละ sensor */
        .metric-range[data-key="temp"] .fill {
            background: linear-gradient(90deg,
                #3B82F6 0%,
                #60A5FA 30%,
                #22C55E 50%,
                #EAB308 70%,
                #F97316 100%
            );
        }

        .metric-range[data-key="humidity"] .fill {
            background: linear-gradient(90deg,
                #7F1D1D 0%,
                #3B82F6 25%,
                #60A5FA 50%,
                #22C55E 75%,
                #16A34A 100%
            );
        }

        .metric-range[data-key="ammonia"] .fill {
            background: linear-gradient(90deg,
                #22C55E 0%,
                #16A34A 30%,
                #EAB308 55%,
                #F97316 75%,
                #FF0000 100%
            );
        }

        .metric-range[data-key="oxygen"] .fill {
            background: linear-gradient(90deg,
                #FF0000 0%,
                #F97316 25%,
                #EAB308 50%,
                #22C55E 75%,
                #16A34A 100%
            );
        }

        .metric-range[data-key="co2"] .fill {
            background: linear-gradient(90deg,
                #22C55E 0%,
                #16A34A 30%,
                #EAB308 55%,
                #F97316 75%,
                #FF0000 100%
            );
        }

        .metric-range[data-key="luminance"] .fill {
            background: linear-gradient(90deg,
                #1D4ED8 0%,
                #3B82F6 30%,
                #FACC15 60%,
                #FB923C 80%,
                #F97316 100%
            );
        }

        .metric-range .tick {
            position: absolute;
            top: 0;
            width: 2px;
            height: 100%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.75);
        }

        .ion--water-sharp {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23000' d='M256 43.91s-144 158.3-144 270.3c0 88.36 55.64 144 144 144s144-55.64 144-144c0-112-144-270.3-144-270.3m16 362.3v-24a60.07 60.07 0 0 0 60-60h24a84.09 84.09 0 0 1-84 84'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
        .mdi--lightning-bolt {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M11 15H6l7-14v8h5z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
        .mdi--temperature-celsius {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M11 5a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m11.5 7a5 5 0 0 0-5 5a5 5 0 0 0 5 5a5 5 0 0 0 5-5a5 5 0 0 0-5-5m0 2a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
    </style>

</head>

<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-3 sm:px-6 py-3 border-b border-stone-200 bg-white shrink-0 gap-3 sm:gap-0 w-full">
        <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <?php include 'navbar.php'; ?>
            <div class="size-8 sm:size-9 bg-[#FFD7B6] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20 shrink-0">
                <span class="emojione-monotone--chicken text-xl sm:text-2xl text-[#ff8021]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-sm sm:text-lg font-bold leading-tight text-[#1d130c] truncate">สถานะบ่อเลี้ยงไก่ (วันนี้)</h1>
                <p class="text-[9px] sm:text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-0.5">Chicken Farm Intelligence Dashboard</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 sm:gap-6 w-full sm:w-auto flex-nowrap">

            <!-- ส่วนแสดงอายุและวันที่เริ่มต้น -->
            <div class="flex items-center gap-2 bg-stone-100 p-1.5 px-3 sm:px-4 rounded-xl border border-stone-200 w-auto">
                <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm">
                    <span class="font-bold text-primary leading-none whitespace-nowrap" id="shrimp-age">
                        อายุไก่: -- วัน
                    </span>
                    <div class="w-px h-3 bg-stone-300 hidden sm:block"></div>
                    <span class="text-[10px] sm:text-xs text-stone-600 font-bold leading-none whitespace-nowrap" id="start-date">
                        -- --- ----
                    </span>
                </div>
            </div>

            <!-- ส่วนแสดงอัปเดตล่าสุด -->
            <div class="flex items-center gap-6 w-auto">
                <div class="flex flex-col items-end border-l border-stone-200 pl-3 sm:pl-6">
                    <span class="text-[9px] sm:text-[10px] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1 whitespace-nowrap">
                        อัปเดตล่าสุด
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-stone-800 whitespace-nowrap" id="last-update">
                        <?php echo $currentTime; ?>
                    </span>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-2 sm:p-4 overflow-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 w-full max-w-full">

            <!-- LEFT SECTION (Main Content) -->
            <div class="lg:col-span-9 flex flex-col gap-3 sm:gap-4 w-full">

                <!-- TOP ROW: รูปภาพ + กราฟ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 w-full">

                    <!-- รูปภาพ (Mobile/Tablet: แสดงปกติ, Desktop: ยาวลงมา 2 rows) -->
                    <div class="sm:col-span-2 lg:col-span-5 lg:row-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center min-h-[200px] sm:min-h-[250px] lg:min-h-full hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 3rem;">image</span>
                            <p class="text-xs mt-2 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-[10px] mt-1 text-stone-400">เครื่องมือ/อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- กราฟ DO (Desktop: อยู่ด้านบนขวา) -->
                    <div class="sm:col-span-2 lg:col-span-7 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                    <span class="truncate">แนวโน้มค่าออกซิเจนละลายน้ำ</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                    Historical DO Data (24H)
                                </p>
                            </div>
                            <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200 shrink-0">
                                <button id="btnDoDay" class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                <button id="btnDoMonth" class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-200 rounded-md min-h-[150px]">
                            <div id="do-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="flex gap-1">
                                    <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-1.5 rounded-full bg-stone-300 loading-dot"></span>
                                </div>
                            </div>
                            <canvas id="doTrendChart" class="absolute inset-0"></canvas>
                        </div>
                    </div>

                    <!-- กราฟ Price (Desktop: อยู่ด้านล่องขวา) -->
                    <div class="sm:col-span-2 lg:col-span-7 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex justify-between items-center mb-2 shrink-0">
                            <div>
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-primary rounded-full"></span>
                                    <span class="truncate">แนวโน้มราคาตลาด</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price Trend</p>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-100 bg-white min-h-[150px]">
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

                <!-- BOTTOM ROW: Sensor Metrics (6 cards) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 w-full" id="metrics-cards">

                    <!-- Temp Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-temp">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">อุณหภูมิ</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Temp)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">°C</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="temp">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 28-32 °C</p>
                        </div>
                    </div>

                    <!-- Humidity Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-humidity">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">ความชื้น</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Humidity)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">%</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="humidity">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 50-70 %</p>
                        </div>
                    </div>

                    <!-- Ammonia Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-ammonia">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">แอมโมเนีย</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Ammonia)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">ppm</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="ammonia">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 0-25 ppm</p>
                        </div>
                    </div>

                    <!-- Oxygen Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-oxygen">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">ออกซิเจน</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Oxygen)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">%</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="oxygen">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 18-21 %</p>
                        </div>
                    </div>

                    <!-- CO2 Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-co2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest truncate">คาร์บอนไดออกไซด์</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status shrink-0">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(CO₂)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">ppm</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="co2">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 0-3000 ppm</p>
                        </div>
                    </div>

                    <!-- Luminance Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-luminance">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">แสง</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Luminance)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">Lux</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="luminance">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 5-20 Lux</p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT SECTION (Sidebar Cards) -->
            <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 w-full">

                <!-- Card 1: การให้อาหารวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm">restaurant</span>
                        <h3 class="text-[10px] font-bold text-stone-700">การให้อาหารวันนี้</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2" id="feeding-info">
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">จำนวนมื้อ</span>
                            <span class="text-[10px] font-black text-stone-800" id="feeding-meals">--</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">เพิ่มต่อมื้อ</span>
                            <span class="text-[9px] font-black text-primary" id="feeding-increase">--</span>
                        </div>
                        <div class="col-span-2 bg-primary/5 rounded-lg p-1.5 border border-primary/10 flex justify-between items-center">
                            <span class="text-[9px] text-primary font-bold uppercase">ปริมาณรวมที่ต้องกิน</span>
                            <span class="text-[9px] font-black text-primary" id="feeding-total">--</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ต้นทุนทรัพยากร -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 group">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm">analytics</span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนทรัพยากรวันนี้</h3>
                        <div class="ml-auto w-5 h-5 rounded-full bg-stone-100 group-hover:bg-orange-100 flex items-center justify-center transition-colors duration-200">
                            <div class="ion--water-sharp text-orange-300 group-hover:text-[#ff8021] transition-colors duration-200" style="width: 12px; height: 12px;"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2" id="resource-info">
                        <div class="col-span-2 flex items-center justify-center py-2">
                            <span class="text-[10px] text-stone-400">กำลังโหลด...</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: ความชื้นและแสงสว่างที่เหมาะสม -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm">light</span>
                        <h3 class="text-[10px] font-bold text-stone-700">ความชื้นและแสงสว่างที่เหมาะสม</h3>
                    </div>
                    <div>
                        <table class="w-full">
                            <tbody class="text-[9px] divide-y divide-stone-50">
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Temp</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">28-32 °C</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Humidity</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">50-70 %</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Ammonia</td>
                                    <td class="py-0.5 text-right font-bold text-success">0-25 ppm</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Oxygen</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">18-21 %</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">CO₂</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">0-3000 ppm</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Light</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">5-20 Lux</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card 4: การปรับอาหาร -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm">rule</span>
                        <h3 class="text-[10px] font-bold text-stone-700">การปรับอาหาร</h3>
                    </div>
                    <div class="flex flex-col gap-1">
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

    <!-- Footer -->
    <footer class="px-3 sm:px-6 py-2 border-t border-stone-200 bg-white flex items-center gap-2 shrink-0 w-full">
    <div class="flex items-center gap-2 ml-auto">
        <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 1.0</span>
        <div class="h-3 w-px bg-stone-200"></div>
        <span class="text-[9px] font-bold text-primary uppercase">Smart Farm</span>
    </div>
</footer>

    <!-- JavaScript -->
    <script>
        const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const today = new Date();
        const formattedDate = `${today.getDate()} ${thaiMonths[today.getMonth()]} ${today.getFullYear() + 543}`;
        document.getElementById('start-date').textContent = formattedDate;

        document.addEventListener('DOMContentLoaded', () => {
            initMetricBars();
            loadSensorData();
            loadFeedingInfo();
            loadResourceToday();
            loadDoTrendData();
            loadMarketPriceTrend();

            setInterval(() => {
                loadSensorData();
                loadFeedingInfo();
                loadResourceToday();
                loadDoTrendData();
            }, 30000);
        });

        // ========== SENSOR DATA ==========
        async function loadSensorData() {
            try {
                const res = await fetch('/dashboard/api/generate_sensor.php');
                const rows = await res.json();

                console.log('✅ raw sensor data:', rows);

                const sensor = {};
                rows.forEach(item => {
                    if (!item.divice_name) return;
                    sensor[item.divice_name.toLowerCase()] = parseFloat(item.datax_value);
                });

                console.log('✅ mapped sensor:', sensor);

                setCardValue('temp', sensor.temp);
                setCardValue('humidity', sensor.humidity);
                setCardValue('ammonia', sensor.ammonia ?? sensor.nh3 ?? null);
                setCardValue('oxygen', sensor.oxygen);
                setCardValue('co2', sensor.co2);
                setCardValue('luminance', sensor.luminance);

            } catch (err) {
                console.error('❌ loadSensorData error:', err);
            }
        }

        // ========== BADGE ==========
        function setBadge(statusEl, text, type) {
            const map = {
                success: 'status px-2 py-0.5 rounded-full bg-green-100 text-green-600 text-[9px] font-bold uppercase',
                warning: 'status px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[9px] font-bold uppercase',
                danger: 'status px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[9px] font-bold uppercase',
                info: 'status px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[9px] font-bold uppercase',
                na: 'status px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase',
                orange: 'status px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[9px] font-bold uppercase',
            };
            statusEl.textContent = text;
            statusEl.className = map[type] || map.na;
        }

        // ========== STATUS LOGIC (chicken-specific) ==========
        function getStatusByKey(key, v) {
            if (key === 'temp') {
                if (v >= 28 && v <= 32) return { text: 'เหมาะสม', type: 'success' };
                if (v < 28) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'danger' };
            }
            if (key === 'humidity') {
                if (v >= 50 && v <= 70) return { text: 'เหมาะสม', type: 'success' };
                if (v < 50) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'warning' };
            }
            if (key === 'ammonia') {
                if (v >= 0 && v <= 10) return { text: 'ดีที่สุด', type: 'success' };
                if (v > 10 && v <= 25) return { text: 'เหมาะสม', type: 'success' };
                if (v > 25 && v <= 50) return { text: 'สูง', type: 'warning' };
                return { text: 'อันตราย', type: 'danger' };
            }
            if (key === 'oxygen') {
                if (v >= 18 && v <= 21) return { text: 'เหมาะสม', type: 'success' };
                if (v < 18) return { text: 'ต่ำ', type: 'danger' };
                return { text: 'สูง', type: 'info' };
            }
            if (key === 'co2') {
                if (v >= 0 && v <= 1500) return { text: 'ดีที่สุด', type: 'success' };
                if (v > 1500 && v <= 3000) return { text: 'เหมาะสม', type: 'success' };
                if (v > 3000 && v <= 5000) return { text: 'สูง', type: 'warning' };
                return { text: 'อันตราย', type: 'danger' };
            }
            if (key === 'luminance') {
                if (v >= 5 && v <= 20) return { text: 'เหมาะสม', type: 'success' };
                if (v < 5) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'warning' };
            }
            return { text: 'N/A', type: 'na' };
        }

        // ========== CARD VALUE ==========
        function setCardValue(key, value) {
            const card = document.getElementById(`card-${key}`);
            if (!card) return;

            const valueEl = card.querySelector('.value');
            const statusEl = card.querySelector('.status');

            if (value === null || value === undefined || isNaN(value)) {
                valueEl.textContent = '--';
                setBadge(statusEl, 'N/A', 'na');
                return;
            }

            const v = Number(value);
            valueEl.textContent = v.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            const st = getStatusByKey(key, v);
            setBadge(statusEl, st.text, st.type);
            updateMetricBar(key, v);
        }

        // ========== BAR CONFIG (chicken sensors) ==========
        const BAR_CONFIG = {
            temp: {
                min: 25, max: 35,
                leftLabel: '28°C', rightLabel: '32°C',
                leftColor: '#3B82F6', rightColor: '#F97316',
            },
            humidity: {
                min: 30, max: 90,
                leftLabel: '50%', rightLabel: '70%',
                leftColor: '#3B82F6', rightColor: '#198754',
            },
            ammonia: {
                min: 0, max: 75,
                leftLabel: '0', rightLabel: '25',
                leftColor: '#198754', rightColor: '#DC2626',
            },
            oxygen: {
                min: 15, max: 25,
                leftLabel: '18%', rightLabel: '21%',
                leftColor: '#DC2626', rightColor: '#198754',
            },
            co2: {
                min: 0, max: 6000,
                leftLabel: '0', rightLabel: '3000',
                leftColor: '#198754', rightColor: '#DC2626',
            },
            luminance: {
                min: 0, max: 40,
                leftLabel: '5', rightLabel: '20',
                leftColor: '#1D4ED8', rightColor: '#F97316',
            },
        };

        function initMetricBars() {
            document.querySelectorAll('.metric-range').forEach(wrap => {
                const key = wrap.dataset.key;
                const cfg = BAR_CONFIG[key];
                if (!cfg) return;

                wrap.classList.remove('hidden');

                const left = wrap.querySelector('.label-left');
                const right = wrap.querySelector('.label-right');
                left.textContent = cfg.leftLabel || '';
                right.textContent = cfg.rightLabel || '';
                left.style.color = cfg.leftColor || '#C73434';
                right.style.color = cfg.rightColor || '#198754';

                const fill = wrap.querySelector('.fill');
                if (fill) fill.style.clipPath = 'inset(0 100% 0 0)';
            });
        }

        function updateMetricBar(key, value) {
            const cfg = BAR_CONFIG[key];
            if (!cfg) return;

            const wrap = document.querySelector(`.metric-range[data-key="${key}"]`);
            if (!wrap) return;

            const fill = wrap.querySelector('.fill');

            if (value === null || value === undefined || isNaN(value)) {
                if (fill) fill.style.clipPath = 'inset(0 100% 0 0)';
                return;
            }

            const v = Number(value);
            const pct = ((v - cfg.min) / (cfg.max - cfg.min)) * 100;
            const safePct = clamp(pct, 0, 100);

            if (fill) {
                fill.style.clipPath = `inset(0 ${100 - safePct}% 0 0)`;
            }
        }

        function clamp(n, min, max) {
            return Math.max(min, Math.min(max, n));
        }

        // ========== FEEDING INFO ==========
        async function loadFeedingInfo() {
            const mealsEl = document.getElementById('feeding-meals');
            const increaseEl = document.getElementById('feeding-increase');
            const totalEl = document.getElementById('feeding-total');

            if (!mealsEl || !increaseEl || !totalEl) return;

            mealsEl.textContent = '--';
            increaseEl.textContent = '--';
            totalEl.textContent = '--';

            try {
                const res = await fetch('/dashboard/api/food_preparation.php', { cache: 'no-store' });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const rows = await res.json();
                if (!Array.isArray(rows) || rows.length === 0) throw new Error('ไม่มีข้อมูล');

                rows.forEach(item => {
                    const label = (item.label || '').toLowerCase();
                    const value = item.value || '--';

                    if (label.includes('จำนวนมื้อ') || item.id == 10) {
                        mealsEl.textContent = value;
                    } else if (label.includes('เพิ่มต่อมื้อ') || item.id == 15) {
                        increaseEl.textContent = value;
                    } else if (label.includes('ปริมาณรวม') || item.id == 14) {
                        totalEl.textContent = value;
                    }
                });

            } catch (err) {
                console.error('❌ loadFeedingInfo error:', err);
                mealsEl.textContent = 'N/A';
                increaseEl.textContent = 'N/A';
                totalEl.textContent = 'N/A';
            }
        }

        // ========== RESOURCE TODAY ==========
        let resourceTimer = null;
        let resourceIndex = 0;
        let resourceTotal = 0;

        async function loadResourceToday() {
            const box = document.getElementById("resource-info");
            if (!box) return;

            box.classList.remove("grid", "grid-cols-2", "gap-2");
            box.classList.add("flex", "overflow-x-auto", "snap-x", "snap-mandatory", "scroll-smooth", "no-scrollbar");

            if (typeof stopResourceAutoSlide === "function") stopResourceAutoSlide();

            box.innerHTML = `
                <div class="w-full shrink-0 snap-start flex items-center justify-center">
                    <span class="text-xs text-stone-400">กำลังโหลด...</span>
                </div>
            `;

            try {
                const res = await fetch("/dashboard/api/resource_to_day.php", { cache: "no-store" });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();

                const item = Array.isArray(data)
                    ? data.find(x => String(x.id) === "1")
                    : (String(data?.id) === "1" ? data : null);

                if (!item) {
                    box.innerHTML = `
                        <div class="w-full shrink-0 snap-start flex items-center justify-center">
                            <span class="text-xs text-stone-400">ไม่พบข้อมูล id=1</span>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="w-full h-full shrink-0 snap-start">
                        <div class="w-full h-full">${renderResourceCard(item)}</div>
                    </div>
                `;

            } catch (err) {
                console.error("❌ loadResourceToday error:", err);
                box.innerHTML = `
                    <div class="w-full shrink-0 snap-start flex items-center justify-center">
                        <span class="text-xs text-red-500 font-semibold">โหลดข้อมูลไม่สำเร็จ</span>
                    </div>
                `;
            }
        }

        function stopResourceAutoSlide() {
            const box = document.getElementById("resource-info");
            if (box) box.removeEventListener("scroll", onResourceScroll);
            if (resourceTimer) { clearInterval(resourceTimer); resourceTimer = null; }
        }

        function onResourceScroll() {
            const box = document.getElementById("resource-info");
            if (!box) return;
            const w = box.clientWidth || 1;
            const idx = Math.round(box.scrollLeft / w);
            if (Number.isFinite(idx)) resourceIndex = clamp(idx, 0, Math.max(0, resourceTotal - 1));
        }

        function renderResourceCard(item) {
            const water = toNumber(item.water_value);
            const waterUnit = item.water_unit ?? "m3";
            const elec = toNumber(item.electric_value);
            const elecUnit = item.electric_unit ?? "kWh";
            const updated = item.updated_at ?? "";

            return `
                <div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-100 border border-stone-200 p-2">
                            <div class="text-[10px] text-stone-500 font-semibold text-center">ค่าน้ำประปา</div>
                            <div class="text-sm text-center font-extrabold ${water === 0 ? "text-stone-400" : "text-stone-800"}">
                                ${formatNumber(water)} <span class="text-[10px] font-bold text-stone-500">${escapeHtml(waterUnit)}</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-gray-100 border border-stone-200 p-2">
                            <div class="text-[10px] text-stone-500 font-semibold text-center">ค่าไฟฟ้า</div>
                            <div class="text-sm text-center font-extrabold ${elec === 0 ? "text-stone-400" : "text-stone-800"}">
                                ${formatNumber(elec)} <span class="text-[10px] font-bold text-stone-500">${escapeHtml(elecUnit)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-[9px] text-stone-400 font-semibold text-end">
                        อัปเดต: ${escapeHtml(updated)}
                    </div>
                </div>
            `;
        }

        function toNumber(v) {
            const n = parseFloat(v);
            return Number.isFinite(n) ? n : 0;
        }

        function formatNumber(n) {
            return (Number.isFinite(n) ? n : 0).toFixed(1);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ========== DO TREND CHART ==========
        let doChart = null;

        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');

            try {
                const res = await fetch('/dashboard/api/monitor_trend.php', { cache: 'no-store' });
                const data = await res.json();

                console.log('📊 DO Trend API Response:', data);

                const doData = data.find(item => item.device_id === 2);

                if (!doData || !doData.points || doData.points.length === 0) {
                    console.warn('⚠️ ไม่มีข้อมูล DO');
                    renderDoChart([], []);
                    return;
                }

                const labels = doData.points.map(p => {
                    const d = new Date(p.time);
                    return `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
                });

                const values = doData.points.map(p => parseFloat(p.value));
                renderDoChart(labels, values);

            } catch (err) {
                console.error('❌ loadDoTrendData error:', err);
                renderDoChart([], []);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function renderDoChart(labels, values) {
            const ctx = document.getElementById('doTrendChart');
            if (!ctx) return;

            if (doChart) doChart.destroy();

            doChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'DO (mg/L)',
                        data: values,
                        borderColor: '#ff8021',
                        backgroundColor: 'rgba(255,128,33,0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 2,
                        pointBackgroundColor: '#ff8021'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L` }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 9 }, color: '#78716c', maxRotation: 0, autoSkip: true, maxTicksLimit: 8 }
                        },
                        y: {
                            beginAtZero: true,
                            max: 10,
                            ticks: { font: { size: 9 }, color: '#78716c', callback: v => v.toFixed(1) },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        }
                    }
                }
            });
        }

        // ========== MARKET PRICE CHART ==========
        let marketPriceChart = null;

        async function loadMarketPriceTrend() {
            const loading = document.getElementById('price-loading');

            try {
                const res = await fetch('/dashboard/api/market_price_Tred.php', { cache: 'no-store' });
                const raw = await res.json();

                console.log('📈 price trend raw:', raw);

                if (!Array.isArray(raw) || raw.length === 0) return;

                const labels = raw.map(r => r.event_month);

                const price50 = raw
                    .filter(r => r.data_table_id === "19")
                    .map(r => Number(r.event_price));

                const price70 = raw
                    .filter(r => r.data_table_id === "20")
                    .map(r => Number(r.event_price));

                renderMarketPriceChart(labels, price50, price70);

            } catch (err) {
                console.error('❌ price trend error:', err);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function renderMarketPriceChart(labels, price50, price70) {
            const ctx = document.getElementById('marketPriceChart');
            if (!ctx) return;

            if (marketPriceChart) marketPriceChart.destroy();

            marketPriceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: '50 ตัว/กก.',
                            data: price50,
                            borderColor: '#ff8021',
                            backgroundColor: 'rgba(255,128,33,0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2.5
                        },
                        {
                            label: '70 ตัว/กก.',
                            data: price70,
                            borderColor: 'rgba(255,128,33,0.4)',
                            backgroundColor: 'rgba(255,128,33,0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: ctx => ` ${ctx.raw} บาท/กก.` }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, color: '#78716c' }
                        },
                        y: {
                            ticks: { font: { size: 10 }, color: '#78716c', callback: v => v + ' ฿' }
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>