<?php
$Title = "Knowledge Management";
$subTitle = "manage knowledge about farm in website.";
$classIconHeader = "heroicons-outline--light-bulb";
?>
<!DOCTYPE html>
<html class="light" lang="th">
<?php include("../scripts/ref.html"); ?>
<?php include("../styles/css-default.html"); ?>
<?php include("../styles/css-icon.html"); ?>

<head>
    <title>สร้างตาราง - knowledge</title>
</head>

<body class="h-screen overflow-hidden flex flex-col bg-white dark:bg-stone-950 transition-colors duration-300">
    <?php include "../components/header.php"; ?>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col p-[0.5vw] gap-3 overflow-hidden min-h-0 bg-stone-50 dark:bg-stone-950 transition-colors duration-300">
        <!-- Main Container: แบ่ง 12 คอลัมน์ (10 + 2) -->
        <div class="h-full w-full min-h-0">

            <!-- ========== ตารางแสดงข้อมูล (12 columns) ========== -->
            <div class="flex flex-col gap-3 justify-between">
                <div class="flex justify-between bg-blue-400 px-2">
                    <div class="flex gap-2 items-center">
                        <div class="text-center">ชื่อตาราง :</div>
                        <input id="title-table" class="px-2 py-1">
                    </div>
                    <div class="flex">
                        <div id="cancel-table" class="border border-stone-400 bg-stone-200 hover:bg-stone-400 w-[3vw] text-center px-1 py-2 cursor-pointer" onclick="ModalConfirmCancelTable()">ยกเลิก</div>
                        <div id="save-table" class="border border-lime-600 bg-lime-500 hover:bg-lime-400 w-[3vw] text-center text-white px-1 py-2 cursor-pointer" onclick="ModalConfirmSaveTable()">บันทึก</div>
                    </div>
                </div>
                <div class="w-full h-full bg-orange-300">
                    <table id="table" class="">
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg p-6 w-[300px] text-center">
            <h2 id="modalTitle" class="text-lg font-semibold mb-3"></h2>
            <p id="modalDesc" class="text-sm text-gray-600 mb-5"></p>

            <div class="flex gap-2 justify-center">
                <button onclick="closeModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    ยกเลิก
                </button>

                <button id="confirmBtn"
                    class="px-4 py-2 rounded text-white">
                    ยืนยัน
                </button>
            </div>
        </div>
    </div>

    <?php include "../components/footer.php"; ?>
    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-knowledge-create.html"; ?>



</body>

</html>