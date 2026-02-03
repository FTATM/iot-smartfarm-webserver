<?php

$currentDate = date('d M Y');
date_default_timezone_set('Asia/Bangkok');
$currentTime = date('H:i:s');

$metricTitles = [
    [
        "title" => "อุณหภูมิ",
        "value" => "(Soil Moisture)",
        "unit"  => "%RH"
    ],
];
?>

<!DOCTYPE html>
<html class="light" lang="th">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Outdoor System - Dashboard</title>

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

        /* ===== ICONS ===== */
        .fluent--door-arrow-right-28-regular {
            display: inline-block;
            width: 25px;
            height: 25px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 28 28'%3E%3Cpath fill='%23000' d='M5 5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8.427a7.5 7.5 0 0 0-1.5-.36V5A1.5 1.5 0 0 0 20 3.5H8A1.5 1.5 0 0 0 6.5 5v18A1.5 1.5 0 0 0 8 24.5h6.155a7.5 7.5 0 0 0 1.246 1.5H8a3 3 0 0 1-3-3zm4.5 10a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3M27 20.5a6.5 6.5 0 1 1-13 0a6.5 6.5 0 0 1 13 0M16.5 20a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 .146-.351v-.006a.5.5 0 0 0-.146-.35l-3-3a.5.5 0 0 0-.708.707L23.293 20z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .game-icons--coins {
            display: inline-block;
            width: 20px;
            height: 20px;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23000' d='M136 25.01c-16.5 0-31.3 3.4-41.29 8.4c-9.9 5-13.7 10.6-13.7 14.6c0 3.9 3.8 9.61 13.7 14.5c9.99 5 24.79 8.5 41.29 8.5s31.3-3.5 41.2-8.5c10-4.89 13.8-10.6 13.8-14.5c0-4-3.8-9.6-13.8-14.6c-9.9-5-24.7-8.4-41.2-8.4m160 32c-16.5 0-31.3 3.4-41.2 8.4c-10 5-13.8 10.6-13.8 14.6c0 3.9 3.8 9.61 13.8 14.5c9.9 5 24.7 8.49 41.2 8.49c4.6 0 9-.3 13.2-.8c4.3-5.49 10.4-10.36 17.5-13.6c8.2-3.7 17.7-6.7 24.3-8.59c0-4-3.8-9.6-13.8-14.6c-9.9-5-24.7-8.4-41.2-8.4M81.01 75.4c-.4 14.66 15.48 20.64 25.49 23.6c17.9 5.2 41.1 5.2 59 0c12.8-3.66 25.4-10.72 25.5-23.6c-16.1 10.63-39.6 13.49-55 13.6c-19.5-1.63-39.98-3.65-54.99-13.6M376 96.31c-16.5 0-31.3 3.4-41.2 8.49c-10 4.9-13.8 10.6-13.8 14.5c0 4 3.8 9.6 13.8 14.6c9.9 5 24.7 8.4 41.2 8.4s31.3-3.4 41.2-8.4c10-5 13.8-10.6 13.8-14.6c0-3.9-3.8-9.6-13.8-14.5c-9.9-5.09-24.7-8.49-41.2-8.49M241 107.4v2.4c2.9 1 5.7 2.2 8.3 3.5c9 4.5 16.8 10.8 20.8 18.7c10.2 2.5 21.7 3.4 32.9 2.7v-13.9c-2.3.1-4.6.2-7 .2c-18.9 0-36.1-3.7-49.3-10.3q-3-1.5-5.7-3.3m-159.99.1c-.34 14.6 15.52 20.6 25.49 23.6c6.8 1.9 14.4 3.2 22.2 3.6c2-5.2 5.6-9.8 10.2-13.7c-12.8.3-25.7-1.3-37.4-4.6c-7.79-2.3-14.69-5.2-20.49-8.9M200 121c-16.5 0-31.3 3.5-41.2 8.4c-10 5-13.8 10.6-13.8 14.6s3.8 9.6 13.8 14.6c9.9 5 24.7 8.4 41.2 8.4s31.3-3.4 41.2-8.4c10-5 13.8-10.6 13.8-14.6s-3.8-9.6-13.8-14.6c-9.9-4.9-24.7-8.4-41.2-8.4M81.01 139.5c-.34 14.6 15.52 20.6 25.49 23.6c6.3 1.8 13.3 3 20.5 3.5v-13.8c-8.8-.6-17.4-2.1-25.5-4.4c-7.79-2.3-14.69-5.2-20.49-8.9M321 146.8c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.2 41.1 5.2 59 0c12.7-3.7 25.4-10.7 25.5-23.6c-1.8 1.1-3.7 2.2-5.7 3.2c-13.2 6.6-30.4 10.3-49.3 10.3s-36.1-3.7-49.3-10.3c-2-1-3.9-2.1-5.7-3.2m-48 4.2v13.7c9.4 1.9 19.9 2.6 30 2v-13.8c-10.1.5-20.3-.1-30-1.9m-128 20.4c-.5 6 2.2 10.9 5.5 13.8c4.2 3.6 11 7.3 20 9.9c17.9 5.1 41.1 5.1 59 0c9-2.6 15.8-6.3 20-9.9c4.9-4.1 5.5-8.1 5.5-13.8q-2.7 1.8-5.7 3.3c-13.2 6.6-30.4 10.3-49.3 10.3s-36.1-3.7-49.3-10.3q-3-1.5-5.7-3.3m-63.99.1c-.34 14.6 15.52 20.6 25.49 23.6c6.3 1.8 13.3 3 20.5 3.5v-13.8c-8.8-.6-17.4-2.1-25.5-4.4c-7.79-2.3-14.69-5.2-20.49-8.9M321 178.8c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.2 41.1 5.2 59 0c12.7-3.7 25.4-10.7 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.3-47.3 6.3-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-48 4.2v13.7c9.4 1.9 19.9 2.6 30 2v-13.8c-10.1.5-20.3-.1-30-1.9M81.01 203.5c-.34 14.6 15.52 20.6 25.49 23.6c6.3 1.8 13.3 3 20.5 3.5v-13.8c-8.8-.6-17.4-2.1-25.5-4.4c-7.79-2.3-14.69-5.2-20.49-8.9m63.99 0c-.5 6 2.2 10.8 5.5 13.7c4.2 3.6 11 7.3 20 9.9c17.9 5.1 41.1 5.1 59 0c9-2.6 15.8-6.3 20-9.9c4.9-4.1 5.5-8 5.5-13.7c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m176 7.3c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.2 41.1 5.2 59 0c12.7-3.7 25.4-10.7 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.3-47.3 6.3-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-48 4.2v13.7c9.4 1.9 19.9 2.6 30 2v-13.8c-10.1.5-20.3-.1-30-1.9m-128 20.5v2.3c2.9 1 5.7 2.2 8.3 3.5c9 4.5 16.8 10.8 20.8 18.7c17.3 4.2 38.7 3.9 55.4-.9c9-2.6 15.8-6.3 20-9.9c4.9-4.1 5.5-8 5.5-13.7c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m176 7.3c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.2 41.1 5.2 59 0c12.7-3.7 25.4-10.7 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.3-47.3 6.3-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-48 4.2v13.7c9.4 1.9 19.9 2.6 30 2v-13.8c-10.1.5-20.3-.1-30-1.9m-169 2c-16.49 0-31.29 3.5-41.29 8.4c-9.9 5-13.7 10.6-13.7 14.6s3.8 9.6 13.7 14.6c10 5 24.8 8.4 41.29 8.4c16.5 0 31.3-3.4 41.2-8.4c10-5 13.8-10.6 13.8-14.6s-3.8-9.6-13.8-14.6c-9.9-4.9-24.7-8.4-41.2-8.4m151 18.5c-5.8 3.7-12.7 6.6-20.5 8.9c-18 5.1-38.6 6-57.5 2.6v13.7c16.8 3.5 36.7 2.9 52.5-1.6c12.6-3.7 25.4-10.8 25.5-23.6m66 7.3c-.4 10.3 8 16.1 13.8 19.1c0 0 .1.1.2.1v-12.4c-5.2-1.9-9.9-4.2-14-6.8M49.01 331.5c-.34 14.6 15.52 20.6 25.5 23.6c17.9 5.1 41.09 5.1 58.99 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.29 6.2-68.99 0c-7.8-2.3-14.7-5.2-20.5-8.9M177 343v13.6c4.9 1.1 10.2 1.8 15.7 2.2c2-5.3 5.6-9.9 10.2-13.8c-8.7.2-17.5-.5-25.9-2m87 2c-16.5 0-31.3 3.5-41.2 8.4c-10 5-13.8 10.6-13.8 14.6s3.8 9.6 13.8 14.6c9.9 5 24.7 8.4 41.2 8.4s31.3-3.4 41.2-8.4c10-5 13.8-10.6 13.8-14.6s-3.8-9.6-13.8-14.6c-9.9-4.9-24.7-8.4-41.2-8.4m89 2.4c-.1 14.8 15.2 20.7 25.5 23.7c17.9 5.1 41.1 5.1 59 0c12.7-3.7 25.4-10.8 25.5-23.7c-15.8 9.6-39.7 13.5-55 13.6c-19.6-1.6-39.9-3.6-55-13.6M49.01 363.5c-.34 14.6 15.52 20.6 25.5 23.6c17.9 5.1 41.09 5.1 58.99 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.29 6.2-68.99 0c-7.8-2.3-14.7-5.2-20.5-8.9m303.99 16c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.1 41.1 5.1 59 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-144 15.9c-.5 6 2.2 10.9 5.5 13.8c4.2 3.6 11 7.3 20 9.9c17.9 5.1 41.1 5.1 59 0c9-2.6 15.8-6.3 20-9.9s5.5-6.7 5.5-9.2v-4.6q-2.7 1.8-5.7 3.3c-13.2 6.6-30.4 10.3-49.3 10.3s-36.1-3.7-49.3-10.3q-3-1.5-5.7-3.3m-159.99.1c-.34 14.6 15.52 20.6 25.5 23.6c17.9 5.1 41.09 5.1 58.99 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.29 6.2-68.99 0c-7.8-2.3-14.7-5.2-20.5-8.9m303.99 16c-.2 14.7 15.4 20.6 25.5 23.6c17.9 5.1 41.1 5.1 59 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-303.99 16c-.34 14.6 15.52 20.6 25.5 23.6c17.9 5.1 41.09 5.1 58.99 0c12.6-3.7 25.4-10.8 25.5-23.6c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.29 6.2-68.99 0c-7.8-2.3-14.7-5.2-20.5-8.9m159.99 0c-.4 10.3 8 16.1 13.8 19.1c9.9 5 24.7 8.4 41.2 8.4s31.3-3.4 41.2-8.4c9.9-6.2 13.8-8.6 13.8-19.1c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m144 16c-.4 10.3 8 16.1 13.8 19.1c9.9 5 24.7 8.4 41.2 8.4s31.3-3.4 41.2-8.4c9.9-6.2 13.8-8.6 13.8-19.1c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.3 6.2-69 0c-7.8-2.3-14.7-5.2-20.5-8.9m-303.99 16c-.44 10.2 7.88 16.1 13.7 19.1c10 5 24.8 8.4 41.29 8.4c16.5 0 31.3-3.4 41.2-8.4c9.9-6.2 13.8-8.6 13.8-19.1c-5.8 3.7-12.7 6.6-20.5 8.9c-21.7 6.2-47.29 6.2-68.99 0c-7.8-2.3-14.7-5.2-20.5-8.9' stroke-width='13' stroke='%23000'/%3E%3C/svg%3E");
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
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'%3E%3Cpath fill='%23000' d='M21.6 29a1 1 0 0 0-1-1h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 1-1' class='clr-i-outline clr-i-outline-path-1' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='%23000' d='M22.54 24h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-2' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='%23000' d='M22 32h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-3' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='%23000' d='M32.7 32h-7a1 1 0 0 0 0 2h7a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-4' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='%23000' d='M33.7 28h-7a1 1 0 0 0 0 2h7a1 1 0 0 0 0-2' class='clr-i-outline clr-i-outline-path-5' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='%23000' d='M33.74 26a28 28 0 0 0-2.82-10.12a20.24 20.24 0 0 0-6.32-7.17L27 3.42a1 1 0 0 0-.07-1a1 1 0 0 0-.8-.42H9.8a1 1 0 0 0-.91 1.42l2.45 5.31a20.3 20.3 0 0 0-6.28 7.15c-2.15 4-2.82 8.89-3 12.28a3.6 3.6 0 0 0 1 2.71a3.8 3.8 0 0 0 2.74 1.07H12V30H5.72a1.68 1.68 0 0 1-1.21-.52a1.62 1.62 0 0 1-.45-1.23c.14-2.61.69-7.58 2.76-11.45a18 18 0 0 1 6.26-6.8h1a31 31 0 0 0-1.87 2.92a23 23 0 0 0-1.47 3.34l1.37.92a24 24 0 0 1 1.49-3.47A29 29 0 0 1 16.05 10h1a21.5 21.5 0 0 1 1.41 5a22.5 22.5 0 0 1 .32 3.86l1.58-1.11a24 24 0 0 0-.32-3A25 25 0 0 0 18.76 10h.78l.91-2h-7.24l-1.85-4h13.21l-2.5 5.47a10 10 0 0 1 1.23.78a18.6 18.6 0 0 1 5.86 6.57A26.6 26.6 0 0 1 31.73 26Z' class='clr-i-outline clr-i-outline-path-6' stroke-width='1' stroke='%23000'/%3E%3Cpath fill='none' d='M0 0h36v36H0z'/%3E%3C/svg%3E");
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
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23000' d='M241.1 21.91c-89.7-.15-172.99 2.47-171.94 8.27l22.08 36.14c59.36-5.1 115.96-6.95 172.66-6.57c53.2.35 106.5 2.67 162.1 6.09l31.2-33.28c-4.5-6.74-114.4-10.48-216.1-10.65m3.8 55.81c-49.1.12-98.3 1.99-149.69 6.32C73.42 207.7 61.35 358.6 89.71 451.7l-26.43 31c128.12 9.6 258.52 12.7 382.62 6.3L416 453.4c19.3-106.2 13.3-251.2.2-370.12c-51.2-3.06-100.3-5.1-149.3-5.49L401.1 201h-71.3c3.8 23.1 20.4 54.3 37.9 83.2c9.9 16.4 20 32.3 27.7 46.3S409 356.2 409 368c0 28-18.9 51-46.7 65.9c-27.8 15-65.1 23.1-106.3 23.1s-78.5-8.1-106.3-23.1C121.9 419 103 396 103 368c0-12 6.2-23.7 14.3-37.9s18.6-30.2 28.8-46.8c18-29.1 34.4-60.5 36.7-82.3H104zm11 14.37L152 183h48.3l.7 8.2c2.7 31.9-18.9 68-39.6 101.6c-10.4 16.8-20.8 32.7-28.4 46.2c-7.7 13.4-12 25-12 29c0 14.6 7 27.5 20.1 38.6c54.2-6.8 82-10.6 106.4-11.4c.6-8.5 1.7-17 3.1-25.2c-36.6 2.3-70.3-7.5-103.5-19.3c14.9-28.8 42.3-48.9 67-48.8c4.6 0 9.1.6 13.4 2.1c11.2 3.6 21.3 23.7 28 40.6c2.2-10.5 4.3-19.5 5.2-26.1v-.1c.9-6.2-1.6-24-4.8-38.7c-2.3-10.6-4.7-19.9-5.9-24.3c-6.3 0-11.9-1.2-15.6-5c-25.4-26.4-8.9-75.2 12.9-102.4c15.5 25.8 35.7 53.6 56 72.8c-4.4 14.8-12.4 26.7-27.9 32c-2.1.2-4.7.5-7.3.9c1.4 5.2 3.4 13 5.4 22.1c1.3 5.7 2.5 11.6 3.5 17.5c5.3-4.9 11.4-9.4 16.4-9.6h1.1c28 2.6 59.7 29.6 67.7 69c-27.5-12.6-59-5.3-83.9-30.2c-2.8 18.6-10.6 46.6-12.7 72.8c24.1.8 51.9 4.6 105.3 11.3c13.1-11.1 20.1-24 20.1-38.6c0-4.2-4.1-15.6-11.4-28.8c-7.3-13.3-17.2-29-27.3-45.7C332.2 260.3 311 224.2 311 192v-9h43.9zm.1 320.81c-22.3 0-45.9 3-90.7 8.7C189 432.5 220.8 439 256 439s67-6.5 90.7-17.4c-44.8-5.7-68.4-8.7-90.7-8.7' stroke-width='13' stroke='%23000'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        /* ===== SCROLLBAR ===== */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #FFF3E9; border-radius: 10px; }

        /* ===== LOADING DOTS ===== */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .loading-dot { animation: pulse-dot 1.4s ease-in-out infinite; }
        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        /* ===== METRIC RANGE BAR ===== */
        .metric-range .bar {
            position: relative;
            background: #e7e5e4;
            overflow: hidden;
            border-radius: 999px;
        }
        .metric-range .fill {
            position: absolute;
            left: 0; top: 0;
            height: 100%; width: 100%;
            border-radius: 999px;
            clip-path: inset(0 100% 0 0);
            transition: clip-path 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .metric-range[data-key="soil_moisture"] .fill {
            background: linear-gradient(90deg,
                #7F1D1D 0%,
                #DC2626 15%,
                #F97316 30%,
                #EAB308 45%,
                #22C55E 60%,
                #16A34A 75%,
                #3B82F6 100%
            );
        }

        .metric-range .tick {
            position: absolute;
            top: 0; width: 2px; height: 100%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.75);
        }
    </style>

</head>

<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-3 sm:px-6 py-3 border-b border-stone-200 bg-white shrink-0 gap-3 sm:gap-0">
        <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
            <?php include 'navbar.php'; ?>
            <div class="size-8 sm:size-9 bg-[#FFD7B6] rounded-xl flex items-center justify-center text-white shadow-sm shadow-primary/20 shrink-0">
                <span class="fluent--door-arrow-right-28-regular text-xl sm:text-2xl text-[#ff8021]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-sm sm:text-lg font-bold leading-tight text-[#1d130c] truncate">Outdoor System</h1>
                <p class="text-[9px] sm:text-[10px] text-stone-500 font-medium uppercase tracking-wider mt-0.5">Outdoor Dashboard Farm Intelligence</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 sm:gap-6 w-full sm:w-auto flex-nowrap">
            <!-- อัปเดตล่าสุด -->
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

            <!-- LEFT SECTION -->
            <div class="lg:col-span-10 flex flex-col gap-3 sm:gap-4">

                <!-- TOP ROW: รูปภาพ + กราฟ -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-10 gap-3 sm:gap-4">

                    <!-- รูปภาพ (Desktop: ยาวลงมา 2 rows) -->
                    <div class="sm:col-span-2 lg:col-span-4 lg:row-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm p-4 flex items-center justify-center min-h-[200px] sm:min-h-[250px] lg:min-h-full hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                        <div class="text-center text-stone-400">
                            <span class="material-symbols-outlined" style="font-size: 3rem;">image</span>
                            <p class="text-xs mt-2 font-medium">พื้นที่รูปภาพขยาย</p>
                            <p class="text-[10px] mt-1 text-stone-400">เครื่องมือ/อุปกรณ์</p>
                        </div>
                    </div>

                    <!-- กราฟ DO (บนขวา) -->
                    <div class="sm:col-span-2 lg:col-span-6 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
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

                    <!-- กราฟ Price (ล่างขวา) -->
                    <div class="sm:col-span-2 lg:col-span-6 bg-white border border-stone-200 rounded-2xl p-3 shadow-sm flex flex-col min-h-[200px] hover:ring-2 hover:ring-orange-400 transition-all duration-200">
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

                <!-- BOTTOM ROW: Sensor Metrics -->
                <div class="grid grid-cols-1 gap-3 sm:gap-4" id="metrics-cards">

                    <!-- Soil Moisture Card -->
                    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-stone-200 shadow-sm flex flex-col hover:ring-2 hover:ring-orange-400 transition-all duration-200" id="card-soil_moisture">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[8px] sm:text-[9px] font-bold text-stone-500 uppercase tracking-widest">อุณหภูมิ</span>
                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[8px] sm:text-[9px] font-bold uppercase status">--</span>
                        </div>
                        <div class="text-[8px] sm:text-[9px] font-bold text-stone-400 mb-1">(Soil Moisture)</div>
                        <div class="flex-1 flex items-center justify-center py-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-base sm:text-lg font-black text-black value">--</span>
                                <span class="text-xs sm:text-sm font-bold text-stone-400">%RH</span>
                            </div>
                        </div>
                        <div class="metric-range mt-2 hidden" data-key="soil_moisture">
                            <div class="flex justify-between text-[8px] font-bold leading-none mb-1">
                                <span class="label-left"></span>
                                <span class="label-right"></span>
                            </div>
                            <div class="relative h-1.5 rounded-full bar">
                                <div class="fill"></div>
                            </div>
                            <p class="text-[7px] text-stone-500 font-medium mt-2 text-center">ควรอยู่ระหว่าง 30-70 %RH</p>
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT SECTION (Sidebar) -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">

                <!-- Card 1: สิ่งที่ต้องทำวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-sm">rule</span>
                        <h3 class="text-[10px] font-bold text-stone-700">สิ่งที่ต้องทำวันนี้</h3>
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

                <!-- Card 2: ต้นทุนทรัพยากรวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="game-icons--coins text-[#ff8021] text-sm"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนทรัพยากรวันนี้</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2" id="resource-info">
                        <div class="col-span-2 flex items-center justify-center py-2">
                            <span class="text-[10px] text-stone-400">กำลังโหลด...</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: การให้น้ำและปุ๋ย -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="game-icons--fertilizer-bag text-[#ff8021] text-sm"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">การให้น้ำและปุ๋ย</h3>
                    </div>
                    <div>
                        <table class="w-full">
                            <tbody class="text-[9px] divide-y divide-stone-50">
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">DO</td>
                                    <td class="py-0.5 text-right font-bold text-success">3.0-7.0 mg/L</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">pH</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">7.5 - 8.5</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">EC</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">23K-45K μS/cm</td>
                                </tr>
                                <tr>
                                    <td class="py-0.5 text-stone-500 font-medium">Temp</td>
                                    <td class="py-0.5 text-right font-bold text-stone-700">28-32 °C</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card 4: ต้นทุนรวมวันนี้ -->
                <div class="bg-white border border-stone-200 rounded-2xl p-3 shadow-sm hover:ring-2 hover:ring-orange-400 transition-all duration-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="clarity--coin-bag-line text-[#ff8021] text-sm"></span>
                        <h3 class="text-[10px] font-bold text-stone-700">ต้นทุนรวมวันนี้</h3>
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
    <footer class="px-3 sm:px-6 py-2 border-t border-stone-200 bg-white flex items-center gap-2 shrink-0">
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

        // ========== SENSOR ==========
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

                setCardValue('soil_moisture', sensor.soil_moisture ?? sensor.do ?? null);

            } catch (err) {
                console.error('❌ loadSensorData error:', err);
            }
        }

        // ========== BADGE ==========
        function setBadge(statusEl, text, type) {
            const map = {
                success: 'status px-2 py-0.5 rounded-full bg-green-100 text-green-600 text-[9px] font-bold uppercase',
                warning: 'status px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-[9px] font-bold uppercase',
                danger:  'status px-2 py-0.5 rounded-full bg-red-100 text-red-600 text-[9px] font-bold uppercase',
                info:    'status px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[9px] font-bold uppercase',
                na:      'status px-2 py-0.5 rounded-full bg-stone-100 text-stone-500 text-[9px] font-bold uppercase',
                orange:  'status px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[9px] font-bold uppercase',
            };
            statusEl.textContent = text;
            statusEl.className = map[type] || map.na;
        }

        // ========== STATUS ==========
        function getStatusByKey(key, v) {
            if (key === 'soil_moisture') {
                if (v >= 40 && v <= 60) return { text: 'เหมาะสม', type: 'success' };
                if (v >= 30 && v < 40)  return { text: 'แล้งเล็กน้อย', type: 'warning' };
                if (v > 60 && v <= 70)  return { text: 'ชื้นเล็กน้อย', type: 'info' };
                if (v < 30)             return { text: 'แล้งมาก', type: 'danger' };
                return { text: 'ชื้นมาก', type: 'danger' };
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
            valueEl.textContent = v.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });

            const st = getStatusByKey(key, v);
            setBadge(statusEl, st.text, st.type);
            updateMetricBar(key, v);
        }

        // ========== BAR CONFIG ==========
        const BAR_CONFIG = {
            soil_moisture: {
                min: 0, max: 100,
                leftLabel: '30%', rightLabel: '70%',
                leftColor: '#DC2626', rightColor: '#3B82F6',
            },
        };

        function initMetricBars() {
            document.querySelectorAll('.metric-range').forEach(wrap => {
                const key = wrap.dataset.key;
                const cfg = BAR_CONFIG[key];
                if (!cfg) return;

                wrap.classList.remove('hidden');

                const left  = wrap.querySelector('.label-left');
                const right = wrap.querySelector('.label-right');
                left.textContent  = cfg.leftLabel || '';
                right.textContent = cfg.rightLabel || '';
                left.style.color  = cfg.leftColor || '#C73434';
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

            const v      = Number(value);
            const pct    = ((v - cfg.min) / (cfg.max - cfg.min)) * 100;
            const safePct = clamp(pct, 0, 100);
            if (fill) fill.style.clipPath = `inset(0 ${100 - safePct}% 0 0)`;
        }

        function clamp(n, min, max) { return Math.max(min, Math.min(max, n)); }

        // ========== FEEDING ==========
        async function loadFeedingInfo() {
            const mealsEl    = document.getElementById('feeding-meals');
            const increaseEl = document.getElementById('feeding-increase');
            const totalEl    = document.getElementById('feeding-total');
            if (!mealsEl || !increaseEl || !totalEl) return;

            mealsEl.textContent = '--';
            increaseEl.textContent = '--';
            totalEl.textContent = '--';

            try {
                const res  = await fetch('/dashboard/api/food_preparation.php', { cache: 'no-store' });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const rows = await res.json();
                if (!Array.isArray(rows) || rows.length === 0) throw new Error('ไม่มีข้อมูล');

                rows.forEach(item => {
                    const label = (item.label || '').toLowerCase();
                    const value = item.value || '--';
                    if (label.includes('จำนวนมื้อ')  || item.id == 10) mealsEl.textContent    = value;
                    else if (label.includes('เพิ่มต่อมื้อ') || item.id == 15) increaseEl.textContent = value;
                    else if (label.includes('ปริมาณรวม')  || item.id == 14) totalEl.textContent    = value;
                });
            } catch (err) {
                console.error('❌ loadFeedingInfo error:', err);
                mealsEl.textContent = 'N/A';
                increaseEl.textContent = 'N/A';
                totalEl.textContent = 'N/A';
            }
        }

        // ========== RESOURCE ==========
        let resourceTimer = null;
        let resourceIndex = 0;
        let resourceTotal = 0;

        async function loadResourceToday() {
            const box = document.getElementById("resource-info");
            if (!box) return;

            box.classList.remove("grid", "grid-cols-2", "gap-2");
            box.classList.add("flex", "overflow-x-auto", "snap-x", "snap-mandatory", "scroll-smooth", "no-scrollbar");

            if (typeof stopResourceAutoSlide === "function") stopResourceAutoSlide();

            box.innerHTML = `<div class="w-full shrink-0 snap-start flex items-center justify-center"><span class="text-xs text-stone-400">กำลังโหลด...</span></div>`;

            try {
                const res  = await fetch("/dashboard/api/resource_to_day.php", { cache: "no-store" });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();

                const item = Array.isArray(data)
                    ? data.find(x => String(x.id) === "1")
                    : (String(data?.id) === "1" ? data : null);

                if (!item) {
                    box.innerHTML = `<div class="w-full shrink-0 snap-start flex items-center justify-center"><span class="text-xs text-stone-400">ไม่พบข้อมูล id=1</span></div>`;
                    return;
                }

                box.innerHTML = `<div class="w-full h-full shrink-0 snap-start"><div class="w-full h-full">${renderResourceCard(item)}</div></div>`;

            } catch (err) {
                console.error("❌ loadResourceToday error:", err);
                box.innerHTML = `<div class="w-full shrink-0 snap-start flex items-center justify-center"><span class="text-xs text-red-500 font-semibold">โหลดข้อมูลไม่สำเร็จ</span></div>`;
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
            const w   = box.clientWidth || 1;
            const idx = Math.round(box.scrollLeft / w);
            if (Number.isFinite(idx)) resourceIndex = clamp(idx, 0, Math.max(0, resourceTotal - 1));
        }

        function renderResourceCard(item) {
            const water      = toNumber(item.water_value);
            const waterUnit  = item.water_unit ?? "m3";
            const elec       = toNumber(item.electric_value);
            const elecUnit   = item.electric_unit ?? "kWh";
            const updated    = item.updated_at ?? "";

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
                    <div class="mt-2 text-[9px] text-stone-400 font-semibold text-end">อัปเดต: ${escapeHtml(updated)}</div>
                </div>
            `;
        }

        function toNumber(v)     { const n = parseFloat(v); return Number.isFinite(n) ? n : 0; }
        function formatNumber(n) { return (Number.isFinite(n) ? n : 0).toFixed(1); }
        function escapeHtml(text) { const d = document.createElement('div'); d.textContent = text; return d.innerHTML; }

        // ========== DO TREND CHART ==========
        let doChart = null;

        async function loadDoTrendData() {
            const loading = document.getElementById('do-loading');
            try {
                const res  = await fetch('/dashboard/api/monitor_trend.php', { cache: 'no-store' });
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
                    return `${d.getHours().toString().padStart(2,'0')}:${d.getMinutes().toString().padStart(2,'0')}`;
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
                    labels,
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
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y.toFixed(2)} mg/L` } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9 }, color: '#78716c', maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                        y: { beginAtZero: true, max: 10, ticks: { font: { size: 9 }, color: '#78716c', callback: v => v.toFixed(1) }, grid: { color: 'rgba(0,0,0,0.05)' } }
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

                const labels  = raw.map(r => r.event_month);
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
                        { label: '50 ตัว/กก.', data: price50, borderColor: '#ff8021',              backgroundColor: 'rgba(255,128,33,0.15)', tension: 0.35, fill: true, pointRadius: 2.5 },
                        { label: '70 ตัว/กก.', data: price70, borderColor: 'rgba(255,128,33,0.4)', backgroundColor: 'rgba(255,128,33,0.08)', tension: 0.35, fill: true, pointRadius: 2   }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.raw} บาท/กก.` } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#78716c' } },
                        y: { ticks: { font: { size: 10 }, color: '#78716c', callback: v => v + ' ฿' } }
                    }
                }
            });
        }
    </script>
</body>

</html>