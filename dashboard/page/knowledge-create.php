<?php
session_start();
$Title = "Knowledge Management";
$subTitle = "manage knowledge about farm in website.";
$classIconHeader = "material-symbols--book";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>สร้างตาราง - knowledge</title>
    <link rel="stylesheet" href="../styles/knowledge-create.css">
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">

    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-[#FFF7ED] dark:bg-stone-950 transition-colors duration-300">

        <div class="h-full w-full min-h-0 flex flex-col gap-3">

            <!-- ── Toolbar ── -->
            <div id="toolbar">
                <!-- Left: title -->
                <div class="flex items-center gap-3">
                    <div class="accent-bar"></div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">ชื่อตาราง :</span>
                    <input id="title-table" placeholder="ระบุชื่อตาราง..." data-field="name-table">
                </div>

                <!-- Right: actions -->
                <div class="flex items-center gap-2">
                    <button id="cancel-table" class="btn-outline" onclick="ModalConfirmCancelTable()">ยกเลิก</button>

                    <button id="save-table" class="btn-orange" onclick="ModalConfirmSaveTable()">บันทึก</button>
                </div>
            </div>

            <!-- ── Table wrapper + add-column ── -->
            <div class="flex flex-col gap-2 flex-1 min-h-0 overflow-hidden">

                <!-- แถวตาราง + ปุ่มเพิ่มคอลัมน์ — flex:1 ขยายเต็มพื้นที่ที่เหลือ -->
                <div class="flex gap-3 min-h-0 overflow-hidden items-start" style="flex:1;">

                    <!-- กรอบขาวครอบเฉพาะตาราง -->
                    <div
                        style="flex:1; min-height:0; height:100%; display:flex; flex-direction:column; border:1px solid var(--orange-mid); border-radius:12px; box-shadow:0 1px 4px rgba(249,115,22,.08); overflow:hidden;">
                        <!-- overflow:auto อยู่ชั้นในเพื่อ scroll แต่ overflow:hidden ชั้นนอกตัดมุมโค้ง -->
                        <div id="table-wrapper" style="flex:1; overflow:auto;">
                            <table id="table"></table>
                        </div>
                    </div>

                    <!-- ปุ่มเพิ่มคอลัมน์ -->
                    <div style="padding-top:4px; flex-shrink:0;">
                        <button id="btn-add-col" onclick="addColumn()">+ เพิ่มคอลัมน์</button>
                    </div>
                </div>

                <!-- ปุ่มเพิ่มแถว — อยู่นอกกรอบ ไม่ถูกทับ -->
                <div style="flex-shrink:0;">
                    <button id="btn-add-row" onclick="addRow()">+ เพิ่มแถว</button>
                </div>
            </div>

        </div>
    </main>

    <!-- ── Confirm Modal ── -->
    <div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="modal-card">
            <h2 id="modalTitle"></h2>
            <p id="modalDesc"></p>
            <div class="modal-actions">
                <button id="modal-cancel-btn" onclick="closeModal()">ยกเลิก</button>
                <button id="confirmBtn" class="btn-orange">ยืนยัน</button>
            </div>
        </div>
    </div>

    <?php include "../components/footer.php"; ?>
    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-knowledge-create.html"; ?>
</body>

</html>