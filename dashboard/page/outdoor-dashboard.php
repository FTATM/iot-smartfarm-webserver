<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "ความชื้นในดิน",
        "value" => "(Soil Moisture)",
        "unit"  => "%RH"
    ],
    [
        "title" => "ความนำไฟฟ้า",
        "value" => "(EC)",
        "unit"  => "µS/cm"
    ],
    [
        "title" => "ค่า",
        "value"=> "(PH)",
        "unit"  => " "
    ],
    [
        "title" => "อุณหภูมิ",
        "value" => "(Temperature)",
        "unit"  => "°C"
    ],
    [
        "title" => "ความชื้น",
        "value" => "(Humidity)",
        "unit"  => "%"
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
        .fluent--door-arrow-right-28-regular {
        --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 28 28'%3E%3Cpath fill='%23000' d='M5 5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8.427a7.5 7.5 0 0 0-1.5-.36V5A1.5 1.5 0 0 0 20 3.5H8A1.5 1.5 0 0 0 6.5 5v18A1.5 1.5 0 0 0 8 24.5h6.155a7.5 7.5 0 0 0 1.246 1.5H8a3 3 0 0 1-3-3zm4.5 10a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3M27 20.5a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0M16.5 20a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 .146-.351v-.006a.5.5 0 0 0-.146-.35l-3-3a.5.5 0 0 0-.708.707L23.293 20z'/%3E%3C/svg%3E");
        -webkit-mask-image: var(--svg);
        mask-image: var(--svg);
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

        .ic--round-list {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M4 13c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m0 4c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m0-8c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m4 4h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1s.45 1 1 1m0 4h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1s.45 1 1 1M7 8c0 .55.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1m-3 5c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m0 4c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m0-8c.55 0 1-.45 1-1s-.45-1-1-1s-1 .45-1 1s.45 1 1 1m4 4h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1s.45 1 1 1m0 4h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1s.45 1 1 1M7 8c0 .55.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1H8c-.55 0-1 .45-1 1'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
            }

        .game-icons--fertilizer-bag {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23000' d='M241.1 21.91c-89.7-.15-172.99 2.47-171.94 8.27l22.08 36.14c59.36-5.1 115.96-6.95 172.66-6.57c53.2.35 106.5 2.67 162.1 6.09l31.2-33.28c-4.5-6.74-114.4-10.48-216.1-10.65m3.8 55.81c-49.1.12-98.3 1.99-149.69 6.32C73.42 207.7 61.35 358.6 89.71 451.7l-26.43 31c128.12 9.6 258.52 12.7 382.62 6.3L416 453.4c19.3-106.2 13.3-251.2.2-370.12c-51.2-3.06-100.3-5.1-149.3-5.49L401.1 201h-71.3c3.8 23.1 20.4 54.3 37.9 83.2c9.9 16.4 20 32.3 27.7 46.3S409 356.2 409 368c0 28-18.9 51-46.7 65.9c-27.8 15-65.1 23.1-106.3 23.1s-78.5-8.1-106.3-23.1C121.9 419 103 396 103 368c0-12 6.2-23.7 14.3-37.9s18.6-30.2 28.8-46.8c18-29.1 34.4-60.5 36.7-82.3H104zm11 14.37L152 183h48.3l.7 8.2c2.7 31.9-18.9 68-39.6 101.6c-10.4 16.8-20.8 32.7-28.4 46.2c-7.7 13.4-12 25-12 29c0 14.6 7 27.5 20.1 38.6c54.2-6.8 82-10.6 106.4-11.4c.6-8.5 1.7-17 3.1-25.2c-36.6 2.3-70.3-7.5-103.5-19.3c14.9-28.8 42.3-48.9 67-48.8c4.6 0 9.1.6 13.4 2.1c11.2 3.6 21.3 23.7 28 40.6c2.2-10.5 4.3-19.5 5.2-26.1v-.1c.9-6.2-1.6-24-4.8-38.7c-2.3-10.6-4.7-19.9-5.9-24.3c-6.3 0-11.9-1.2-15.6-5c-25.4-26.4-8.9-75.2 12.9-102.4c15.5 25.8 35.7 53.6 56 72.8c-4.4 14.8-12.4 26.7-27.9 32c-2.1.2-4.7.5-7.3.9c1.4 5.2 3.4 13 5.4 22.1c1.3 5.7 2.5 11.6 3.5 17.5c5.3-4.9 11.4-9.4 16.4-9.6h1.1c28 2.6 59.7 29.6 67.7 69c-27.5-12.6-59-5.3-83.9-30.2c-2.8 18.6-10.6 46.6-12.7 72.8c24.1.8 51.9 4.6 105.3 11.3c13.1-11.1 20.1-24 20.1-38.6c0-4.2-4.1-15.6-11.4-28.8c-7.3-13.3-17.2-29-27.3-45.7C332.2 260.3 311 224.2 311 192v-9h43.9zm.1 320.81c-22.3 0-45.9 3-90.7 8.7C189 432.5 220.8 439 256 439s67-6.5 90.7-17.4c-44.8-5.7-68.4-8.7-90.7-8.7'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
            }

        .clarity--coin-bag-line {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'%3E%3Cpath fill='%23000' d='M21.6 29a1 1 0 0 0-1-1h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 1-1' class='clr-i-outline clr-i-outline-path-1'/%3E%3Cpath fill='%23000' d='M22.54 24h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-2'/%3E%3Cpath fill='%23000' d='M22 32h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-3'/%3E%3Cpath fill='%23000' d='M32.7 32h-7a1 1 0 0 0 0 2h7a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-4'/%3E%3Cpath fill='%23000' d='M33.7 28h-7a1 1 0 0 0 0 2h7a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-5'/%3E%3Cpath fill='%23000' d='M33.74 26a28 28 0 0 0-2.82-10.12a20.24 20.24 0 0 0-6.32-7.17L27 3.42a1 1 0 0 0-.07-1a1 1 0 0 0-.8-.42H9.8a1 1 0 0 0-.91 1.42l2.45 5.31a20.3 20.3 0 0 0-6.28 7.15c-2.15 4-2.82 8.89-3 12.28a3.6 3.6 0 0 0 1 2.71a3.8 3.8 0 0 0 2.74 1.07H12V30H5.72a1.68 1.68 0 0 1-1.21-.52a1.62 1.62 0 0 1-.45-1.23c.14-2.61.69-7.58 2.76-11.45a18 18 0 0 1 6.26-6.8h1a31 31 0 0 0-1.87 2.92a23 23 0 0 0-1.47 3.34l1.37.92a24 24 0 0 1 1.49-3.47A29 29 0 0 1 16.05 10h1a21.5 21.5 0 0 1 1.41 5a22.5 22.5 0 0 1 .32 3.86l1.58-1.11a24 24 0 0 0-.32-3A25 25 0 0 0 18.76 10h.78l.91-2h-7.24l-1.85-4h13.21l-2.5 5.47a10 10 0 0 1 1.23.78a18.6 18.6 0 0 1 5.86 6.57A26.6 26.6 0 0 1 31.73 26Z' class='clr-i-outline clr-i-outline-path-6'/%3E%3Cpath fill='none' d='M0 0h36v36H0z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
            }

        .emojione-monotone--money-bag {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cellipse cx='10.97' cy='52.578' fill='%23000' rx='1.742' ry='1.188'/%3E%3Cpath fill='%23000' d='m62 46.669l-.654-.384c-.05-.029-5.047-2.994-6.289-6.592l-.277-.797l-.512.669c-.016.021-.726.899-2.713 1.929c-1.471-8.373-6.475-18.369-13.051-22.631c1.88.152 3.766-.004 5.586-.666c1.096-.398.623-2.184-.483-1.78c-1.492.543-2.987.691-4.479.615c3.663-3.357 6.539-8.502 6.539-12.264c0-.904 0-2.587-1.34-2.587c-.538 0-.973.336-1.574.8c-.922.711-2.314 1.787-4.359 1.787c-.779 0-2.205-.822-3.351-1.482C33.658 2.489 32.773 2 32.029 2c-1.232 0-3.043.996-4.641 1.875c-.65.357-1.539.847-1.723.894c-2.316 0-3.661-1.068-4.553-1.775c-.52-.414-.897-.714-1.4-.714c-1.318 0-1.318 1.581-1.318 3.412c0 3.534 2.692 8.199 6.217 11.334c-2.221.159-4.411.83-5.95 2.321c-.85.822.438 2.126 1.285 1.306c1.279-1.24 2.956-1.771 4.75-1.874q.076.274.189.54C17.369 24.72 12.03 37.545 12.03 46.203c0 .854.066 1.627.168 2.355c-1.938-.861-2.645-1.616-2.659-1.633l-.516-.595l-.254.75c-1.123 3.304-6.005 6.17-6.054 6.199L2 53.694l.727.393c.255.138.508.259.763.39l-.46.319l.243 3.04L22.326 62l7.224-4.621q1.093.006 2.218.006L40.212 62l19.089-6v-3.209l-.505-.303q.378-.24.755-.491l.669-.447l-.147-.072l.851-.233l.144-3.336l-.492-.349q.393-.235.785-.483zM20.213 5.692c0-.444.006-.79.015-1.059c.153.12.331.251.521.386c.583 1.396 1.686 3.406 3.556 4.672c2.272 1.54 1.97-.23 3.182-1.846c1.212-1.615 2.032-2.461 4.062 1.693c.885 1.811 2.36-3.25 3.762-3.989c.371.202.741.39 1.104.55c2.514 4.088 3.679 2.813 5.912-.585c.614-.36 1.118-.746 1.519-1.055q.003.139.004.311c0 3.21-2.623 7.814-5.835 10.817a3.6 3.6 0 0 0-.642-1.053c-1.297-1.469-3.324.16-4.285 1.229c-1.705-.548-4.483-2.057-6.293-1.193a4.6 4.6 0 0 0-1.226.831c-2.96-2.698-5.356-6.593-5.356-9.709m16.204 10.974a27 27 0 0 1-1.337-.32c.558-.481 1.07-.649 1.285.066c.023.078.037.167.052.254m-7.6.621q-.496-.096-1.021-.165a14 14 0 0 1-.779-.525c.914-.613 2.49-.287 3.996.224c-.743.075-1.476.237-2.196.466m.454 2.64c-.56.14-1.17.084-1.697-.126c.214-.113.43-.211.646-.31c.35.169.7.31 1.051.436M10.774 56.379a39.3 39.3 0 0 1-6.912-2.733c1.357-.892 4.313-3.049 5.515-5.641c.824.647 2.726 1.827 6.43 2.733c-.063.102-.14.211-.214.318c-3.162-.828-4.96-1.875-4.989-1.893l-.436-.26l-.209.467c-.275.612-.92.704-1.279.704c-.1 0-.167-.007-.176-.008l-.357-.052l-.13.342c-.266.698-1.358 1.61-1.758 1.903l-.668.49l.764.313c.284.116.361.223.365.251c.01.069-.111.251-.24.356l-.582.469l.677.313c1.387.64 2.958 1.17 4.529 1.607q-.186.183-.33.321m8.064-3.726c1.398.196 2.947.32 4.639.32h.001q.527 0 1.073-.017a1 1 0 0 0 .103.213q.133.203.361.334l-2.552 2.181l-.146-.003c-1.137 0-1.985.418-2.392 1.162a69 69 0 0 1-4.602-.747a51 51 0 0 0 3.515-3.443M7.489 53.85c.1-.189.165-.418.13-.668a1 1 0 0 0-.307-.59c.449-.385 1.068-.979 1.408-1.594c.773-.011 1.411-.304 1.813-.822c.677.354 2.202 1.069 4.455 1.689c-.921 1.155-2.162 2.465-3.127 3.439c-1.504-.398-3.019-.878-4.372-1.454m14.251 4.209l-.205.001c-.861 0-3.714-.07-7.483-.855c.132-.112.269-.227.396-.337c3.058.639 5.524.913 5.709.934l.366.039l.113-.355c.247-.766 1.158-.879 1.68-.879c.143 0 .238.009.249.01l.194.021l4.433-3.788l-1.185-.039c-.396-.014-.563-.103-.604-.164l.251-.657l-.694.029q-.759.033-1.483.033h-.001c-1.396 0-2.691-.092-3.888-.237l.278-.328c2.416.323 5.309.522 8.802.497c-.834 1.17-2.914 3.657-6.928 6.075m10.024-4.345v1.825q-.853 0-1.665-.006l-.163-2.054l-1.354.11c.912-1.078 1.301-1.797 1.336-1.862l.367-.694l-.775.014c-7.418.136-12.256-.748-15.31-1.73c-.223-.902-.352-1.922-.352-3.113c0-8.123 5.191-20.401 12.083-25.377c1.221 1.181 3.073 1.698 4.833.549c.386-.252.722-.582 1.037-.933c.075.002.152.019.228.019c.252 0 .508-.021.763-.053c.323.129.639.259.932.388c1.403.618 2.601.234 3.419-.618c6.313 3.82 11.371 13.925 12.702 22.093c-2.886 1.154-7.464 2.28-14.49 2.478c1.386-.792 2.426-2.124 2.052-3.874c-.393-1.85-1.939-2.37-3.54-2.455q-.007-1.302.002-2.605c.491.217.962.467 1.39.734c.756.475 1.44-.756.689-1.231a10.3 10.3 0 0 0-2.072-.999q.015-1.508.015-3.014c.001-.92-1.366-.92-1.366 0q-.001 1.325-.014 2.646a7.1 7.1 0 0 0-2.202-.037a27 27 0 0 0-.432-1.97c-.226-.886-1.546-.511-1.317.378c.168.639.305 1.275.418 1.92c-.058.022-.121.033-.178.059c-1.852.733-2.982 2.602-1.627 4.345c.631.812 1.456 1.14 2.359 1.249c.049 1.203.08 2.407.115 3.609c-.605-.393-1.156-.897-1.655-1.43c-.618-.652-1.583.355-.966 1.006c.839.893 1.72 1.568 2.675 2c.043 1.027.104 2.057.204 3.079c.088.907 1.456.912 1.367 0a54 54 0 0 1-.187-2.657c.52.09 1.066.113 1.646.058c.065.946.141 1.892.249 2.838c.062.542.58.755.962.645l-.085 1.964c-.602.054-1.216.104-1.856.146l-.762.05l.406.656c.041.066.502.795 1.531 1.856h-1.412zm24.888-6.582c-1.257.591-2.847 1.133-4.467 1.606c-.996-1.029-2.27-2.402-3.221-3.62c2.119-.642 3.305-1.304 3.84-1.661c.423.373.983.567 1.646.566c.22 0 .415-.021.565-.045c.48.622 1.254 1.34 1.738 1.769q-.344.353-.331.776c.008.24.104.444.23.609m-7.155 3.286q.307.27.63.55c-4.054 1.075-7.071 1.192-7.728 1.203c-4.198-2.561-6.374-5.271-7.213-6.491c3.513-.088 6.417-.406 8.839-.844a24 24 0 0 0 .299.36a53 53 0 0 1-5.877.583l-.807.034l.444.679c-.001.001-.091.169-.687.285l-.969.189l4.764 3.857l.222-.06c.005-.001.425-.114.933-.114c.733 0 1.234.223 1.486.659l.16.276l.311-.055a87 87 0 0 0 5.193-1.111M35.4 47.479l-1.138-.062c-.053-.568-.106-1.136-.146-1.704c.2.332.61.956 1.284 1.766m-1.408-3.661a105 105 0 0 1-.119-4.041q.248.01.48.043c1.768.268 2.23 2.234.85 3.34c-.36.285-.775.494-1.211.658m10.439 6.752c-.448-.553-1.161-.843-2.083-.843c-.385 0-.724.052-.938.094l-2.995-2.427q.372-.214.509-.546a1 1 0 0 0 .052-.171c2.374-.13 4.374-.367 6.065-.657c.82.915 2.025 2.177 3.623 3.644c-1.963.467-3.6.787-4.233.906M32.505 39.813c.019 1.449.055 2.902.132 4.35c-.129.02-.256.045-.383.058c-.43.049-.838.013-1.226-.085c-.049-1.4-.081-2.803-.134-4.204c.538-.025 1.083-.078 1.611-.119m-1.68-1.265a44 44 0 0 0-.31-3.286a5.3 5.3 0 0 1 1.987.101a285 285 0 0 0-.005 3.067c-.439.024-.856.066-1.223.099a6 6 0 0 1-.449.019m-1.372-.175c-.085-.023-.169-.041-.256-.07a2.7 2.7 0 0 1-1.057-.667c-.854-.797.318-1.579 1.057-1.95c.116.89.196 1.788.256 2.687M40.49 57.839c-4.341-2.218-6.67-4.683-7.595-5.825c.665-.05 1.294-.11 1.917-.173l7.118 4.62l15.61-4.288c-8.05 4.852-16.003 5.591-17.05 5.666m12.892-7.89l-.462-.463c1.776-.538 3.494-1.162 4.775-1.848l.779-.419l-.79-.402c-.177-.091-.35-.258-.353-.321c.002-.024.072-.152.375-.326l.578-.332l-.514-.428c-.016-.013-1.619-1.344-2.182-2.201l-.186-.28l-.319.093c-.003 0-.277.079-.633.079c-.55.001-.962-.179-1.226-.533l-.292-.387l-.366.315c-.019.016-1.152.945-4.191 1.823a10 10 0 0 1-.289-.44c3.685-1.117 5.568-2.469 6.384-3.209c1.31 2.834 4.429 5.117 5.783 6.012a38 38 0 0 1-6.871 3.267'/%3E%3Cpath fill='%23000' d='M44.38 47.117c-.997-.271-2.122-.086-2.516.409c-.391.497.103 1.123 1.099 1.396c.998.271 2.122.085 2.516-.411c.391-.497-.101-1.121-1.099-1.394'/%3E%3C/svg%3E");
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
            
            .fluent--door-arrow-right-28-regular {
                width: 50px;
                height: 50px;
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
            
            #metrics-cards .text-lg {
                font-size: 4rem !important;
            }
            
            #metrics-cards .text-sm {
                font-size: 2rem !important;
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
            
            .fluent--door-arrow-right-28-regular {
                width: 35px;
                height: 35px;
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
            
            #metrics-cards .text-lg {
                font-size: 2.5rem !important;
            }
            
            #metrics-cards .text-sm {
                font-size: 1.25rem !important;
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
                <span class="fluent--door-arrow-right-28-regular text-2xl text-[#ff8021]"> </span>
            </div>
            <div>
                <h1 class="text-[#1d130c] text-lg font-bold leading-none">Outdoor Dashboard</h1>
                <p class="text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-1">Outdoor Intelligence Dashboard</p>
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

                    <!-- รูปภาพ 2 ส่วน (กลาง) -->
                    <div class="col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 5rem;">image</span>
                            <p class="text-xs mt-2 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-[10px] mt-1 text-stone-400">เครื่องมือ/อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- กราฟ 2 ส่วน (เรียงแนวตั้ง - ขวาสุด) -->
                    <div class="col-span-2 flex flex-col gap-4 min-h-0">
                        <!-- กราฟที่ 1: DO Trend Chart -->
                        <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col flex-1 hover:ring-2 hover:ring-orange-400 transition-all duration-200 min-h-0">
                            <div class="flex justify-between items-center mb-2 shrink-0">
                                <div>
                                    <h2 class="text-[11px] font-bold text-stone-800 flex items-center gap-2">
                                        <span class="w-1 h-3 bg-orange-500 rounded-full"></span>
                                        แนวโน้มค่าออกซิเจนละลายน้ำ
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">
                                        Historical DO Data (24H)
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
                                        แนวโน้มราคาตลาด
                                    </h2>
                                    <p class="text-[7px] text-stone-400 font-medium uppercase tracking-wider mt-0.5">Market Price Trend</p>
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
                <div class="grid grid-cols-5 gap-4 shrink-0" id="metrics-cards">
                    <?php
                    $keys = ['do', 'ph', 'ec', 'temp'];
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
                                    <span class="text-lg font-black text-black value">--</span>
                                    <span class="text-sm font-bold text-stone-400">
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

                                <div class="relative h-1.5 rounded-full bar">
                                    <div class="fill"></div>
                                </div>

                                <!-- Warning text -->
                                <p class="text-[7px] text-stone-500 font-medium mt-3 text-center">
                                    <?= $warnings[$keys[$i]] ?>
                                </p>
                            </div>

                        </div>
                    <?php endfor; ?>
                </div>

            </div>

            <!-- ========== RIGHT SECTION (2 columns): 4 Cards แนวตั้ง ========== -->
            <div class="col-span-2 grid grid-rows-4 gap-3 h-full">

                <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm ic--round-list"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">สิ่งที่ต้องทำวันนี้</h3>
                    </div>
                    <div id="tasks-container" class="flex-1 flex flex-col gap-2 overflow-y-auto custom-scrollbar">
                        <!-- ข้อมูลจะแสดงที่นี่ -->
                    </div>
                </div>

                <!-- Card 2: การให้น้ำและปุ๋ย -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 group shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm game-icons--fertilizer-bag"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">การให้น้ำและปุ๋ย</h3>
                    </div>
                    <div id="watering-container" class="flex-1 flex flex-col gap-2">
                        <!-- ข้อมูลจะแสดงที่นี่ -->
                    </div>
                </div>

                <!-- Card 3: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm clarity--coin-bag-line"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนทรัพยากรวันนี้</h3>
                    </div>
                    <div id="resource-cost-container" class="flex-1 flex flex-col gap-2">
                        <!-- ข้อมูลจะแสดงที่นี่ -->
                    </div>
                </div>

                <!-- Card 4: ต้นทุนรวมวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200 shrink-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm emojione-monotone--money-bag"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนรวมวันนี้</h3>
                    </div>
                    <div id="total-cost-container" class="flex-1 flex flex-col gap-2">
                        <!-- ข้อมูลจะแสดงที่นี่ -->
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="px-6 py-2 border-t border-stone-200 bg-white flex justify-between flex-row-reverse shrink-0">
        <div class="flex flex-center gap-2">
            <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Version 1.0</span>
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
            loadTasksData();
            loadWateringData();
            loadResourceCostData();
            loadTotalCostData();
            loadDoTrendData();

            setInterval(() => {
                loadSensorData();
                loadTasksData();
                loadWateringData();
                loadResourceCostData();
                loadTotalCostData();
                loadDoTrendData();
            }, 30000);
        });

        // ==================== SENSOR DATA ====================
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

        // ==================== CARD 1: สิ่งที่ต้องทำวันนี้ ====================
        async function loadTasksData() {
            try {
                const res = await fetch('/dashboard/api/daily_tasks.php');
                const data = await res.json();
                
                const container = document.getElementById('tasks-container');
                container.innerHTML = '';

                if (Array.isArray(data)) {
                    data.forEach(task => {
                        const div = document.createElement('div');
                        div.className = 'bg-stone-50 rounded-lg p-2 border border-stone-100';
                        div.innerHTML = `
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex-1">
                                    <div class="text-[10px] font-bold text-stone-800">${task.title || ''}</div>
                                    ${task.subtitle ? `<div class="text-[9px] text-stone-500 mt-0.5">${task.subtitle}</div>` : ''}
                                    ${task.details ? `<div class="text-[9px] text-stone-600 font-bold mt-1 whitespace-pre-line">${task.details}</div>` : ''}
                                </div>
                                ${task.ph ? `<div class="text-[9px] font-bold text-stone-800 whitespace-nowrap">${task.ph}</div>` : ''}
                            </div>
                        `;
                        container.appendChild(div);
                    });
                }
            } catch (err) {
                console.error('❌ loadTasksData error:', err);
            }
        }

        // ==================== CARD 2: การให้น้ำและปุ๋ย ====================
        async function loadWateringData() {
            try {
                const res = await fetch('/dashboard/api/watering_schedule.php');
                const data = await res.json();
                
                const container = document.getElementById('watering-container');
                container.innerHTML = '';

                if (data.range) {
                    const rangeDiv = document.createElement('div');
                    rangeDiv.className = 'text-[9px] text-stone-500 text-right mb-1';
                    rangeDiv.textContent = data.range;
                    container.appendChild(rangeDiv);
                }

                const gridDiv = document.createElement('div');
                gridDiv.className = 'grid grid-cols-2 gap-2';
                
                if (data.watering) {
                    gridDiv.innerHTML += `
                        <div class="bg-stone-50 rounded-lg p-2 border border-stone-100">
                            <div class="text-[9px] text-stone-500 mb-1">${data.watering.title || 'การให้น้ำ'}</div>
                            <div class="text-[10px] font-bold text-stone-800">${data.watering.schedule || '-'}</div>
                        </div>
                    `;
                }

                if (data.fertilizer) {
                    gridDiv.innerHTML += `
                        <div class="bg-stone-50 rounded-lg p-2 border border-stone-100">
                            <div class="text-[9px] text-stone-500 mb-1">${data.fertilizer.title || 'การใส่ปุ๋ย'}</div>
                            <div class="text-[10px] font-bold text-stone-800">${data.fertilizer.schedule || '-'}</div>
                        </div>
                    `;
                }
                
                container.appendChild(gridDiv);

                if (data.alert) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'bg-red-50 rounded-lg p-2 border border-red-100 mt-2';
                    alertDiv.innerHTML = `
                        <div class="text-[9px] text-red-600 font-bold mb-1">${data.alert.text || ''}</div>
                        <div class="text-[9px] text-red-800">${data.alert.details || ''}</div>
                    `;
                    container.appendChild(alertDiv);
                }
            } catch (err) {
                console.error('❌ loadWateringData error:', err);
            }
        }

        // ==================== CARD 3: ต้นทุนทรัพยากรวันนี้ ====================
        async function loadResourceCostData() {
            try {
                const res = await fetch('/dashboard/api/resource_to_day.php');
                const data = await res.json();
                
                const container = document.getElementById('resource-cost-container');
                container.innerHTML = '';

                if (data.range) {
                    const rangeDiv = document.createElement('div');
                    rangeDiv.className = 'text-[9px] text-stone-500 text-right mb-2';
                    rangeDiv.textContent = data.range;
                    container.appendChild(rangeDiv);
                }

                const gridDiv = document.createElement('div');
                gridDiv.className = 'grid grid-cols-2 gap-2';

                if (data.water) {
                    gridDiv.innerHTML += `
                        <div class="bg-stone-50 rounded-lg p-2 border border-stone-100">
                            <div class="text-[9px] text-stone-500 mb-1">ค่าน้ำประปา</div>
                            <div class="text-lg font-black text-stone-800">
                                ${data.water.value.toFixed(1)} 
                                <span class="text-[9px] font-bold text-stone-500">${data.water.unit || 'm3'}</span>
                            </div>
                        </div>
                    `;
                }

                if (data.electricity) {
                    gridDiv.innerHTML += `
                        <div class="bg-stone-50 rounded-lg p-2 border border-stone-100">
                            <div class="text-[9px] text-stone-500 mb-1">ค่าไฟฟ้า</div>
                            <div class="text-lg font-black text-stone-800">
                                ${data.electricity.value.toFixed(0)} 
                                <span class="text-[9px] font-bold text-stone-500">${data.electricity.unit || 'kWh'}</span>
                            </div>
                        </div>
                    `;
                }

                container.appendChild(gridDiv);

                if (data.costs) {
                    const costDiv = document.createElement('div');
                    costDiv.className = 'bg-primary/5 rounded-lg p-2 border border-primary/10 mt-2';
                    costDiv.innerHTML = `
                        <div class="text-[9px] text-primary font-bold mb-1">${data.costs.water || ''}</div>
                        <div class="text-[10px] font-black text-primary">${data.costs.range || ''}</div>
                    `;
                    container.appendChild(costDiv);
                }

                if (data.updated_at) {
                    const updateDiv = document.createElement('div');
                    updateDiv.className = 'text-[8px] text-stone-400 text-right mt-1';
                    updateDiv.textContent = `อัปเดต: ${data.updated_at}`;
                    container.appendChild(updateDiv);
                }
            } catch (err) {
                console.error('❌ loadResourceCostData error:', err);
            }
        }

        // ==================== CARD 4: ต้นทุนรวมวันนี้ ====================
        async function loadTotalCostData() {
            try {
                const res = await fetch('/dashboard/api/total_cost_today.php');
                const data = await res.json();
                
                const container = document.getElementById('total-cost-container');
                container.innerHTML = '';

                if (data.costs && Array.isArray(data.costs)) {
                    data.costs.forEach(cost => {
                        const div = document.createElement('div');
                        div.className = 'flex justify-between items-center py-1 border-b border-stone-100';
                        div.innerHTML = `
                            <span class="text-[9px] text-stone-600">${cost.label || ''}</span>
                            <span class="text-[9px] font-bold text-stone-800">${cost.amount || '-'}</span>
                        `;
                        container.appendChild(div);
                    });
                }

                if (data.total) {
                    const totalDiv = document.createElement('div');
                    totalDiv.className = 'flex justify-between items-center pt-2 border-t-2 border-primary/20';
                    const colorClass = data.total.color === 'orange' ? 'text-primary' : 'text-stone-800';
                    totalDiv.innerHTML = `
                        <span class="text-[10px] font-bold ${colorClass}">${data.total.label || 'รวม'}</span>
                        <span class="text-[11px] font-black ${colorClass}">${data.total.amount || '-'}</span>
                    `;
                    container.appendChild(totalDiv);
                }
            } catch (err) {
                console.error('❌ loadTotalCostData error:', err);
            }
        }

        // ==================== DO TREND CHART ====================
        let doChart = null;

        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');

            try {
                const res = await fetch('/dashboard/api/monitor_trend.php', {
                    cache: 'no-store'
                });
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
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: fontSize
                                },
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
                                font: {
                                    size: fontSize
                                },
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

        // ==================== MARKET PRICE TREND ====================
        async function loadMarketPriceTrend() {
            const loading = document.getElementById('price-loading');

            try {
                const res = await fetch('/dashboard/api/market_price_Tred.php', {
                    cache: 'no-store'
                });
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

            const screenWidth = window.innerWidth;
            let fontSize = 10;
            if (screenWidth >= 3840) fontSize = 20;
            else if (screenWidth >= 1920) fontSize = 14;

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
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.raw} บาท/กก.`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: fontSize
                                },
                                color: '#78716c'
                            }
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: fontSize
                                },
                                color: '#78716c',
                                callback: v => v + ' ฿'
                            }
                        }
                    }
                }
            });
        }

        // ==================== INIT ====================
        document.addEventListener("DOMContentLoaded", () => {
            initMetricBars();
            loadMarketPriceTrend();
        });
    </script>
</body>

</html>