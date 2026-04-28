<?php
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dairy Cow Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'IBM Plex Sans Thai', system-ui, sans-serif;
            background: #F5F3EE;
            color: #1C1917;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        ::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #D3D1C7;
            border-radius: 4px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #E7E5DF;
            transition: box-shadow .15s, border-color .15s;
        }

        .card:hover {
            border-color: #EF9F27;
            box-shadow: 0 0 0 2px #EF9F2722;
        }

        .sec-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            font-weight: 600;
            color: #44403C;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 8px;
        }

        .sec-title .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .72rem;
        }

        .dash-table th {
            text-align: left;
            padding: 5px 6px;
            color: #78716C;
            font-weight: 500;
            border-bottom: 1px solid #E7E5DF;
            white-space: nowrap;
        }

        .dash-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #F5F3EE;
            vertical-align: middle;
        }

        .dash-table tr:last-child td {
            border-bottom: none;
        }

        .dash-table tr:hover td {
            background: #FAFAF8;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .progress-track {
            flex: 1;
            height: 5px;
            background: #F1EFE8;
            border-radius: 3px;
            overflow: hidden;
            min-width: 40px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
        }

        .metric-sub {
            display: inline-block;
            margin-top: 3px;
            font-size: .65rem;
            font-weight: 600;
            padding: 1px 7px;
            border-radius: 20px;
        }

        .disease-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            background: #FAFAF8;
            border-radius: 8px;
            font-size: .7rem;
        }

        .milk-bar-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 0;
            font-size: .67rem;
            border-bottom: 1px solid #F5F3EE;
        }

        .milk-bar-wrap:last-child {
            border-bottom: none;
        }

        .profit-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 8px;
            border-radius: 8px;
            font-size: .72rem;
        }

        .profit-highlight {
            background: #FAEEDA;
            border: 1px solid #FAC775;
        }

        input[type=range] {
            width: 100%;
            height: 3px;
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            background: #E7E5DF;
            border-radius: 2px;
            outline: none;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #EF9F27;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header
        style="background:#fff;border-bottom:1px solid #E7E5DF;padding:8px 16px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div
                style="width:36px;height:36px;border-radius:10px;background:#FAEEDA;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 512 512">
                    <path fill="currentColor"
                        d="M468.958 108.958c-27.507 2.08-48.997 7.94-71.375 22.572c-5.333-2.214-12.62-17.738-16-16c-11.82 6.08-14.892 19.555-4.916 32.817l-59.084 9.916c-24.776 3.341-49.567 4.838-74.187 5.334c1.326 3.832 2.96 7.636 4.812 10.05c5.219 6.802 20.323 6.21 21.07 14.75c1.935 22.098-24.876 47.415-47.056 47.057c-15.401-.248-17.017-28.762-31.604-33.713c-19.097-6.482-41.62 18.77-59.699 9.832c-15.267-7.547-24.992-39.8-27.836-50.41c-10.213-.127-20.327-.142-30.316.035c-12.564.366-22.902 5.645-29.408 14.239c-8.676 11.458-11.652 26.658-13.254 42.925c-1.78 18.057 6.147 53.007 5.517 70.282c-.504 13.85-7.493 11.87-11.912 18.888c-13.52 21.47 8.894 20.83 17.014 5.56c12.482-23.473 4.253-63.11 7.195-92.974c1.855-35.76 10.597-23.937 15.664-24.588c-4.2 13.065-6.21 30.962-7 51.334c6.895-2.342 36.498-11.6 42.73-.174c6.872 12.598-27.802 22.016-23.878 35.819c2.464 8.666 22.95 2.378 24.582 11.238c3.322 18.035-32.13 38.713-42.236 44.209c.812 23.329 1.564 45.567 1.238 65.086H88.91c-4.234-16.543-12.038-49.944-4.06-55.084c21.425-18.091 29.836-37.484 42.732-56.428c8.755 2.556 16.92 4.787 24.782 6.672c3.553.972 7.244 1.771 10.984 2.44c24.859 4.967 61.553 5.678 90.783-.172c3.76 34.12 7.263 68.452 4.602 102.572h28.957c-12.375-26.902-4.263-65.044 13.892-86.27l44.934-33.462c24.881-16.384 42.93-37.996 55.982-63.38c30.402 3.413 57.086 3.29 77.192-.786l12.84-19.55c-24.257-17.857-43.3-36.585-62.948-58.13c10.063-14.533 25.027-22.765 39.375-32.506zm-39.375 54.572a8 8 0 1 1 0 16a8 8 0 0 1 0-16M366.2 183.481c5.029 9.822-26.17 10.808-24.933 21.772c.998 8.847 22.204 3.839 23.53 12.643c3.818 25.373-28.44 53.805-54.08 54.78c-14.262.544-34.902-14.06-32.308-28.093c2.605-14.092 34.551-1.657 40.383-14.748c4.724-10.603-18.352-22.01-12.992-32.307c6.264-12.032 30.364-22.553 41.934-22.646s15.606 3.347 18.466 8.6zm-26.585 126.346l-34.707 23.96l6.464 69.255h34.414c-11.783-22.454-15.58-55.506-6.171-93.215m-204.561 1.41c-6.047 12.184-14.147 21.97-22.174 31.242c5.97 3.235 11.648 5.414 17.154 6.614c11.218 2.443 21.636.333 29.948-4.408c10.056-5.737 17.521-14.452 24.115-23.368c-14.615-.869-32.96-2.962-49.043-10.08m24.252 52c-8.737 2.585-17.452 3.7-25.566 2.96c5.167 12.624 10.45 24.152 15.824 36.845h28.306c-10.393-18.48-16.148-29.285-18.564-39.805" />
                </svg>
            </div>
            <div>
                <div style="font-size:.85rem;font-weight:700;color:#1C1917;">Dairy Cow Management Dashboard</div>
                <div style="font-size:.65rem;color:#A8A29E;">ระบบจัดการฟาร์มวัวนม · อัปเดตล่าสุดวันนี้</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <span
                style="font-size:.7rem;font-weight:600;padding:4px 10px;border-radius:20px;background:#EAF3DE;color:#3B6D11;border:1px solid #97C459;">●
                Live</span>
            <span style="font-size:.65rem;color:#A8A29E;" class="mono" id="clock"></span>
        </div>
    </header>

    <!-- MAIN -->
    <main style="flex:1;overflow:hidden;display:flex;flex-direction:column;gap:10px;padding:10px;min-height:0;">

        <!-- ROW 1: METRICS -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;flex-shrink:0;">

            <div class="card" style="padding:12px 14px; position: relative;">
                <div
                    style="font-size:.65rem; font-weight:600;color:#78716C;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">
                    วัวทั้งหมด</div>
                <div class="mono" style="font-size:1.4rem;font-weight:700;color:#1C1917;line-height:1.2;">42 ตัว</div>
                <span class="metric-sub"
                    style="position:absolute; top:10px; right:10px; background:#FAEEDA; color:#854F0B; padding:4px 8px; border-radius:6px;">6
                    ระยะการเลี้ยง</span>
            </div>

            <div class="card" style="padding:12px 14px; position:relative;">
                <div
                    style="font-size:.65rem;font-weight:600;color:#78716C;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">
                    ผลผลิตนม/วัน</div>
                <div class="mono" style="font-size:1.4rem;font-weight:700;color:#1C1917;line-height:1.2;">18 ลิตร</div>
                <span class="metric-sub"
                    style="position:absolute; top:10px; right:10px; background:#E1F5EE;color:#0F6E56; padding:4px 8px; border-radius:6px;">เฉลี่ย/ตัว/วัน</span>
            </div>

            <div class="card" style="padding:12px 14px; position: relative;">
                <div
                    style="font-size:.65rem;font-weight:600;color:#78716C;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">
                    ต้นทุนรายวัน</div>
                <div class="mono" style="font-size:1.4rem; font-weight:700; color:#1C1917; line-height:1.2;">฿6,240
                </div>
                <span class="metric-sub"
                    style="position:absolute; top:10px; right:10px; background:#FAECE7;color:#993C1D; padding:4px 8px; border-radius:6px;">เฉลี่ย
                    ฿180/ตัว</span>
            </div>

            <div class="card" style="padding:12px 14px; position: relative;">
                <div
                    style="font-size:.65rem;font-weight:600;color:#78716C;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;">
                    รายได้นม/วัน</div>
                <div class="mono" style="font-size:1.4rem;font-weight:700;color:#1C1917;line-height:1.2;">฿9,720</div>
                <span class="metric-sub"
                    style="position:absolute; top:10px; right:10px; background:#EAF3DE;color:#3B6D11; padding:4px 8px; border-radius:6px;">กำไรสุทธิ
                    ฿3,480</span>
            </div>

        </div>

        <!-- ROW 2 -->
        <div style="flex:1;display:grid;grid-template-columns:1fr 252px;gap:10px;min-height:0;overflow:hidden;">

            <!-- LEFT -->
            <div style="display:flex;flex-direction:column;gap:10px;min-height:0;overflow:hidden;">

                <!-- TOP: Stage + Disease+Cost — ให้ flex-shrink:0 และกำหนด height ที่กรอบระยะการเลี้ยง -->
                <div style="display:grid; grid-template-columns:1fr 380px; gap:10px; flex-shrink:0;">

                    <!-- ระยะการเลี้ยง — ลดความสูงลงด้วย max-height -->
                    <div class="card" style="padding:12px;display:flex;flex-direction:column;">
                        <div class="sec-title">
                            <span class="dot" style="background:#EF9F27;"></span>ระยะการเลี้ยงวัวนม
                        </div>
                        <div style="flex:1;overflow-y:auto;min-height:0;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>ระยะ</th>
                                        <th>อายุ</th>
                                        <th>น้ำหนักเป้าหมาย</th>
                                        <th>เป้าหมาย</th>
                                        <th style="min-width:90px;">ความคืบหน้า</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight:700;">ลูกวัว</td>
                                        <td style="color:#78716C;white-space:nowrap;">0–3 เดือน</td>
                                        <td class="mono" style="color:#78716C;">35–80 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">สร้างภูมิ + กระเพาะ</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:15%;background:#EF9F27;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">15%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#E7E5DF;color:#78716C;">ไม่ควรขาย</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">หย่านม</td>
                                        <td style="color:#78716C;white-space:nowrap;">4–8 เดือน</td>
                                        <td class="mono" style="color:#78716C;">80–150 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">พัฒนาโครงร่าง</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:30%;background:#EF9F27;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">30%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#E7E5DF;color:#78716C;">ไม่ควรขาย</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">รุ่น</td>
                                        <td style="color:#78716C;white-space:nowrap;">9–15 เดือน</td>
                                        <td class="mono" style="color:#78716C;">150–280 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">เตรียมผสม</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:45%;background:#EF9F27;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">45%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#E7E5DF;color:#78716C;">ไม่ควรขาย</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">สาวท้อง</td>
                                        <td style="color:#78716C;white-space:nowrap;">16–24 เดือน</td>
                                        <td class="mono" style="color:#78716C;">280–420 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">เตรียมรีดนม</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:65%;background:#EF9F27;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">65%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#E7E5DF;color:#78716C;">ไม่ควรขาย</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:600;color:#1C1917;">แม่โครีดนม</td>
                                        <td style="color:#78716C;white-space:nowrap;">24–36 เดือน</td>
                                        <td class="mono" style="color:#78716C;">420–550 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">ผลิตน้ำนมสูงสุด</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:85%;background:#378ADD;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">85%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#EAF3F8;color:#378ADD;border:1px solid #cce0ff;">กำลังผลิต</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:600;color:#E24B4A;">ปลดระวาง</td>
                                        <td style="color:#78716C;white-space:nowrap;">&gt;6 ปี</td>
                                        <td class="mono" style="color:#78716C;">&gt;550 kg</td>
                                        <td style="color:#78716C;font-size:.62rem;">น้ำนมลด</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div class="progress-track">
                                                    <div class="progress-fill" style="width:100%;background:#E24B4A;">
                                                    </div>
                                                </div>
                                                <span class="mono" style="font-size:.59rem;color:#A8A29E;">100%</span>
                                            </div>
                                        </td>
                                        <td><span class="badge"
                                                style="background:#FCEBEB;color:#E24B4A;border:1px solid #f8c2c2;">เหมาะขาย!</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- โรคที่ต้องระวัง + ต้นทุนอาหาร/ระยะ -->
                    <div style="display:flex; flex-direction:column; gap:10px; min-height:0;">

                        <!-- โรคที่ต้องระวัง -->
                        <div class="card" style="padding:12px;display:flex;flex-direction:column;flex:1;">
                            <div class="sec-title">
                                <span class="dot" style="background:#E24B4A;"></span>โรคที่ต้องระวัง
                            </div>
                            <div style="display:flex;flex-direction:column;gap:5px;">
                                <div class="disease-row">
                                    <span
                                        style="width:7px; height:7px; border-radius:50%; background:#E24B4A; flex-shrink:0;"></span>
                                    <span style="color:#A8A29E; min-width:62px; font-size:.6rem;">0–3 เดือน</span>
                                    <span style="font-weight:700; flex:1;">ท้องเสีย</span>
                                    <span style="color:#A8A29E; font-size:.6rem;">นมน้ำเหลืองไม่พอ</span>
                                </div>
                                <div class="disease-row">
                                    <span
                                        style="width:7px; height:7px; border-radius:50%; background:#EF9F27; flex-shrink:0;"></span>
                                    <span style="color:#A8A29E; min-width:62px; font-size:.6rem;">4–8 เดือน</span>
                                    <span style="font-weight:700; flex:1;">ปอดอักเสบ</span>
                                    <span style="color:#A8A29E; font-size:.6rem;">อากาศชื้น</span>
                                </div>
                                <div class="disease-row">
                                    <span
                                        style="width:7px;height:7px;border-radius:50%;background:#1D9E75;flex-shrink:0;"></span>
                                    <span style="color:#A8A29E;min-width:62px;font-size:.6rem;">รุ่น</span>
                                    <span style="font-weight:700;flex:1;">พยาธิ</span>
                                    <span style="color:#A8A29E;font-size:.6rem;">เล็มหญ้า</span>
                                </div>
                                <div class="disease-row">
                                    <span
                                        style="width:7px;height:7px;border-radius:50%;background:#E24B4A;flex-shrink:0;"></span>
                                    <span style="color:#A8A29E;min-width:62px;font-size:.6rem;">คลอด</span>
                                    <span style="font-weight:700;flex:1;">ไข้น้ำนม</span>
                                    <span style="color:#A8A29E;font-size:.6rem;">แคลเซียมต่ำ</span>
                                </div>
                                <div class="disease-row">
                                    <span
                                        style="width:7px;height:7px;border-radius:50%;background:#E24B4A;flex-shrink:0;"></span>
                                    <span style="color:#A8A29E;min-width:62px;font-size:.6rem;">รีดนม</span>
                                    <span style="font-weight:700;flex:1;">เต้านมอักเสบ</span>
                                    <span style="color:#A8A29E;font-size:.6rem;">สุขอนามัยรีด</span>
                                </div>
                                <div
                                    style="display:flex; gap:10px; padding-top:4px; font-size:.59rem; color:#A8A29E; flex-wrap:wrap; justify-content:flex-end;">
                                    <span style="display:flex;align-items:center;gap:3px;"><span
                                            style="width:6px;height:6px;border-radius:50%;background:#E24B4A;display:inline-block;"></span>สูง</span>
                                    <span style="display:flex;align-items:center;gap:3px;"><span
                                            style="width:6px;height:6px;border-radius:50%;background:#EF9F27;display:inline-block;"></span>กลาง</span>
                                    <span style="display:flex;align-items:center;gap:3px;"><span
                                            style="width:6px;height:6px;border-radius:50%;background:#1D9E75;display:inline-block;"></span>ต่ำ</span>
                                </div>
                            </div>
                        </div>

                        <!-- ต้นทุนอาหาร/ระยะ -->
                        <div class="card" style="padding:12px; flex:1;">
                            <div class="sec-title"><span class="dot" style="background:#EF9F27;"></span>ต้นทุนอาหาร/ระยะ
                            </div>
                            <div style="display:flex;flex-direction:column;gap:1px;">
                                <div
                                    style="display:flex; justify-content:space-between; align-items:center; padding:4px 0;border-bottom:1px solid #F5F3EE;font-size:.68rem;">
                                    <span style="font-weight:600; color:#44403C;">ลูกวัว</span>
                                    <span style="color:#78716C;">35–45 ฿/วัน</span>
                                    <span class="mono" style="font-weight:600; color:#EF9F27;">~฿1,200/เดือน</span>
                                </div>
                                <div
                                    style="display:flex; justify-content:space-between; align-items:center; padding:4px 0;border-bottom:1px solid #F5F3EE;font-size:.68rem;">
                                    <span style="font-weight:600;color:#44403C;">รุ่น</span>
                                    <span style="color:#78716C;">60–80 ฿/วัน</span>
                                    <span class="mono" style="font-weight:600;color:#EF9F27;">~฿2,200/เดือน</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;border-bottom:1px solid #F5F3EE;font-size:.68rem;">
                                    <span style="font-weight:600;color:#44403C;">สาวท้อง</span>
                                    <span style="color:#78716C;">90–120 ฿/วัน</span>
                                    <span class="mono" style="font-weight:600;color:#EF9F27;">~฿3,200/เดือน</span>
                                </div>
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;font-size:.68rem;">
                                    <span style="font-weight:600;color:#44403C;">รีดนม</span>
                                    <span style="color:#78716C;">180–250 ฿/วัน</span>
                                    <span class="mono" style="font-weight:600;color:#EF9F27;">~฿6,750/เดือน</span>
                                </div>
                            </div>
                        </div>

                    </div><!-- /right column wrapper -->
                </div>

                <!-- BOTTOM: Feed + Chart + Milk -->
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; flex:1; min-height:0;">

                    <!-- ตารางอาหาร -->
                    <div class="card" style="padding:12px;display:flex;flex-direction:column;min-height:0;">
                        <div class="sec-title"><span class="dot" style="background:#EF9F27;"></span>ตารางอาหารวัวนม
                        </div>
                        <div style="flex:1;overflow-y:auto;min-height:0;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>ระยะ</th>
                                        <th>อาหารหลัก</th>
                                        <th>อาหารข้น</th>
                                        <th>กิน/วัน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight:700;">ลูกวัว</td>
                                        <td style="color:#78716C;font-size:.62rem;">นม + Starter</td>
                                        <td style="color:#78716C;font-size:.62rem;">18–20% โปรตีน</td>
                                        <td class="mono" style="color:#EF9F27;font-size:.62rem;font-weight:700;">2–3%BW
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">หย่านม</td>
                                        <td style="color:#78716C;font-size:.62rem;">หญ้าอ่อน</td>
                                        <td style="color:#78716C;font-size:.62rem;">1 kg</td>
                                        <td class="mono" style="color:#EF9F27;font-size:.62rem;font-weight:700;">3%BW
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">รุ่น</td>
                                        <td style="color:#78716C;font-size:.62rem;">หญ้าสด</td>
                                        <td style="color:#78716C;font-size:.62rem;">1–2 kg</td>
                                        <td class="mono" style="color:#EF9F27;font-size:.62rem;font-weight:700;">2.5–3%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700;">สาวท้อง</td>
                                        <td style="color:#78716C;font-size:.62rem;">หญ้าคุณภาพ</td>
                                        <td style="color:#78716C;font-size:.62rem;">2–3 kg</td>
                                        <td class="mono" style="color:#EF9F27;font-size:.62rem;font-weight:700;">3%</td>
                                    </tr>
                                    <tr style="background:#FAEEDA;">
                                        <td style="font-weight:600;color:#1C1917;">รีดนม</td>
                                        <td style="color:#78716C;font-size:.62rem;">หญ้า + TMR</td>
                                        <td style="color:#78716C;font-size:.62rem;">1 kg/นม 2 ลิตร</td>
                                        <td class="mono" style="color:#BA7517;font-size:.62rem;font-weight:700;">3–4%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Weight Chart -->
                    <div class="card" style="padding:12px;display:flex;flex-direction:column;gap:6px;min-height:0;">
                        <div class="sec-title"><span class="dot" style="background:#EF9F27;"></span>น้ำหนักมาตรฐาน</div>
                        <div style="position:relative;flex:1;min-height:0;">
                            <canvas id="weightChart" aria-label="กราฟน้ำหนักมาตรฐานวัวนม"></canvas>
                        </div>
                    </div>

                    <!-- ผลผลิตนม -->
                    <div class="card" style="padding:12px;display:flex;flex-direction:column;min-height:0;">
                        <div class="sec-title"><span class="dot" style="background:#378ADD;"></span>ผลผลิตนมตามระยะ
                        </div>
                        <div style="display:flex;flex-direction:column;flex:1;justify-content:space-between;">
                            <div>
                                <div class="milk-bar-wrap">
                                    <span style="color:#A8A29E;min-width:68px;font-size:.61rem;">ต้นรีดนม</span>
                                    <div
                                        style="flex:1;height:5px;background:#E7E5DF;border-radius:3px;overflow:hidden;">
                                        <div style="width:57%;height:100%;background:#EF9F27;border-radius:3px;"></div>
                                    </div>
                                    <span class="mono"
                                        style="font-size:.61rem;color:#EF9F27;min-width:44px;text-align:right;font-weight:700;">15–20L</span>
                                </div>
                                <div class="milk-bar-wrap">
                                    <span style="color:#A8A29E;min-width:68px;font-size:.61rem;">พีคผลิต</span>
                                    <div
                                        style="flex:1;height:5px;background:#E7E5DF;border-radius:3px;overflow:hidden;">
                                        <div style="width:100%;height:100%;background:#EF9F27;border-radius:3px;"></div>
                                    </div>
                                    <span class="mono"
                                        style="font-size:.61rem;color:#BA7517;min-width:44px;text-align:right;font-weight:700;">25–35L</span>
                                </div>
                                <div class="milk-bar-wrap">
                                    <span style="color:#A8A29E;min-width:68px;font-size:.61rem;">กลางรีดนม</span>
                                    <div
                                        style="flex:1;height:5px;background:#E7E5DF;border-radius:3px;overflow:hidden;">
                                        <div style="width:51%;height:100%;background:#378ADD;border-radius:3px;"></div>
                                    </div>
                                    <span class="mono"
                                        style="font-size:.61rem;color:#378ADD;min-width:44px;text-align:right;font-weight:700;">12–18L</span>
                                </div>
                                <div class="milk-bar-wrap">
                                    <span style="color:#A8A29E;min-width:68px;font-size:.61rem;">ปลายรีดนม</span>
                                    <div
                                        style="flex:1;height:5px;background:#E7E5DF;border-radius:3px;overflow:hidden;">
                                        <div style="width:34%;height:100%;background:#78716C;border-radius:3px;"></div>
                                    </div>
                                    <span class="mono"
                                        style="font-size:.61rem;color:#78716C;min-width:44px;text-align:right;font-weight:700;">8–12L</span>
                                </div>
                                <div class="milk-bar-wrap">
                                    <span style="color:#A8A29E;min-width:68px;font-size:.61rem;">ปลดระวาง</span>
                                    <div
                                        style="flex:1;height:5px;background:#E7E5DF;border-radius:3px;overflow:hidden;">
                                        <div style="width:22%;height:100%;background:#E24B4A;border-radius:3px;"></div>
                                    </div>
                                    <span class="mono"
                                        style="font-size:.61rem;color:#E24B4A;min-width:44px;text-align:right;font-weight:700;">&lt;8L</span>
                                </div>
                            </div>
                            <div>
                                <div style="height:1px;background:#E7E5DF;margin:5px 0;">
                                </div>
                                <div style="font-size:.6rem;color:#78716C;display:flex; justify-content:flex-end;">
                                    ราคานม: <span class="mono" style="color:#EF9F27;font-weight:700;">
                                        ฿18–22/ลิตร</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT: Calculator + Formula stacked -->
            <div style="display:flex; flex-direction:column; gap:10px; min-height:0; overflow:hidden;">

                <!-- Calculator -->
                <div class="card"
                    style="padding:12px; display:flex; flex-direction:column; gap:10px; overflow-y:auto; flex:1; min-height:0;">
                    <div class="sec-title"><span class="dot" style="background:#EF9F27;"></span>คำนวณรายได้นม</div>

                    <div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:.67rem;color:#78716C;margin-bottom:4px;">
                            <span>จำนวนแม่โครีดนม</span>
                            <span class="mono" id="num-out" style="font-weight:600;color:#1C1917;">15 ตัว</span>
                        </div>
                        <input type="range" id="num-cattle" min="1" max="42" value="15" step="1" oninput="calcIncome()">
                    </div>

                    <div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:.67rem;color:#78716C;margin-bottom:4px;">
                            <span>ผลผลิตเฉลี่ย (ลิตร/ตัว/วัน)</span>
                            <span class="mono" id="milk-out" style="font-weight:600;color:#1C1917;">18 ลิตร</span>
                        </div>
                        <input type="range" id="milk-prod" min="5" max="35" value="18" step="1" oninput="calcIncome()">
                    </div>

                    <div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:.67rem;color:#78716C;margin-bottom:4px;">
                            <span>ราคาขายนม (฿/ลิตร)</span>
                            <span class="mono" id="price-out" style="font-weight:600;color:#1C1917;">20 ฿</span>
                        </div>
                        <input type="range" id="milk-price" min="15" max="25" value="20" step="1"
                            oninput="calcIncome()">
                    </div>

                    <div style="height:1px;background:#E7E5DF;"></div>

                    <div style="display:flex;flex-direction:column;gap:5px;">
                        <div class="profit-row" style="background:#FAFAF8;">
                            <span style="color:#78716C;">รายได้/วัน</span>
                            <span class="mono" style="font-weight:600;" id="p-day">฿0</span>
                        </div>
                        <div class="profit-row" style="background:#FAFAF8;">
                            <span style="color:#78716C;">รายได้/เดือน</span>
                            <span class="mono" style="font-weight:600;" id="p-month">฿0</span>
                        </div>
                        <div class="profit-row profit-highlight">
                            <span style="font-weight:700;color:#854F0B;">กำไรสุทธิ/เดือน</span>
                            <span class="mono" style="font-size:.9rem;font-weight:700;" id="p-profit">฿0</span>
                        </div>
                    </div>

                </div>

                <!-- สูตรอาหาร card -->
                <div class="card" style="padding:12px;flex-shrink:0;">
                    <div class="sec-title"><span class="dot" style="background:#1D9E75;"></span>สูตรอาหาร (100 kg)</div>
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>วัตถุดิบ</th>
                                <th style="text-align:right;">แม่แห้ง</th>
                                <th style="text-align:right;">รีดนม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ข้าวโพดบด</td>
                                <td class="mono" style="text-align:right;color:#78716C;">30 kg</td>
                                <td class="mono" style="text-align:right;color:#EF9F27;font-weight:700;">35 kg</td>
                            </tr>
                            <tr>
                                <td>มันเส้น</td>
                                <td class="mono" style="text-align:right;color:#78716C;">25 kg</td>
                                <td class="mono" style="text-align:right;color:#EF9F27;font-weight:700;">25 kg</td>
                            </tr>
                            <tr>
                                <td>รำละเอียด</td>
                                <td class="mono" style="text-align:right;color:#78716C;">25 kg</td>
                                <td class="mono" style="text-align:right;color:#EF9F27;font-weight:700;">15 kg</td>
                            </tr>
                            <tr>
                                <td>กากถั่วเหลือง</td>
                                <td class="mono" style="text-align:right;color:#78716C;">15 kg</td>
                                <td class="mono" style="text-align:right;color:#EF9F27;font-weight:700;">20 kg</td>
                            </tr>
                            <tr>
                                <td>เกลือแร่</td>
                                <td class="mono" style="text-align:right;color:#78716C;">3 kg</td>
                                <td class="mono" style="text-align:right;color:#EF9F27;font-weight:700;">3 kg</td>
                            </tr>
                            <tr>
                                <td>ไขมันเสริม</td>
                                <td class="mono" style="text-align:right;color:#78716C;">–</td>
                                <td class="mono" style="text-align:right;color:#BA7517;font-weight:700;">2 kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div><!-- /RIGHT wrapper -->
        </div>

    </main>

    <!-- FOOTER -->
    <footer
        style="flex-shrink:0;padding:5px 16px;background:#fff;border-top:1px solid #E7E5DF;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.65rem;color:#A8A29E;">🐮 Dairy Cow Management Dashboard · ระบบจัดการฟาร์มวัวนม</span>
        <span class="mono" style="font-size:.65rem;color:#A8A29E;" id="footer-date"></span>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            document.getElementById('clock').textContent =
                `${pad(now.getDate())}/${pad(now.getMonth()+1)}/${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            document.getElementById('footer-date').textContent = `${now.getFullYear()} · Mock Data`;
        }
        updateClock();
        setInterval(updateClock, 1000);

        new Chart(document.getElementById('weightChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['3m', '6m', '12m', '18m', '24m', 'รีดนม'],
                datasets: [{
                    data: [70, 130, 230, 320, 420, 500],
                    borderColor: '#EF9F27',
                    backgroundColor: 'rgba(239,159,39,0.07)',
                    borderWidth: 2,
                    pointBackgroundColor: '#EF9F27',
                    pointRadius: 3,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#A8A29E',
                            callback: v => v + 'kg'
                        }
                    }
                }
            }
        });

        function calcIncome() {
            const n = parseInt(document.getElementById('num-cattle').value);
            const m = parseInt(document.getElementById('milk-prod').value);
            const p = parseInt(document.getElementById('milk-price').value);
            document.getElementById('num-out').textContent = n + ' ตัว';
            document.getElementById('milk-out').textContent = m + ' ลิตร';
            document.getElementById('price-out').textContent = p + ' ฿';
            const incomeDay = n * m * p;
            const incomeMonth = incomeDay * 30;
            const costMonth = n * 6750;
            const profit = incomeMonth - costMonth;
            document.getElementById('p-day').textContent = '฿' + incomeDay.toLocaleString();
            document.getElementById('p-month').textContent = '฿' + incomeMonth.toLocaleString();
            const el = document.getElementById('p-profit');
            el.textContent = '฿' + profit.toLocaleString();
            el.style.color = profit >= 0 ? '#0F6E56' : '#A32D2D';
        }
        calcIncome();
    </script>
</body>

</html>