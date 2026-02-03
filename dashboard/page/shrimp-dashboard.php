<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "key"   => "do",
        "title" => "ค่าออกซิเจนละลายน้ำ",
        "value" => "(DO)",
        "unit"  => "mg/L",
        "range" => "3.0-7.0 mg/L"
    ],
    [
        "key"   => "ph",
        "title" => "กรด - ด่าง",
        "value" => "(PH)",
        "unit"  => "pH",
        "range" => "7.0-8.5"
    ],
    [
        "key"   => "ec",
        "title" => "ความนำไฟฟ้า",
        "value" => "(EC)",
        "unit"  => "μS/cm",
        "range" => "23K-45K μS/cm"
    ],
    [
        "key"   => "temp",
        "title" => "อุณหภูมิ",
        "value" => "(Temp)",
        "unit"  => "°C",
        "range" => "28-32 °C"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>สถานะบ่อเลี้ยงกุ้ง - Dashboard</title>

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
            body {
                @apply bg-background-light text-[#1d130c] h-screen flex flex-col overflow-hidden;
                font-family: 'Inter', 'Noto Sans Thai', sans-serif;
            }
        }
        .emojione-monotone--shrimp {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='M36.532 24.117c8.79-.436 18.323-7.866 18.323-7.866l-5.197-1.033L60 8.633s-12.077 2.253-17.963 1.715a5.4 5.4 0 0 0-2.443-1.73a5.4 5.4 0 0 0-1.763-.295a5.43 5.43 0 0 0-4.706 2.743c-1.683-.604-3.727-.818-5.849-.907c-1.65-.058-3.372-.049-5.068-.162c-.419-.04-.842-.066-1.265-.12l-1.266-.217a47 47 0 0 1-2.493-.529c-1.619-.409-3.133-.958-4.437-1.645a91 91 0 0 1-.931-.539c-.302-.187-.56-.409-.832-.598c-.534-.38-1.006-.809-1.423-1.189c-.832-.773-1.399-1.552-1.498-2.191c-.124-.649.19-.972.153-.969c.04 0-.305.296-.221.978c.058.675.591 1.52 1.383 2.362c.404.418.842.868 1.367 1.298c.27.217.52.466.818.67l.928.628c1.301.795 2.833 1.462 4.47 1.986c.82.258 1.659.484 2.513.702l1.283.301c.438.083.884.141 1.33.208c1.763.226 3.487.319 5.098.462c1.486.152 2.873.335 4.051.661a6 6 0 0 0-.721.777c-2.656.426-6.668 1.407-8.902 3.583q-.12.117-.231.245c-.251.06-.491.126-.731.191a17 17 0 0 0-2.292-.892c-.867-.291-1.755-.42-2.598-.592c-.86-.079-1.691-.218-2.491-.205c-.396-.008-.787-.012-1.163-.019l-1.103.068c-.711.02-1.368.148-1.972.219c-1.2.199-2.169.407-2.827.61c-.656.187-1.027.291-1.027.291s.371-.078 1.042-.224c.668-.157 1.646-.304 2.844-.426c2.379-.202 5.756-.176 9.038 1.063q.61.222 1.202.502c-2.011.669-3.561 1.522-4.62 2.554c-.8.777-1.237 1.925-1.271 3.263c-1.982 1.111-3.371 2.316-4.13 3.591c-.383.646-.537 1.417-.464 2.249c-1.438 1.335-2.958 3.1-3.481 4.941c-.242.862-.126 1.816.321 2.739c-1.081 1.807-1.641 3.419-1.656 4.803c-.014.733.234 1.462.713 2.12c-.162 1.869-.15 4.574.887 6.436c.302.55.77.999 1.367 1.322c.277 2.514 1.26 5.793 3.994 8.999L12.271 62l1.438-1.407c.563-.55 1.27-1.573 1.603-2.881a5.6 5.6 0 0 0 1.906.092l1.536-.197l.112-1.558c.013-.156.055-1.017-.179-2.275a5.2 5.2 0 0 0 1.899-.706l1.648-.997l-1.018-1.648c-.096-.152-2.141-3.41-5.798-5.127c-.098-.815-.327-1.676-.683-2.572c.551-1.116.882-2.482.992-4.08c.785-.76 1.454-1.77 2.016-2.984c.99 1.322 2.431 2.726 4.449 3.594c.251.105.578.252.881-.16c.717.756 1.548 2.047 2.055 4.21c.226.972.158 1.591.89 1.559a.5.5 0 0 0 .188-.051c.286.637.63 1.647.751 3.07c0 0 .927-1.433.597-3.999c.073.007.133.002.218.02c1.13.216-.975-4.101-3.895-6.3c.347-.25.656-.171.416-.731c-.445-1.036-3.421-1.1-5.722-3.143q1.302-.833 2.474-2.282c.15 0 .309-.019.464-.028c1.253 1.327 3.226 2.888 5.922 3.442c.269.052.621.13.832-.337c.854.597 1.928 1.693 2.861 3.708c.419.907.475 1.526 1.185 1.35a.5.5 0 0 0 .173-.086c.408.565.948 1.487 1.354 2.855c0 0 .619-1.593-.221-4.04c.071-.008.13-.024.217-.025c1.15-.017-1.779-3.819-5.081-5.384c.285-.314.606-.299.26-.797c-.531-.763-2.618-.53-4.824-1.285c1.185-.453 2.396-1.147 3.624-2.094c1.478 1.039 3.649 2.13 6.313 2.168c.268.003.628.015.752-.483c.949.429 2.207 1.306 3.498 3.113c.579.811.748 1.406 1.414 1.104a.5.5 0 0 0 .154-.119c.508.48 1.206 1.286 1.859 2.553c0 0 .313-1.68-.965-3.927c.068-.021.121-.049.206-.065c1.127-.229-2.454-3.421-5.987-4.343c.221-.361.537-.405.104-.83c-.611-.603-2.369-.128-4.45-.31c1.779-.596 3.614-1.751 5.487-3.472c.111.001.223.008.336.002m-2.501-11.651a4.02 4.02 0 0 1 3.8-2.713c.433 0 .871.069 1.301.218a4.026 4.026 0 0 1 2.489 5.113a4.01 4.01 0 0 1-3.79 2.713a4.02 4.02 0 0 1-3.8-5.331M7.245 49.212c-.986-1.777-.714-4.938-.563-6.145q-.011-.01-.019-.02c.125.067 2.563 1.371 4.614.763c.599 1.501 1.222 4.569-2.583 6.262c-.662-.13-1.204-.421-1.449-.86m9.146.422c-.925.2-3.058-.215-3.058-.215c2.511 2.517 3.646 6.492 3.646 6.492c-3.438-1.345-4.648-3.632-4.648-3.632c.424 2.465.063 6.943.063 6.943c-2.817-3.309-3.563-6.669-3.631-9.134l-.041-.011c.409.021 4.634.164 5.054-3.021c1.655 1.132 2.615 2.578 2.615 2.578m6.362-27.196s1.016 2.182 3.125 3.234c-4.756 3.486-10.485-1.295-10.485-1.295c.876 2.398 1.982 3.813 3.186 4.567c-3.403 4.143-7.669.854-7.669.854c1.544 1.953 2.95 2.816 4.195 3.014c-.773 2.135-2.82 5.947-7.192 3.966c0 0 2.118 1.572 4.364 1.391c-.057 1.499-.688 5.216-5.62 4.873c-.478-.433-.777-.939-.768-1.423c.028-1.624 1.186-3.608 2.023-4.838c-.624-.73-.942-1.559-.757-2.211c.515-1.828 2.498-3.722 3.754-4.773c-.273-.741-.301-1.461-.004-1.959c.892-1.501 2.998-2.731 4.486-3.458c-.219-1.24-.035-2.385.604-3.004c1.358-1.321 3.687-2.136 5.674-2.621q.565.355 1.119.733c.76.514 1.519 1.028 2.265 1.53c.757.501 1.557.937 2.313 1.389c.623.361 1.283.735 1.999 1.025c-1.94.722-4.341.877-6.612-.994m9.709-1.481c-.228 1.145-1.213 1.457-2.27 1.12c-.674-.194-1.387-.549-2.17-.952c-.742-.396-1.521-.761-2.287-1.206c-.771-.461-1.529-.934-2.336-1.397c-.204-.122-.416-.238-.623-.357c.05-.061.095-.123.15-.177c2.207-2.153 7.004-2.963 8.685-3.188c.236-.408.506-.737.803-1.018c0 .813.163 1.623.526 2.375a5.4 5.4 0 0 0 3.122 2.773c.57.197 1.167.298 1.769.298a5.43 5.43 0 0 0 5.129-3.676a5.4 5.4 0 0 0 .063-3.319c3.577.562 11.115-1.385 11.115-1.385l-8.622 5.019l5.332 1.234s-14.39 9.581-19.846-.946c-.542 1.576-.146 3.524 1.46 4.802'/%3E%3Cpath fill='%23000' d='M37.202 15.6a1.923 1.923 0 0 0 2.445-1.195a1.937 1.937 0 0 0-1.194-2.453a1.928 1.928 0 0 0-1.251 3.648'/%3E%3C/svg%3E");
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

        .metric-range[data-key="do"] .fill {
            background: linear-gradient(90deg,
                #FF0000 0%,
                #F97316 25%,
                #EAB308 50%,
                #22C55E 75%,
                #16A34A 100%
            );
        }

        .metric-range[data-key="ph"] .fill {
            background: linear-gradient(90deg,
                #7F1D1D 0%,
                #DC2626 7.7%,
                #F97316 15.4%,
                #FB923C 23.1%,
                #F59E0B 30.8%,
                #FACC15 38.5%,
                #86EFAC 46.2%,
                #22C55E 53.8%,
                #06B6D4 61.5%,
                #3B82F6 69.2%,
                #1D4ED8 76.9%,
                #A855F7 84.6%,
                #581C87 100%
            );
        }

        .metric-range[data-key="ec"] .fill {
            background: linear-gradient(90deg,
                #3B82F6 0%,
                #60A5FA 22.86%,
                #22C55E 45.71%,
                #16A34A 68.57%,
                #F97316 100%
            );
        }

        .metric-range[data-key="temp"] .fill {
            background: linear-gradient(90deg,
                #3B82F6 0%,
                #60A5FA 30%,
                #22C55E 50%,
                #EAB308 70%,
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

        /* 4K Responsive Styles */
        @media (min-width: 2560px) {
            body {
                font-size: 20px;
            }
            
            .emojione-monotone--shrimp {
                width: 48px;
                height: 48px;
            }
            
            /* Header adjustments */
            header h1 {
                font-size: 2.5rem !important;
            }
            
            header p {
                font-size: 1rem !important;
            }
            
            /* Card titles */
            .metric-card-title {
                font-size: 1.25rem !important;
            }
            
            /* Values */
            .metric-value {
                font-size: 4rem !important;
            }
            
            .metric-unit {
                font-size: 2rem !important;
            }
            
            /* Status badges */
            .status {
                font-size: 1rem !important;
                padding: 0.75rem 1.5rem !important;
            }
            
            /* Chart titles */
            .chart-title {
                font-size: 1.5rem !important;
            }
            
            /* Sidebar text */
            .sidebar-title {
                font-size: 1.25rem !important;
            }
            
            .sidebar-value {
                font-size: 1.5rem !important;
            }
            
            /* Icons */
            .material-symbols-outlined {
                font-size: 3rem !important;
            }
            
            /* Range labels */
            .metric-range .bar {
                height: 0.75rem !important;
            }
            
            .metric-range .label-left,
            .metric-range .label-right {
                font-size: 1rem !important;
            }
            
            /* Footer */
            footer span {
                font-size: 1rem !important;
            }
        }
    </style>

</head>

<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 2xl:px-12 py-4 2xl:py-8 border-b border-stone-200 bg-white shrink-0 gap-3 sm:gap-0">
        <div class="flex items-center gap-4 2xl:gap-8 w-full sm:w-auto">
            <?php include 'navbar.php'; ?>
            <div class="size-12 2xl:size-20 bg-[#FFD7B6] rounded-xl 2xl:rounded-2xl flex items-center justify-center text-white shadow-sm shadow-primary/20 shrink-0">
                <span class="emojione-monotone--shrimp text-2xl 2xl:text-5xl text-[#ff8021]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-lg 2xl:text-4xl font-bold leading-tight text-[#1d130c] truncate">สถานะบ่อเลี้ยงกุ้ง (วันนี้)</h1>
                <p class="text-xs 2xl:text-xl text-stone-500 font-medium uppercase tracking-wider mt-1 2xl:mt-2">Dashboard</p>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-4 2xl:gap-10 w-full sm:w-auto flex-nowrap">
            <div class="flex items-center gap-2 2xl:gap-4 bg-stone-100 p-2 2xl:p-4 px-4 2xl:px-8 rounded-xl 2xl:rounded-2xl border border-stone-200 w-auto">
                <div class="flex items-center gap-3 2xl:gap-6 text-sm 2xl:text-2xl">
                    <span class="font-bold text-primary leading-none whitespace-nowrap" id="shrimp-age">
                        อายุ: -- วัน
                    </span>
                    <div class="w-px h-4 2xl:h-8 bg-stone-300"></div>
                    <span class="text-xs 2xl:text-xl text-stone-600 font-bold leading-none whitespace-nowrap" id="start-date">
                        -- --- ----
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-6 2xl:gap-10 w-auto">
                <div class="flex flex-col items-end border-l border-stone-200 pl-6 2xl:pl-12">
                    <span class="text-xs 2xl:text-lg font-bold text-stone-400 uppercase tracking-widest leading-none mb-1 2xl:mb-2 whitespace-nowrap">
                        อัปเดตล่าสุด
                    </span>
                    <span class="text-sm 2xl:text-2xl font-bold text-stone-800 whitespace-nowrap" id="last-update">
                        <?php echo $currentTime; ?>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4 2xl:p-8 overflow-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 2xl:gap-8">

            <!-- LEFT SECTION (Main Content) -->
            <div class="lg:col-span-10 flex flex-col gap-4 2xl:gap-8">

                <!-- TOP ROW: รูปภาพ + กราฟ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-10
            2xl:grid-rows-[1fr_1fr]
            2xl:min-h-[1450px]
            gap-4 2xl:gap-10">

                    <!-- รูปภาพ -->
                    <div class="sm:col-span-2 lg:col-span-4 lg:row-span-2 bg-white rounded-2xl 2xl:rounded-3xl
                        border border-stone-200 shadow-sm p-6 2xl:p-12
                        flex items-center justify-center
                        min-h-[200px] lg:min-h-0
                        hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 5rem;">image</span>
                            <p class="text-base 2xl:text-2xl mt-4 2xl:mt-6 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-sm 2xl:text-xl mt-2 2xl:mt-3 text-stone-400">เครื่องมือ/อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- กราฟ DO -->
                    <div class="sm:col-span-2 lg:col-span-6 lg:row-span-1
                                bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl
                                p-4 2xl:p-8 shadow-sm
                                flex flex-col
                                min-h-0
                                hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 2xl:mb-6 gap-2 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h2 class="chart-title text-sm 2xl:text-2xl font-bold text-stone-800 flex items-center gap-2 2xl:gap-4">
                                    <span class="w-1 2xl:w-2 h-4 2xl:h-8 bg-orange-500 rounded-full"></span>
                                    <span class="truncate">แนวโน้มค่าออกซิเจนละลายน้ำ</span>
                                </h2>
                                <p class="text-xs 2xl:text-lg text-stone-400 font-medium uppercase tracking-wider mt-1 2xl:mt-2">
                                    Historical DO Data (24H)
                                </p>
                            </div>
                            <div class="flex items-center gap-1 2xl:gap-2 bg-stone-100 p-1 2xl:p-2 rounded-lg 2xl:rounded-xl border border-stone-200 shrink-0">
                                <button id="btnDoDay" class="px-3 2xl:px-6 py-1 2xl:py-3 text-xs 2xl:text-lg font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                                <button id="btnDoMonth" class="px-3 2xl:px-6 py-1 2xl:py-3 text-xs 2xl:text-lg font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-200 rounded-md min-h-[150px] 2xl:min-h-[350px]">
                            <div id="do-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="flex gap-2 2xl:gap-3">
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                </div>
                            </div>
                            <canvas id="doTrendChart" class="absolute inset-0"></canvas>
                        </div>
                    </div>

                    <!-- กราฟ Price -->
                    <div class="sm:col-span-2 lg:col-span-6 lg:row-span-1
                                bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl
                                p-4 2xl:p-8 shadow-sm
                                flex flex-col
                                min-h-0
                                hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="flex justify-between items-center mb-3 2xl:mb-6 shrink-0">
                            <div>
                                <h2 class="chart-title text-sm 2xl:text-2xl font-bold text-stone-800 flex items-center gap-2 2xl:gap-4">
                                    <span class="w-1 2xl:w-2 h-4 2xl:h-8 bg-primary rounded-full"></span>
                                    <span class="truncate">แนวโน้มราคาตลาด</span>
                                </h2>
                                <p class="text-xs 2xl:text-lg text-stone-400 font-medium uppercase tracking-wider mt-1 2xl:mt-2">Market Price Trend</p>
                            </div>
                        </div>
                        <div class="flex-1 relative border-l border-b border-stone-100 bg-white min-h-[150px] 2xl:min-h-[350px]">
                            <div id="price-loading" class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="flex gap-2 2xl:gap-3">
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                    <span class="size-2 2xl:size-4 rounded-full bg-stone-300 loading-dot"></span>
                                </div>
                            </div>
                            <canvas id="marketPriceChart" class="absolute inset-0"></canvas>
                        </div>
                    </div>

                </div>

                <!-- BOTTOM ROW: Sensor Metrics -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 2xl:gap-8" id="metrics-cards">
                    <!-- DO Card -->
                    <div class="bg-white rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-do">
                        <div class="flex justify-between items-center mb-3 2xl:mb-6">
                            <span class="metric-card-title text-sm 2xl:text-xl font-bold text-stone-500 uppercase tracking-widest">ค่า DO</span>
                            <span class="status px-2 2xl:px-4 py-1 2xl:py-2 rounded-full bg-stone-100 text-stone-500 text-xs 2xl:text-lg font-bold uppercase">--</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center py-4 2xl:py-8">
                            <div class="flex items-baseline gap-2 2xl:gap-4">
                                <span class="metric-value text-2xl 2xl:text-6xl font-black text-black value">--</span>
                                <span class="metric-unit text-lg 2xl:text-3xl font-bold text-stone-400">mg/L</span>
                            </div>
                        </div>
                        <div class="metric-range mt-3 2xl:mt-6 hidden" data-key="do">
                            <div class="flex justify-between text-xs 2xl:text-lg font-bold leading-none mb-2 2xl:mb-3">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-2 2xl:h-4 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-xs 2xl:text-base text-stone-500 font-medium mt-3 2xl:mt-4 text-center">ควรอยู่ระหว่าง 3.0-7.0 mg/L</p>
                        </div>
                    </div>

                    <!-- PH Card -->
                    <div class="bg-white rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-ph">
                        <div class="flex justify-between items-center mb-3 2xl:mb-6">
                            <span class="metric-card-title text-sm 2xl:text-xl font-bold text-stone-500 uppercase tracking-widest">กรด-ด่าง</span>
                            <span class="status px-2 2xl:px-4 py-1 2xl:py-2 rounded-full bg-stone-100 text-stone-500 text-xs 2xl:text-lg font-bold uppercase">--</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center py-4 2xl:py-8">
                            <div class="flex items-baseline gap-2 2xl:gap-4">
                                <span class="metric-value text-2xl 2xl:text-6xl font-black text-black value">--</span>
                                <span class="metric-unit text-lg 2xl:text-3xl font-bold text-stone-400">pH</span>
                            </div>
                        </div>
                        <div class="metric-range mt-3 2xl:mt-6 hidden" data-key="ph">
                            <div class="flex justify-between text-xs 2xl:text-lg font-bold leading-none mb-2 2xl:mb-3">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-2 2xl:h-4 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-xs 2xl:text-base text-stone-500 font-medium mt-3 2xl:mt-4 text-center">ควรอยู่ระหว่าง 7.0-8.5</p>
                        </div>
                    </div>

                    <!-- EC Card -->
                    <div class="bg-white rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-ec">
                        <div class="flex justify-between items-center mb-3 2xl:mb-6">
                            <span class="metric-card-title text-sm 2xl:text-xl font-bold text-stone-500 uppercase tracking-widest">ความนำไฟฟ้า</span>
                            <span class="status px-2 2xl:px-4 py-1 2xl:py-2 rounded-full bg-stone-100 text-stone-500 text-xs 2xl:text-lg font-bold uppercase">--</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center py-4 2xl:py-8">
                            <div class="flex items-baseline gap-2 2xl:gap-4">
                                <span class="metric-value text-2xl 2xl:text-6xl font-black text-black value">--</span>
                                <span class="metric-unit text-base 2xl:text-3xl font-bold text-stone-400">μS/cm</span>
                            </div>
                        </div>
                        <div class="metric-range mt-3 2xl:mt-6 hidden" data-key="ec">
                            <div class="flex justify-between text-xs 2xl:text-lg font-bold leading-none mb-2 2xl:mb-3">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-2 2xl:h-4 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-xs 2xl:text-base text-stone-500 font-medium mt-3 2xl:mt-4 text-center">ควรอยู่ระหว่าง 23K-45K μS/cm</p>
                        </div>
                    </div>

                    <!-- Temp Card -->
                    <div class="bg-white rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-temp">
                        <div class="flex justify-between items-center mb-3 2xl:mb-6">
                            <span class="metric-card-title text-sm 2xl:text-xl font-bold text-stone-500 uppercase tracking-widest">อุณหภูมิ</span>
                            <span class="status px-2 2xl:px-4 py-1 2xl:py-2 rounded-full bg-stone-100 text-stone-500 text-xs 2xl:text-lg font-bold uppercase">--</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center py-4 2xl:py-8">
                            <div class="flex items-baseline gap-2 2xl:gap-4">
                                <span class="metric-value text-2xl 2xl:text-6xl font-black text-black value">--</span>
                                <span class="metric-unit text-lg 2xl:text-3xl font-bold text-stone-400">°C</span>
                            </div>
                        </div>
                        <div class="metric-range mt-3 2xl:mt-6 hidden" data-key="temp">
                            <div class="flex justify-between text-xs 2xl:text-lg font-bold leading-none mb-2 2xl:mb-3">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-2 2xl:h-4 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-xs 2xl:text-base text-stone-500 font-medium mt-3 2xl:mt-4 text-center">ควรอยู่ระหว่าง 28-32 °C</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT SECTION (Sidebar Cards) -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 2xl:gap-8">

                <!-- Card 1: การให้อาหารวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 2xl:gap-4 mb-3 2xl:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg 2xl:text-4xl">restaurant</span>
                        <h3 class="sidebar-title text-sm 2xl:text-xl font-bold text-stone-700">การให้อาหารวันนี้</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 2xl:gap-4" id="feeding-info">
                        <div class="bg-stone-50 rounded-lg 2xl:rounded-xl p-2 2xl:p-4">
                            <span class="text-xs 2xl:text-base text-stone-400 font-bold uppercase block">จำนวนมื้อ</span>
                            <span class="sidebar-value text-sm 2xl:text-2xl font-black text-stone-800" id="feeding-meals">--</span>
                        </div>
                        <div class="bg-stone-50 rounded-lg 2xl:rounded-xl p-2 2xl:p-4">
                            <span class="text-xs 2xl:text-base text-stone-400 font-bold uppercase block">เพิ่มต่อมื้อ</span>
                            <span class="sidebar-value text-xs 2xl:text-2xl font-black text-primary" id="feeding-increase">--</span>
                        </div>
                        <div class="col-span-2 bg-primary/5 rounded-lg 2xl:rounded-xl p-2 2xl:p-4 border border-primary/10 flex justify-between items-center">
                            <span class="text-xs 2xl:text-base text-primary font-bold uppercase">ปริมาณรวม</span>
                            <span class="sidebar-value text-xs 2xl:text-2xl font-black text-primary" id="feeding-total">--</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: ต้นทุนทรัพยากร -->
                <div class="bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 2xl:gap-4 mb-3 2xl:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg 2xl:text-4xl">analytics</span>
                        <h3 class="sidebar-title text-sm 2xl:text-xl font-bold text-stone-700">ต้นทุนทรัพยากร</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 2xl:gap-4" id="resource-info">
                        <div class="col-span-2 flex items-center justify-center py-4 2xl:py-8">
                            <span class="text-sm 2xl:text-xl text-stone-400">กำลังโหลด...</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: คุณภาพน้ำ -->
                <div class="bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 2xl:gap-4 mb-3 2xl:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg 2xl:text-4xl">waves</span>
                        <h3 class="sidebar-title text-sm 2xl:text-xl font-bold text-stone-700">คุณภาพน้ำที่เหมาะสม</h3>
                    </div>
                    <div>
                        <table class="w-full">
                            <tbody class="text-sm 2xl:text-xl divide-y divide-stone-50">
                                <tr>
                                    <td class="py-1 2xl:py-3 text-stone-500 font-medium">DO</td>
                                    <td class="py-1 2xl:py-3 text-right font-bold text-success">3.0-7.0 mg/L</td>
                                </tr>
                                <tr>
                                    <td class="py-1 2xl:py-3 text-stone-500 font-medium">pH</td>
                                    <td class="py-1 2xl:py-3 text-right font-bold text-stone-700">7.5 - 8.5</td>
                                </tr>
                                <tr>
                                    <td class="py-1 2xl:py-3 text-stone-500 font-medium">EC</td>
                                    <td class="py-1 2xl:py-3 text-right font-bold text-stone-700">23K-45K μS/cm</td>
                                </tr>
                                <tr>
                                    <td class="py-1 2xl:py-3 text-stone-500 font-medium">Temp</td>
                                    <td class="py-1 2xl:py-3 text-right font-bold text-stone-700">28-32 °C</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card 4: การปรับอาหาร -->
                <div class="bg-white border border-stone-200 rounded-2xl 2xl:rounded-3xl p-4 2xl:p-8 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 2xl:gap-4 mb-3 2xl:mb-6">
                        <span class="material-symbols-outlined text-primary text-lg 2xl:text-4xl">rule</span>
                        <h3 class="sidebar-title text-sm 2xl:text-xl font-bold text-stone-700">การปรับอาหาร</h3>
                    </div>
                    <div class="flex flex-col gap-2 2xl:gap-4">
                        <div class="flex items-center justify-between px-3 2xl:px-6 py-2 2xl:py-4 bg-success/5 border border-success/10 rounded-lg 2xl:rounded-xl">
                            <span class="text-sm 2xl:text-xl font-bold text-success uppercase">หมดเกลี้ยง</span>
                            <span class="text-sm 2xl:text-xl font-normal text-stone-700">+5 ถึง +10%</span>
                        </div>
                        <div class="flex items-center justify-between px-3 2xl:px-6 py-2 2xl:py-4 bg-warning/5 border border-warning/10 rounded-lg 2xl:rounded-xl">
                            <span class="text-sm 2xl:text-xl font-bold text-warning uppercase">เหลือเล็กน้อย</span>
                            <span class="text-sm 2xl:text-xl font-normal text-stone-700">คงที่ / -5%</span>
                        </div>
                        <div class="flex items-center justify-between px-3 2xl:px-6 py-2 2xl:py-4 bg-danger/5 border border-danger/10 rounded-lg 2xl:rounded-xl">
                            <span class="text-sm 2xl:text-xl font-bold text-danger uppercase">เหลือเยอะ</span>
                            <span class="text-sm 2xl:text-xl font-normal text-stone-700">งด / -50%</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="px-6 2xl:px-12 py-3 2xl:py-6 border-t border-stone-200 bg-white flex items-center gap-3 2xl:gap-6 shrink-0">
        <div class="flex items-center gap-3 2xl:gap-6 ml-auto">
            <span class="text-sm 2xl:text-xl font-bold text-stone-300 uppercase tracking-widest">Version 1.0</span>
            <div class="h-4 2xl:h-8 w-px bg-stone-200"></div>
            <span class="text-sm 2xl:text-xl font-bold text-primary uppercase">Smart Farm</span>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const today = new Date();
        const formattedDate = `${today.getDate()} ${thaiMonths[today.getMonth()]} ${today.getFullYear() + 543}`;
        document.getElementById('start-date').textContent = formattedDate;

        document.addEventListener('DOMContentLoaded', () => {
            loadSensorData();
            loadFeedingInfo();
            loadResourceToday();
            loadDoTrendData();

            setInterval(() => {
                loadSensorData();
                loadFeedingInfo();
                loadResourceToday();
                loadDoTrendData();
            }, 30000);
        });

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

                setCardValue('do', sensor.do);
                setCardValue('ph', sensor.ph);
                setCardValue('ec', sensor.ec);
                setCardValue('temp', sensor.temp);
                setCardValue('nh3', sensor.nh3 ?? null);

            } catch (err) {
                console.error('❌ loadSensorData error:', err);
            }
        }

        function setBadge(statusEl, text, type) {
            const map = {
                success: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-green-100 text-green-600 text-xs 2xl:text-lg font-bold uppercase',
                warning: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-yellow-100 text-yellow-700 text-xs 2xl:text-lg font-bold uppercase',
                danger: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-red-100 text-red-600 text-xs 2xl:text-lg font-bold uppercase',
                info: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-blue-100 text-blue-700 text-xs 2xl:text-lg font-bold uppercase',
                na: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-stone-100 text-stone-500 text-xs 2xl:text-lg font-bold uppercase',
                orange: 'status px-2 2xl:px-4 py-0.5 2xl:py-2 rounded-full bg-orange-100 text-orange-700 text-xs 2xl:text-lg font-bold uppercase',
            };

            statusEl.textContent = text;
            statusEl.className = map[type] || map.na;
        }

        function getStatusByKey(key, v) {
            if (key === 'do') {
                if (v >= 5.0 && v <= 7.0) return {text: 'ดีที่สุด', type: 'success'};
                if (v > 4.0) return {text: 'เหมาะสม', type: 'success'};
                if (v >= 3.0 && v <= 4.0) return {text: 'ไม่ดี', type: 'warning'};
                return {text: 'อันตราย', type: 'danger'};
            }

            if (key === 'ph') {
                if (v >= 7.0 && v <= 8.5) return {text: 'เหมาะสม', type: 'success'};
                if (v < 6.5) return {text: 'หายใจลำบาก', type: 'orange'};
                if (v > 9.0) return {text: 'อันตราย', type: 'danger'};
                return {text: 'ควรเฝ้าดู', type: 'warning'};
            }

            if (key === 'ec') {
                if (v >= 23000 && v <= 45000) return {text: 'เหมาะสม', type: 'success'};
                if (v < 23000) return {text: 'ต่ำ', type: 'info'};
                return {text: 'สูง', type: 'danger'};
            }

            if (key === 'temp') {
                if (v >= 28 && v <= 32) return {text: 'เหมาะสม', type: 'success'};
                if (v < 28) return {text: 'ต่ำ', type: 'info'};
                return {text: 'สูง', type: 'danger'};
            }

            return {text: 'N/A', type: 'na'};
        }

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

        const BAR_CONFIG = {
            do: {min: 0, max: 7, leftLabel: '3.0', rightLabel: '7.0', leftColor: '#C73434', rightColor: '#198754'},
            ph: {min: 1, max: 14, leftLabel: '1', rightLabel: '14', leftColor: '#7F1D1D', rightColor: '#581C87'},
            ec: {min: 15000, max: 50000, leftLabel: '23K', rightLabel: '45K', leftColor: '#3B82F6', rightColor: '#198754'},
            temp: {min: 25, max: 35, leftLabel: '28°C', rightLabel: '32°C', leftColor: '#3B82F6', rightColor: '#F97316'},
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

        async function loadFeedingInfo() {
            const mealsEl = document.getElementById('feeding-meals');
            const increaseEl = document.getElementById('feeding-increase');
            const totalEl = document.getElementById('feeding-total');

            if (!mealsEl || !increaseEl || !totalEl) return;

            mealsEl.textContent = '--';
            increaseEl.textContent = '--';
            totalEl.textContent = '--';

            try {
                const res = await fetch('/dashboard/api/food_preparation.php', {cache: 'no-store'});
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const rows = await res.json();

                if (!Array.isArray(rows) || rows.length === 0) {
                    throw new Error('ไม่มีข้อมูล');
                }

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
                    <span class="text-sm 2xl:text-xl text-stone-400">กำลังโหลด...</span>
                </div>
            `;

            try {
                const res = await fetch("/dashboard/api/resource_to_day.php", {cache: "no-store"});
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();

                const item = Array.isArray(data) ? 
                    data.find(x => String(x.id) === "1") : 
                    (String(data?.id) === "1" ? data : null);

                if (!item) {
                    box.innerHTML = `
                        <div class="w-full shrink-0 snap-start flex items-center justify-center">
                            <span class="text-sm 2xl:text-xl text-stone-400">ไม่พบข้อมูล id=1</span>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="w-full h-full shrink-0 snap-start">
                        <div class="w-full h-full">
                            ${renderResourceCard(item)}
                        </div>
                    </div>
                `;

            } catch (err) {
                console.error("❌ loadResourceToday error:", err);
                box.innerHTML = `
                    <div class="w-full shrink-0 snap-start flex items-center justify-center">
                        <span class="text-sm 2xl:text-xl text-red-500 font-semibold">โหลดข้อมูลไม่สำเร็จ</span>
                    </div>
                `;
            }
        }

        function stopResourceAutoSlide() {
            const box = document.getElementById("resource-info");
            if (box) box.removeEventListener("scroll", onResourceScroll);

            if (resourceTimer) {
                clearInterval(resourceTimer);
                resourceTimer = null;
            }
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
                    <div class="grid grid-cols-2 gap-2 2xl:gap-4">
                        <div class="rounded-lg 2xl:rounded-xl bg-gray-100 border border-stone-200 p-3 2xl:p-6">
                            <div class="text-sm 2xl:text-xl text-stone-500 font-semibold text-center">ค่าน้ำประปา</div>
                            <div class="text-lg 2xl:text-3xl text-center font-extrabold ${water === 0 ? "text-stone-400" : "text-stone-800"}">
                                ${formatNumber(water)} <span class="text-sm 2xl:text-xl font-bold text-stone-500">${escapeHtml(waterUnit)}</span>
                            </div>
                        </div>

                        <div class="rounded-lg 2xl:rounded-xl bg-gray-100 border border-stone-200 p-3 2xl:p-6">
                            <div class="text-sm 2xl:text-xl text-stone-500 font-semibold text-center">ค่าไฟฟ้า</div>
                            <div class="text-lg 2xl:text-3xl text-center font-extrabold ${elec === 0 ? "text-stone-400" : "text-stone-800"}">
                                ${formatNumber(elec)} <span class="text-sm 2xl:text-xl font-bold text-stone-500">${escapeHtml(elecUnit)}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 2xl:mt-4 text-xs 2xl:text-base text-stone-400 font-semibold text-end">
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

        let doChart = null;

        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');

            try {
                const res = await fetch('/dashboard/api/monitor_trend.php', {cache: 'no-store'});
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

            if (doChart) {
                doChart.destroy();
            }

            // ปรับขนาด font ตามขนาดหน้าจอ
            const isFourK = window.innerWidth >= 2560;
            const fontSize = isFourK ? 18 : 9;

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
                        pointRadius: isFourK ? 4 : 2,
                        pointBackgroundColor: '#ff8021'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {display: false},
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L`
                            },
                            titleFont: {size: fontSize},
                            bodyFont: {size: fontSize}
                        }
                    },
                    scales: {
                        x: {
                            grid: {display: false},
                            ticks: {
                                font: {size: fontSize},
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
                                font: {size: fontSize},
                                color: '#78716c',
                                callback: v => v.toFixed(1)
                            },
                            grid: {color: 'rgba(0,0,0,0.05)'}
                        }
                    }
                }
            });
        }

        async function loadMarketPriceTrend() {
            const loading = document.getElementById('price-loading');

            try {
                const res = await fetch('/dashboard/api/market_price_Tred.php', {cache: 'no-store'});
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

        let marketPriceChart;

        function renderMarketPriceChart(labels, price50, price70) {
            const ctx = document.getElementById('marketPriceChart');
            if (!ctx) return;

            if (marketPriceChart) {
                marketPriceChart.destroy();
            }

            const isFourK = window.innerWidth >= 2560;
            const fontSize = isFourK ? 18 : 10;

            marketPriceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: '50 ตัว/กก.',
                        data: price50,
                        borderColor: '#ff8021',
                        backgroundColor: 'rgba(255,128,33,0.15)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: isFourK ? 5 : 2.5
                    },
                    {
                        label: '70 ตัว/กก.',
                        data: price70,
                        borderColor: 'rgba(255,128,33,0.4)',
                        backgroundColor: 'rgba(255,128,33,0.08)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: isFourK ? 4 : 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {display: false},
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.raw} บาท/กก.`
                            },
                            titleFont: {size: fontSize},
                            bodyFont: {size: fontSize}
                        }
                    },
                    scales: {
                        x: {
                            grid: {display: false},
                            ticks: {
                                font: {size: fontSize},
                                color: '#78716c'
                            }
                        },
                        y: {
                            ticks: {
                                font: {size: fontSize},
                                color: '#78716c',
                                callback: v => v + ' ฿'
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            initMetricBars();
            loadMarketPriceTrend();
        });
    </script>
</body>

</html>