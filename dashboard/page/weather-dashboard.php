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
        "value" => "(Air Quality)",
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
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath fill='%23000' d='M56.511 14.725a11 11 0 0 0-.496-.267v-.078C56.015 7.555 50.43 2 43.566 2c-4.27 0-8.23 2.183-10.508 5.742l-.266-.041a12 12 0 0 0-1.758-.134c-3.623 0-6.977 1.614-9.266 4.224A8.4 8.4 0 0 0 11.599 20a8.36 8.36 0 0 0 2.929 6.352c.075.953.298 1.871.642 2.733a19 19 0 0 1-2.313-1.944a19.5 19.5 0 0 1-2.16-2.6l-3.426 8.187l8.019-3.355a9.4 9.4 0 0 0 2.585 3.445L20.002 38l1.367-3.335a9.5 9.5 0 0 0 2.627.379h27.055c.817 0 1.646-.095 2.466-.285C58.51 33.619 62 29.258 62 24.154c0-3.875-2.104-7.488-5.489-9.429M52.8 31.692a7.6 7.6 0 0 1-1.747.205H23.996a6.3 6.3 0 0 1-1.984-.322c-2.521-.827-4.344-3.188-4.344-5.973c0-1.894.843-3.587 2.171-4.739a6.3 6.3 0 0 1 2.073-1.2a6.4 6.4 0 0 1 2.084-.357c2.041 0 3.851.966 5.01 2.457l.057-.001a8.68 8.68 0 0 0-6.797-4.473a9.13 9.13 0 0 1 8.77-6.576c.445 0 .879.043 1.305.104a9.2 9.2 0 0 1 2.407.686a9.09 9.09 0 0 1 5.314 6.984l.002-.054c0-3.563-1.837-6.696-4.618-8.524a9.29 9.29 0 0 1 8.121-4.762c5.126 0 9.281 4.134 9.281 9.232c0 .248-.017.489-.035.732a9.72 9.72 0 0 0-5.629 2.336a7.78 7.78 0 0 1 5.392-.881c.842.167 1.633.47 2.352.884c2.333 1.338 3.906 3.835 3.906 6.703c0 3.676-2.578 6.751-6.034 7.539M33.008 47.575c.674-1.85.719-4.382.262-7.316c-2.201 1.924-3.832 3.922-4.504 5.771c-.572 1.569-.086 3.189 1.086 3.616s2.586-.501 3.156-2.071m8.855 0c.672-1.85.719-4.382.262-7.316c-2.203 1.924-3.832 3.922-4.506 5.771c-.57 1.569-.086 3.189 1.086 3.616s2.586-.501 3.158-2.071m4.611-1.545c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.203 1.924-3.833 3.922-4.505 5.771m-22.32 1.545c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771c-.571 1.569-.086 3.189 1.086 3.616s2.586-.501 3.157-2.071M21.815 58.28c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771m8.855 0c-.571 1.569-.086 3.189 1.086 3.616s2.586-.502 3.157-2.071c.673-1.85.719-4.382.262-7.316c-2.202 1.924-3.832 3.922-4.505 5.771m8.853 0c-.57 1.569-.086 3.189 1.086 3.616s2.586-.502 3.158-2.071c.672-1.85.719-4.382.262-7.316c-2.203 1.924-3.832 3.922-4.506 5.771m-26.563 0c-.57 1.569-.086 3.189 1.086 3.616s2.586-.502 3.159-2.071c.672-1.85.719-4.382.262-7.316c-2.204 1.924-3.833 3.922-4.507 5.771' stroke-width='1.5' stroke='%23000'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
            }

        /* ========== RESPONSIVE FOR 75" TV (4K - 3840x2160) ========== */
        @media (min-width: 3840px) {
            body {
                font-size: 28px;
            }
            
            .arcticons--weathercan {
                width: 50px !important;
                height: 50px !important;
            }
            
            /* Header scaling */
            header h1 {
                font-size: 3rem !important;
            }
            
            header p {
                font-size: 1.25rem !important;
            }
            
            header .text-sm {
                font-size: 1.75rem !important;
            }
            
            header .text-xs {
                font-size: 1.5rem !important;
            }
            
            header .text-\[10px\] {
                font-size: 1.25rem !important;
            }
            
            header .size-9 {
                width: 5rem !important;
                height: 5rem !important;
            }
            
            /* Metric cards */
            .metric-card-value {
                font-size: 4rem !important;
            }
            
            #metrics-cards .text-sm {
                font-size: 2.5rem !important;
            }
            
            #metrics-cards .text-\[9px\] {
                font-size: 1.5rem !important;
            }
            
            #metrics-cards .text-\[8px\] {
                font-size: 1.25rem !important;
            }
            
            #metrics-cards .text-\[7px\] {
                font-size: 1rem !important;
            }
            
            #metrics-cards .text-\[6px\] {
                font-size: 0.875rem !important;
            }
            
            #metrics-cards > div {
                height: 18rem !important;
            }
            
            /* Chart titles and text */
            .text-\[11px\] {
                font-size: 1.75rem !important;
            }
            
            .text-\[10px\] {
                font-size: 1.5rem !important;
            }
            
            .text-\[9px\] {
                font-size: 1.25rem !important;
            }
            
            .text-\[8px\] {
                font-size: 1rem !important;
            }
            
            .text-\[7px\] {
                font-size: 0.875rem !important;
            }
            
            /* Material icons */
            .material-symbols-outlined {
                font-size: 3rem !important;
            }
            
            /* Icon sizes */
            .ion--water-sharp,
            .mdi--lightning-bolt,
            .mdi--temperature-celsius,
            .carbon--temperature-hot,
            .solar--wind-bold-duotone,
            .wi--humidity,
            .emojione-monotone--sun-behind-rain-cloud {
                width: 40px !important;
                height: 40px !important;
            }
            
            /* Right sidebar cards */
            .col-span-2 h3 {
                font-size: 1.75rem !important;
            }
            
            /* Buttons and badges */
            button {
                font-size: 1.25rem !important;
                padding: 0.75rem 1.5rem !important;
            }
            
            .status {
                font-size: 1.5rem !important;
                padding: 0.75rem 1.5rem !important;
            }
            
            /* Border radius scaling */
            .rounded-2xl {
                border-radius: 2rem !important;
            }
            
            .rounded-xl {
                border-radius: 1.5rem !important;
            }
            
            .rounded-lg {
                border-radius: 1rem !important;
            }
            
            /* Spacing */
            .gap-4 {
                gap: 2rem !important;
            }
            
            .gap-3 {
                gap: 1.5rem !important;
            }
            
            .gap-2 {
                gap: 1rem !important;
            }
            
            .p-4 {
                padding: 2rem !important;
            }
            
            .p-3 {
                padding: 1.5rem !important;
            }
            
            .px-6 {
                padding-left: 3rem !important;
                padding-right: 3rem !important;
            }
            
            .py-3 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }
            
            /* Loading dots */
            .loading-dot {
                width: 1rem !important;
                height: 1rem !important;
            }
            
            /* Range bar */
            .metric-range .bar {
                height: 0.75rem !important;
            }
            
            /* Footer */
            footer {
                padding: 1.5rem 3rem !important;
            }
        }

        /* ========== RESPONSIVE FOR LARGE DISPLAYS (1920x1080 - 2560x1440) ========== */
        @media (min-width: 1920px) and (max-width: 3839px) {
            body {
                font-size: 18px;
            }
            
            .arcticons--weathercan {
                width: 35px !important;
                height: 35px !important;
            }
            
            header h1 {
                font-size: 2rem !important;
            }
            
            header .text-\[10px\] {
                font-size: 0.875rem !important;
            }
            
            header .size-9 {
                width: 3.5rem !important;
                height: 3.5rem !important;
            }
            
            #metrics-cards .text-sm {
                font-size: 1.75rem !important;
            }
            
            #metrics-cards .text-\[9px\] {
                font-size: 1rem !important;
            }
            
            #metrics-cards > div {
                height: 12rem !important;
            }
            
            .text-\[11px\] {
                font-size: 1.125rem !important;
            }
            
            .text-\[10px\] {
                font-size: 1rem !important;
            }
            
            .text-\[9px\] {
                font-size: 0.875rem !important;
            }
            
            .material-symbols-outlined {
                font-size: 2rem !important;
            }
            
            .ion--water-sharp,
            .mdi--lightning-bolt,
            .mdi--temperature-celsius,
            .carbon--temperature-hot,
            .solar--wind-bold-duotone,
            .wi--humidity,
            .emojione-monotone--sun-behind-rain-cloud {
                width: 30px !important;
                height: 30px !important;
            }
            
            .status {
                font-size: 1rem !important;
            }
        }

    </style>

</head>
<body>

    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-stone-200 bg-white shrink-0">
        <div class="flex items-center gap-4">
          <?php include 'navbar.php'; ?>
            <div class="size-9 bg-[#FFD7B6] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20">
                <span class="arcticons--weathercan text-2xl text-[#ff8021]"> </span>
            </div>
            <div>        
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Weather Dashboard</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Weather Dashboard Farm Intelligence</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex flex-col items-end border-l border-stone-200 pl-6">
                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest leading-none mb-1">อัปเดตล่าสุด</span>
                <span class="text-sm font-bold text-stone-800" id="last-update"><?php echo $currentTime; ?></span>
            </div>
        </div>
    </header>

<!-- Main Content -->
<main class="flex-1 flex flex-col p-4 gap-4 overflow-hidden">
  <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
  <div class="grid grid-cols-12 gap-4 h-full">
    
    <!-- ========== LEFT SECTION (10 columns) ========== -->
    <div class="col-span-10 flex flex-col gap-4">
      
      <!-- TOP ROW: รูปภาพ + กราฟ (4 columns) -->
      <div class="grid grid-cols-4 gap-4 flex-1 min-h-0">
        
        <!-- รูปภาพ 1 ส่วน (ซ้ายสุด) -->
        <div class="col-span-1 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
          <div class="text-center text-stone-400">
            <span class="material-symbols-outlined" style="font-size: 5rem;">image</span>
            <p class="text-xs mt-2 font-medium">รูปภาพ 1</p>
            <p class="text-[10px] mt-1 text-stone-400">เครื่องมือ/อุปกรณ์</p>
          </div>
        </div>

        <!-- รูปภาพ 2 ส่วน (กลาง) -->
        <div class="col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
          <div class="text-center text-stone-400">
            <span class="material-symbols-outlined" style="font-size: 5rem;">image</span>
            <p class="text-xs mt-2 font-medium">รูปภาพ 2</p>
            <p class="text-[10px] mt-1 text-stone-400">พื้นที่ขยาย</p>
          </div>
        </div>

        <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
        <div class="col-span-1 flex flex-col gap-4 min-h-0">
          <!-- กราฟที่ 1: DO Trend Chart -->
          <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
            <div class="flex justify-between items-center mb-2 shrink-0">
              <div>
                <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                  <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                  แนวโน้มค่า DO
                </h2>
                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                  24H Data
                </p>
              </div>
              <div class="flex items-center gap-1 bg-stone-100 p-0.5 rounded-lg border border-stone-200">
                <button id="btnDoDay" class="px-2 py-0.5 text-[8px] font-bold rounded-md bg-white shadow-sm text-orange-600" type="button">1 วัน</button>
                <button id="btnDoMonth" class="px-2 py-0.5 text-[8px] font-bold rounded-md text-stone-500 hover:bg-white/50" type="button">1 เดือน</button>
              </div>
            </div>
            <div class="flex-1 min-h-0 relative border-l border-b border-stone-200 rounded-md">
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

          <!-- กราฟที่ 2: Price Trend Chart -->
          <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
            <div class="flex justify-between items-center mb-2 shrink-0">
              <div>
                <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                  <span class="w-1 h-3 bg-primary rounded-full"></span>
                  ราคาตลาด
                </h2>
                <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price</p>
              </div>
            </div>
            <div class="flex-1 min-h-0 relative border-l border-b border-stone-100 bg-white">
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

      </div>

      <!-- BOTTOM ROW: Sensor Metrics (4 columns) -->
      <div class="grid grid-cols-6 gap-4 h-50 shrink-0" id="metrics-cards">
        <?php
$keys = ['do','ph','ec','temp'];
$warnings = [
    'do' => 'ควรอยู่ระหว่าง 3.0-7.0 mg/L',
    'ph' => 'ควรอยู่ระหว่าง 7.0-8.5',
    'ec' => 'ควรอยู่ระหว่าง 23K-45K μS/cm',
    'temp' => 'ควรอยู่ระหว่าง 28-32 °C'
];

for ($i = 0; $i < count($metricTitles); $i++): ?>
            <div class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm flex flex-col h-32 hover:ring-2 hover:ring-orange-400 transition-all duration-20 shrink-0" id="card-<?= $keys[$i] ?>">
                <div class="w-full flex justify-between items-center">
                        <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                            <?= $metricTitles[$i]['title'] ?>
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase status" id="card-<?= $keys[$i] ?>">
                                --
                        </span>
                </div>
                        <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">
                            <?= $metricTitles[$i]['value'] ?>
                        </span>

                <div class="flex-1 flex items-center justify-center">
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-black text-black value">--</span>
                        <span class="text-xs font-bold text-stone-400">
                            <?= $metricTitles[$i]['unit'] ?>
                        </span>
                    </div>
                </div>

                <!-- Range Bar with Fixed Gradient -->
                <div class="metric-range mt-2 hidden" data-key="<?= $keys[$i] ?>">
                    <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                        <span class="label-left"></span>
                        <span class="label-right"></span>
                    </div>

                    <div class="relative h-1 rounded-full bar">
                        <div class="fill"></div>
                    </div>
                    
                    <!-- Warning text -->
                    <p class="text-[6px] text-stone-500 font-medium mt-3 text-center">
                        <?= $warnings[$keys[$i]] ?>
                    </p>
                </div>

            </div>
<?php endfor; ?>
        </div>

    </div>

    <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
    <div class="col-span-2 grid grid-rows-4 gap-3 h-full">
      
      <!-- Card 1: การให้อาหารวันนี้ -->
       <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group shrink-0">
        <div class="flex items-center gap-2 mb-2">
          <span class="carbon--temperature-hot text-[#ff8021] text-sm"></span>
          <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์อุณหภูมิ</h3>
          
        </div>
      </div>



      <!-- Card 2: ต้นทุนทรัพยากรวันนี้ -->
            <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
        <div class="flex items-center gap-2 mb-2">
          <span class="solar--wind-bold-duotone text-sm text-[#ff8021]"></span>
          <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์ลมรายชัวโมง (ws10m)</h3>
        </div>
        <div class="grid grid-cols-2 gap-2" id="feeding-info">
          
        </div>
      </div>
      
      <!-- Card 3: คุณภาพน้ำที่เหมาะสม -->
      <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
        <div class="flex items-center gap-2 mb-2">
          <span class="wi--humidity text-sm text-[#ff8021]"></span>
          <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์ความชื้นสัมพัทธ์ (rh)</h3>
        </div>
        <div>
          
        </div>
      </div>

      <!-- Card 4: การปรับอาหาร -->
      <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
        <div class="flex items-center gap-2 mb-2">
          <span class="emojione-monotone--sun-behind-rain-cloud text-[#ff8021] text-sm"></span>
          <h3 class="text-[10px] font-bold text-stone-700">พยากรณ์สภาพอากาศรายชั่วโมง</h3>
        </div>
        <div class="flex flex-col gap-1">
          
          
        </div>
      </div>

    </div>

  </div>
</main>

    <!-- Footer -->
    <footer class="px-6 py-2 border-t border-stone-200 bg-white flex justify-between flex-row-reverse shrink-0">
        <div class="flex flex-center gap-2">
            <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 2.0</span>
            <div class="h-3 w-px bg-stone-200"></div>
            <span class="text-[9px] font-bold text-primary uppercase">smart farm system</span>
        </div>
    </footer>

    <!-- JavaScript -->
     <script>
        const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        const today = new Date();
        const formattedDate = `${today.getDate()} ${thaiMonths[today.getMonth()]} ${today.getFullYear() + 543}`;

        document.addEventListener('DOMContentLoaded', () => {
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
                success: 'status px-1 py-0.5 rounded-full bg-green-100 text-green-600 text-[8px] font-bold uppercase',
                warning: 'status px-1 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[8px] font-bold uppercase',
                danger:  'status px-1 py-0.5 rounded-full bg-red-100 text-red-600 text-[8px] font-bold uppercase',
                info:    'status px-1 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[8px] font-bold uppercase',
                na:      'status px-1 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] font-bold uppercase',
                orange:  'status px-1 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[8px] font-bold uppercase',
            };

            statusEl.textContent = text;
            statusEl.className = map[type] || map.na;
        }

        function getStatusByKey(key, v) {
            // DO
            if (key === 'do') {
                if (v >= 5.0 && v <= 7.0) return { text: 'ดีที่สุด', type: 'success' };
                if (v > 4.0) return { text: 'เหมาะสม', type: 'success' };
                if (v >= 3.0 && v <= 4.0) return { text: 'ไม่ดี', type: 'warning' };
                return { text: 'อันตราย', type: 'danger' };
            }

            // pH
            if (key === 'ph') {
                if (v >= 7.0 && v <= 8.5) return { text: 'เหมาะสม', type: 'success' };
                if (v < 6.5) return { text: 'หายใจลำบาก', type: 'orange' };
                if (v > 9.0) return { text: 'อันตราย', type: 'danger' };
                return { text: 'ควรเฝ้าดู', type: 'warning' };
            }

            // EC
            if (key === 'ec') {
                if (v >= 23000 && v <= 45000) return { text: 'เหมาะสม', type: 'success' };
                if (v < 23000) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'danger' };
            }

            // Temp
            if (key === 'temp') {
                if (v >= 28 && v <= 32) return { text: 'เหมาะสม', type: 'success' };
                if (v < 28) return { text: 'ต่ำ', type: 'info' };
                return { text: 'สูง', type: 'danger' };
            }

            return { text: 'N/A', type: 'na' };
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

        // ✅ การกำหนดค่าเกณฑ์สำหรับทุก sensor
        const BAR_CONFIG = {
            do: {
                min: 0,
                max: 7,
                leftLabel: '3.0',
                rightLabel: '7.0',
                leftColor: '#C73434',
                rightColor: '#198754',
            },
            ph: {
                min: 1,
                max: 14,
                leftLabel: '1',
                rightLabel: '14',
                leftColor: '#7F1D1D',
                rightColor: '#581C87',
            },
            ec: {
                min: 15000,
                max: 50000,
                leftLabel: '23K',
                rightLabel: '45K',
                leftColor: '#3B82F6',
                rightColor: '#198754',
            },
            temp: {
                min: 25,
                max: 35,
                leftLabel: '28°C',
                rightLabel: '32°C',
                leftColor: '#3B82F6',
                rightColor: '#F97316',
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

        // ✅ ฟังก์ชัน loadFeedingInfo() ใหม่
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

                if (!Array.isArray(rows) || rows.length === 0) {
                    throw new Error('ไม่มีข้อมูล');
                }

                rows.forEach(item => {
                    const label = (item.label || '').toLowerCase();
                    const value = item.value || '--';
                    
                    if (label.includes('จำนวนมื้อ') || item.id == 10) {
                        mealsEl.textContent = value;
                    }
                    else if (label.includes('เพิ่มต่อมื้อ') || item.id == 15) {
                        increaseEl.textContent = value;
                    }
                    else if (label.includes('ปริมาณรวม') || item.id == 14) {
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
            box.classList.add(
                "flex",
                "overflow-x-auto",
                "snap-x",
                "snap-mandatory",
                "scroll-smooth",
                "no-scrollbar"
            );

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
                    <div class="w-full h-full">
                    ${renderResourceCard(item)}
                    </div>
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
            const id = item.id ?? "-";
            const water = toNumber(item.water_value);
            const waterUnit = item.water_unit ?? "m3";
            const elec = toNumber(item.electric_value);
            const elecUnit = item.electric_unit ?? "kWh";
            const status = String(item.status ?? "NORMAL").toUpperCase();
            const updated = item.updated_at ?? "";

            return `
                <div class=" ">
                <div class="flex flex-row-reverse mb-2">
                </div>

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

            if (doChart) {
                doChart.destroy();
            }

            // Adjust font sizes based on screen width
            const screenWidth = window.innerWidth;
            let fontSize = 9;
            if (screenWidth >= 3840) fontSize = 18;
            else if (screenWidth >= 1920) fontSize = 12;

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
                        pointRadius: screenWidth >= 3840 ? 4 : 2,
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
                                font: { size: fontSize },
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
                                font: { size: fontSize },
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

        // ========== MARKET PRICE CHART ==========
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

        let marketPriceChart;

        function renderMarketPriceChart(labels, price50, price70) {
            const ctx = document.getElementById('marketPriceChart');
            if (!ctx) return;

            if (marketPriceChart) {
                marketPriceChart.destroy();
            }

            // Adjust font sizes based on screen width
            const screenWidth = window.innerWidth;
            let fontSize = 10;
            if (screenWidth >= 3840) fontSize = 20;
            else if (screenWidth >= 1920) fontSize = 14;

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
                            pointRadius: screenWidth >= 3840 ? 5 : 2.5
                        },
                        {
                            label: '70 ตัว/กก.',
                            data: price70,
                            borderColor: 'rgba(255,128,33,0.4)',
                            backgroundColor: 'rgba(255,128,33,0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: screenWidth >= 3840 ? 4 : 2
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
                                font: { size: fontSize },
                                color: '#78716c'
                            }
                        },
                        y: {
                            ticks: {
                                font: { size: fontSize },
                                color: '#78716c',
                                callback: v => v + ' ฿'
                            }
                        }
                    }
                }
            });
        }

        // เรียกใช้เมื่อโหลดหน้า
        document.addEventListener("DOMContentLoaded", () => {
            initMetricBars();
        });

    </script>
</body>
</html>