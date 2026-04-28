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
    <title>knowledge - management</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="grid grid-cols-12 gap-3 h-full min-h-0">

            <!-- ========== ตารางแสดงข้อมูล (10 columns) ========== -->
            <div class="col-span-10 flex flex-col gap-3 justify-between">
                <div class="flex justify-between bg-red-500">
                    <div id="title-table" class="text-[1.25vw]">กำลังโหลด</div>
                    <div class="flex">
                        <div id="cancel-table"
                            class="border border-stone-400 bg-stone-200 hover:bg-stone-400 w-[3vw] text-center px-1 py-2 cursor-pointer hidden"
                            onclick="ModalConfirmCancelTable()">ยกเลิก</div>
                        <div id="save-table"
                            class="border border-lime-600 bg-lime-500 hover:bg-lime-400 w-[3vw] text-center text-white px-1 py-2 cursor-pointer hidden"
                            onclick="ModalConfirmSaveTable()">บันทึก</div>
                        <div id="edit-table"
                            class="border border-yellow-400 bg-yellow-400 hover:bg-yellow-300 w-[3vw] text-center px-3 py-2 cursor-pointer"
                            onclick="confirmEdit()">แก้ไข</div>
                        <div id="delete-table"
                            class="border border-red-600 bg-red-500 hover:bg-red-400 w-[3vw] text-center text-white px-3 py-2 cursor-pointer"
                            onclick="ModalConfirmDeleteTable()">ลบ</div>
                    </div>
                </div>
                <div class="w-full h-full bg-orange-300">
                    <table id="table" class="">
                    </table>
                </div>
            </div>

            <!-- ========== รายชื่อทั้งหมดตาราง (2 columns) ========== -->
            <div class="col-span-2 flex flex-col gap-3 min-h-0">
                <div class="text-center py-2 bg-blue-400">รายการตารางทั้งหมด</div>
                <div id="table-list" class="w-full h-full gap-2 bg-lime-300">
                </div>
            </div>
        </div>
    </main>
    <div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg p-6 w-[300px] text-center">
            <h2 id="modalTitle" class="text-lg font-semibold mb-3"></h2>
            <p id="modalDesc" class="text-sm text-gray-600 mb-5"></p>

            <div class="flex gap-2 justify-center">
                <button onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    ยกเลิก
                </button>

                <button id="confirmBtn" class="px-4 py-2 rounded text-white">
                    ยืนยัน
                </button>
            </div>
        </div>
    </div>

    <?php include "../components/footer.php"; ?>
    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-knowledge.html"; ?>



</body>

</html>