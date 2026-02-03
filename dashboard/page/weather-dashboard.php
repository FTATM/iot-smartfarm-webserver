<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "ทิศทางลม",
        "value" => "(Wind Direction)",
        "unit"  => "∘"
    ],
    [
        "title" => "ดัชนีคุณภาพอากาศ",
        "value" => "(AQI)",
        "unit"  => "ug/m3"
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
        "title" => "ปริมาณน้ำฝน",
        "value"=> "(Rainfall)",
        "unit"  => "mm/min"
    ],
    [
        "title" => "ความเข้มแสง",
        "value"=> "(Sunlight intensity)",
        "unit"  => "LUX"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Weather Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    
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
            body {
                @apply bg-background-light text-[#1d130c] h-screen flex flex-col overflow-hidden;
                font-family: 'Inter', 'Noto Sans Thai', sans-serif;
            }
        }

        /* Icons */
        .arcticons--weathercan {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' d='M22.51 15.44a6.94 6.94 0 0 0-12.42 4A6.23 6.23 0 0 0 8.38 31m9.19.8h10.74a4.87 4.87 0 0 0 4.87-4.87a4.8 4.8 0 0 0-.58-2.3' stroke-width='2'/%3E%3Ccircle cx='30.18' cy='17.11' r='6.02' fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'/%3E%3Cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' d='M30.18 4.5v5.1m8.92-1.41l-3.61 3.61m7.3 5.31H37.7m1.4 8.92l-3.61-3.6M21.26 8.19l3.61 3.61m-8.75 21.51l-1.81-5.53l-4.24 4a3.83 3.83 0 0 0 1.2 6.39l.36.11l.38.08a3.83 3.83 0 0 0 4.11-5.05m5.5 6.11l-1.89-3.81L17.08 39a2.8 2.8 0 0 0 1.55 4.49h.55a2.8 2.8 0 0 0 2.44-4.07' stroke-width='2'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .carbon--temperature-hot {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cpath fill='%23000' d='M26 13h4v2h-4zm-3-5.414l2.828-2.828l1.414 1.414L24.414 9zm0 12.828L24.414 19l2.828 2.828l-1.414 1.414zM17 2h2v4h-2zm1 6a6 6 0 0 0-1 .09v2.052A4 4 0 0 1 18 10a4 4 0 0 1 0 8v2a6 6 0 0 0 0-12m-8 12.184V7H8v13.184a3 3 0 1 0 2 0'/%3E%3Cpath fill='%23000' d='M9 30a6.993 6.993 0 0 1-5-11.89V7a5 5 0 0 1 10 0v11.11A6.993 6.993 0 0 1 9 30M9 4a3.003 3.003 0 0 0-3 3v11.983l-.332.299a5 5 0 1 0 6.664 0L12 18.983V7a3.003 3.003 0 0 0-3-3'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .solar--wind-bold-duotone {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' fill-rule='evenodd' d='M6.25 5.5A3.25 3.25 0 1 1 9.5 8.75H3a.75.75 0 0 1 0-1.5h6.5A1.75 1.75 0 1 0 7.75 5.5v.357a.75.75 0 1 1-1.5 0z' clip-rule='evenodd'/%3E%3Cpath fill='%23000' d='M3.25 14a.75.75 0 0 1 .75-.75h14.5a4.25 4.25 0 1 1-4.25 4.25V17a.75.75 0 0 1 1.5 0v.5a2.75 2.75 0 1 0 2.75-2.75H4a.75.75 0 0 1-.75-.75' opacity='0.4'/%3E%3Cpath fill='%23000' d='M14.25 7.5a4.25 4.25 0 1 1 4.25 4.25H2a.75.75 0 0 1 0-1.5h16.5a2.75 2.75 0 1 0-2.75-2.75V8a.75.75 0 0 1-1.5 0z' opacity='0.7'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .wi--humidity {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath fill='%23000' d='M7.56 17.19q0-1.32.72-3.03c.72-1.71 1.1-2.25 1.86-3.31c1.56-2.06 2.92-3.62 4.06-4.67l.75-.72c.25.26.53.5.83.72c.41.42 1.04 1.11 1.88 2.09s1.57 1.85 2.17 2.65c.71 1.01 1.32 2.1 1.81 3.25s.74 2.16.74 3.03c0 1-.19 1.95-.58 2.86s-.91 1.7-1.57 2.36s-1.45 1.19-2.37 1.58s-1.89.59-2.91.59c-1 0-1.95-.19-2.86-.57s-1.7-.89-2.36-1.55c-.66-.65-1.19-1.44-1.58-2.35s-.59-1.89-.59-2.93m2.26-2.93c0 .83.17 1.49.52 1.99c.35.49.88.74 1.59.74c.72 0 1.25-.25 1.61-.74c.35-.49.53-1.15.54-1.99c-.01-.84-.19-1.5-.54-2c-.35-.49-.89-.74-1.61-.74c-.71 0-1.24.25-1.59.74c-.35.5-.52 1.16-.52 2m1.57 0v-.35c0-.08.01-.19.02-.33s.02-.25.05-.32s.05-.16.09-.24s.09-.15.15-.18c.07-.04.14-.06.23-.06q.21 0 .33.12c.12.12.14.21.17.38c.03.18.05.32.06.45s.01.3.01.52c0 .23 0 .4-.01.52q-.015.18-.06.45c-.03.17-.09.3-.17.38s-.19.12-.33.12c-.09 0-.16-.02-.23-.06a.34.34 0 0 1-.15-.18c-.04-.08-.07-.17-.09-.24c-.02-.08-.04-.19-.05-.32c-.01-.14-.02-.25-.02-.32zm.59 7.75h1.32l4.99-10.74h-1.35zm4.3-2.99c.01.84.2 1.5.55 2c.35.49.89.74 1.6.74c.72 0 1.25-.25 1.6-.74s.52-1.16.53-2c-.01-.84-.18-1.5-.53-1.99s-.88-.74-1.6-.74c-.71 0-1.25.25-1.6.74c-.36.49-.54 1.15-.55 1.99m1.57 0c0-.23 0-.4.01-.52q.015-.18.06-.45c.045-.27.09-.3.17-.38s.19-.12.33-.12q.135 0 .24.06c.07.04.12.1.16.19s.07.17.1.24s.04.18.05.32l.01.32v.69l-.01.32l-.05.32l-.1.24l-.16.19l-.24.06q-.21 0-.33-.12c-.12-.12-.14-.21-.17-.38q-.045-.27-.06-.45c-.015-.18-.01-.3-.01-.53'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .emojione-monotone--sun-behind-rain-cloud {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='M23.367 10.211L20.002 2l-3.368 8.211A19.5 19.5 0 0 1 20 9.902c1.152 0 2.273.118 3.367.309M9.903 19.999c0-1.15.116-2.271.309-3.365L2 20.001l8.211 3.365a19.4 19.4 0 0 1-.308-3.367m2.958-7.14a19.4 19.4 0 0 1 2.598-2.162L7.272 7.272l3.427 8.186a19 19 0 0 1 2.162-2.599' stroke-width='1.5' stroke='%23000'/%3E%3Cpath fill='%23000' d='M56.511 14.725a11 11 0 0 0-.496-.267v-.078C56.015 7.555 50.43 2 43.566 2c-4.27 0-8.23 2.183-10.508 5.742l-.266-.041a12 12 0 0 0-1.758-.134c-3.623 0-6.977 1.614-9.266 4.224A8.4 8.4 0 0 0 11.599 20a8.36 8.36 0 0 0 2.929 6.352c.075.953.298 1.871.642 2.733a19 19 0 0 1-2.313-1.944a19.5 19.5 0 0 1-2.16-2.6l-3.426 8.187l8.019-3.355a9.4 9.4 0 0 0 2.585 3.445L20.002 38l1.367-3.335a9.5 9.5 0 0 0 2.627.379h27.055c.817 0 1.646-.095 2.466-.285C58.51 33.619 62 29.258 62 24.154c0-3.875-2.104-7.488-5.489-9.429M52.8 31.692a7.6 7.6 0 0 1-1.747.205H23.996a6.3 6.3 0 0 1-1.984-.322c-2.521-.827-4.344-3.188-4.344-5.973c0-1.894.843-3.587 2.171-4.739a6.3 6.3 0 0 1 2.073-1.2a6.4 6.4 0 0 1 2.084-.357c2.041 0 3.851.966 5.01 2.457l.057-.001a8.68 8.68 0 0 0-6.797-4.473a9.13 9.13 0 0 1 8.77-6.576c.445 0 .879.043 1.305.104a9.2 9.2 0 0 1 2.407.686a9.09 9.09 0 0 1 5.314 6.984l.002-.054c0-3.563-1.837-6.696-4.618-8.524a9.29 9.29 0 0 1 8.121-4.762c5.126 0 9.281 4.134 9.281 9.232c0 .248-.017.489-.035.732a9.72 9.72 0 0 0-5.629 2.336a7.78 7.78 0 0 1 5.392-.881c.842.167 1.633.47 2.352.884c2.333 1.338 3.906 3.835 3.906 6.703c0 3.676-2.578 6.751-6.034 7.539M33.008 47.575c.674-1.85.719-4.382.262-7.316c-2.201 1.924-3.832 3.922-4.504 5.771c-.572 1.569-.086 3.189 1.086 3.616s2.586-.501 3.156-2.071m8.855 0c.672-1.85.719-4.382.262-7.316c-2.203 1.924-3.832 3.922-4.506 5.771c-.57 1.569-.086 3.189 1.086 3.616s2.586-.501 3.158-2.071m4.611-1.545c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.203 1.924-3.833 3.922-4.505 5.771m-22.32 1.545c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771c-.571 1.569-.086 3.189 1.086 3.616s2.586-.501 3.157-2.071M21.815 58.28c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771m8.855 0c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771m8.853 0c-.57 1.569-.086 3.189 1.086 3.616s2.586-.502 3.158-2.071c.672-1.85.719-4.382.262-7.316c-2.203 1.924-3.832 3.922-4.506 5.771m-26.563 0c-.57 1.569-.086 3.189 1.086 3.616s2.586-.502 3.159-2.071c.672-1.85.719-4.382.262-7.316c-2.204 1.924-3.833 3.922-4.507 5.771' stroke-width='1.5' stroke='%23000'/%3E%3C/svg%3E");
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

        /* Hide on mobile */
        @media (max-width: 640px) {
            .mobile-hide {
                display: none !important;
            }
        }
    </style>

</head>

<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-3 sm:px-6 py-3 border-b border-stone-200 bg-white shrink-0 gap-3 sm:gap-0">
        <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <?php include 'navbar.php'; ?>
            <div class="size-8 sm:size-9 bg-[#FFD7B6] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20 shrink-0">
                <span class="arcticons--weathercan text-xl sm:text-2xl text-[#ff8021]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-sm sm:text-lg font-bold leading-tight text-[#1d130c] truncate">Weather Dashboard</h1>
                <p class="text-[9px] sm:text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-0.5">Weather Farm Intelligence</p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-2 sm:gap-6 w-full sm:w-auto">
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
    <main class="flex-1 p-2 sm:p-4 overflow-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4">

            <!-- LEFT SECTION (Main Content) -->
            <div class="lg:col-span-10 flex flex-col gap-3 sm:gap-4">

                <!-- TOP ROW: รูปภาพ + กราฟ (Desktop: รูปซ้ายยาวลงมา) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-10 gap-3 sm:gap-4">

                    <!-- รูปภาพ 1 (Mobile/Tablet: แสดงปกติ, Desktop: ยาวลงมา 2 rows) -->
                    <div class="sm:col-span-1 lg:col-span-1 lg:row-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center min-h-[200px] sm:min-h-[250px] lg:min-h-full hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 3rem; sm:font-size: 4rem;">image</span>
                            <p class="text-xs mt-2 font-medium">รูปภาพ 1</p>
                            <p class="text-[10px] mt-1 text-stone-400">อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- รูปภาพ 2 (Mobile/Tablet: แสดงปกติ, Desktop: ยาวลงมา 2 rows) -->
                    <div class="sm:col-span-2 lg:col-span-5 lg:row-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center min-h-[200px] sm:min-h-[250px] lg:min-h-full hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 3rem; sm:font-size: 5rem;">image</span>
                            <p class="text-xs mt-2 font-medium">รูปภาพ 2</p>
                            <p class="text-[10px] mt-1 text-stone-400">พื้นที่ขยาย</p>
                        </div>
                    </div>

                    <!-- กราฟ DO (Desktop: อยู่ด้านบนขวา) -->
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-2 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                    <span class="truncate">แนวโน้มค่า DO</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5 mobile-hide">
                                    24H Data
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

                    <!-- กราฟ Price (Desktop: อยู่ด้านล่างขวา) -->
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex justify-between items-center mb-2 shrink-0">
                            <div>
                                <h2 class="text-[10px] sm:text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                    <span class="w-1 h-3 bg-primary rounded-full"></span>
                                    <span class="truncate">ราคาตลาด</span>
                                </h2>
                                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5 mobile-hide">Market Price</p>
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

                <!-- BOTTOM ROW: Sensor Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4" id="metrics-cards">
                    <?php foreach ($metricTitles as $i => $metric): ?>
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-metric-<?= $i ?>">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest truncate"><?= $metric['title'] ?></span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center py-2">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-400 uppercase mb-1"><?= $metric['value'] ?></span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-[10px] sm:text-xs font-bold text-stone-400"><?= $metric['unit'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- RIGHT SECTION (Sidebar Cards) -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">

                <!-- Card 1: พยากรณ์อุณหภูมิ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="carbon--temperature-hot text-primary text-sm"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์อุณหภูมิ</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">ปัจจุบัน</span>
                            <span class="text-[10px] font-black text-stone-800">--</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">สูงสุด</span>
                            <span class="text-[9px] font-black text-primary">--</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: พยากรณ์ลม -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="solar--wind-bold-duotone text-sm text-primary"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์ลม (ws10m)</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">ความเร็ว</span>
                            <span class="text-[10px] font-black text-stone-800">--</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">ทิศทาง</span>
                            <span class="text-[9px] font-black text-primary">--</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: ความชื้นสัมพัทธ์ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 mobile-hide">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="wi--humidity text-sm text-primary"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ความชื้นสัมพัทธ์ (rh)</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">ปัจจุบัน</span>
                            <span class="text-[10px] font-black text-stone-800">--</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg p-1.5">
                            <span class="text-[9px] text-stone-400 font-bold uppercase block">ค่าเฉลี่ย</span>
                            <span class="text-[9px] font-black text-primary">--</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: สภาพอากาศ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200 mobile-hide">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="emojione-monotone--sun-behind-rain-cloud text-primary text-sm"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">สภาพอากาศ</h3>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center justify-between px-2 py-1 bg-stone-50 rounded-lg">
                            <span class="text-[9px] font-bold text-stone-600">สภาพ</span>
                            <span class="text-[9px] font-normal text-stone-700">--</span>
                        </div>
                        <div class="flex items-center justify-between px-2 py-1 bg-stone-50 rounded-lg">
                            <span class="text-[9px] font-bold text-stone-600">ฝน</span>
                            <span class="text-[9px] font-normal text-stone-700">--</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="px-3 sm:px-6 py-2 border-t border-stone-200 bg-white flex items-center gap-2 shrink-0">
        <div class="flex items-center gap-2 ml-auto">
            <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 2.0</span>
            <div class="h-3 w-px bg-stone-200"></div>
            <span class="text-[9px] font-bold text-primary uppercase">Smart Farm</span>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Chart initialization
        let doChart = null;
        let marketPriceChart = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadDoTrendData();
            loadMarketPriceTrend();

            setInterval(() => {
                loadDoTrendData();
                loadMarketPriceTrend();
            }, 30000);
        });

        // DO Trend Chart
        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');
            
            try {
                const res = await fetch('/dashboard/api/monitor_trend.php', { cache: 'no-store' });
                const data = await res.json();

                const doData = data.find(item => item.device_id === 2);

                if (!doData || !doData.points || doData.points.length === 0) {
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
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 9 },
                                color: '#78716c',
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: 10,
                            ticks: {
                                font: { size: 9 },
                                color: '#78716c',
                                callback: v => v.toFixed(1)
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        }
                    }
                }
            });
        }

        // Market Price Chart
        async function loadMarketPriceTrend() {
            const loading = document.getElementById('price-loading');

            try {
                const res = await fetch('/dashboard/api/market_price_Tred.php', { cache: 'no-store' });
                const raw = await res.json();

                if (!Array.isArray(raw) || raw.length === 0) return;

                const labels = raw.map(r => r.event_month);
                const price50 = raw.filter(r => r.data_table_id === "19").map(r => Number(r.event_price));
                const price70 = raw.filter(r => r.data_table_id === "20").map(r => Number(r.event_price));

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
                            callbacks: {
                                label: ctx => ` ${ctx.raw} บาท/กก.`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10 },
                                color: '#78716c'
                            }
                        },
                        y: {
                            ticks: {
                                font: { size: 10 },
                                color: '#78716c',
                                callback: v => v + ' ฿'
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>