<?php
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
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&family=Kanit:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../styles/knowledge-management.css">
</head>

<body>

    <?php include "../components/header.php"; ?>

    <!-- Main -->
    <main class="km-main">
        <div class="km-grid">

            <!-- LEFT: table panel -->
            <div class="km-left">
                <div class="km-toolbar">
                    <div id="title-table" class="km-toolbar-title">กำลังโหลด...</div>
                    <div class="km-toolbar-actions">
                        <div id="cancel-table" class="km-btn km-btn-cancel hidden" onclick="ModalConfirmCancelTable()">
                            ยกเลิก</div>
                        <div id="save-table" class="km-btn km-btn-save   hidden" onclick="ModalConfirmSaveTable()">
                            บันทึก</div>
                        <div id="edit-table" class="km-btn km-btn-edit" onclick="confirmEdit()">
                            แก้ไข</div>
                        <div id="delete-table" class="km-btn km-btn-delete" onclick="ModalConfirmDeleteTable()">
                            ลบ</div>
                    </div>
                </div>

                <div class="km-card" style="flex:1;min-height:0;display:flex;flex-direction:column;">
                    <div class="km-table-wrap">
                        <table id="table"></table>
                    </div>
                </div>
            </div>

            <!-- RIGHT: sidebar -->
            <div class="km-sidebar">
                <div class="km-sidebar-hd">
                    <span class="fa7-solid--list"></span>
                    รายการตารางทั้งหมด
                </div>
                <div class="h-full flex flex-1 flex-col">
                    <div class="cursor-pointer w-full h-[30px] km-item km-new" onclick="redirectTo('knowledge-create')">+ สร้างตารางใหม่</div>
                    <div id="table-list"></div>
                </div>
            </div>

        </div>
    </main>

    <!-- modal -->
    <div id="confirmModal">
        <div class="km-modal-box">
            <div class="km-modal-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h2 id="modalTitle"></h2>
            <p id="modalDesc"></p>
            <div class="km-modal-actions">
                <button class="km-modal-cancel" onclick="closeModal()">ยกเลิก</button>
                <button id="confirmBtn">ยืนยัน</button>
            </div>
        </div>
    </div>

    <?php include "../components/footer.php"; ?>
    <?php include "../scripts/js.html"; ?>
    <?php include "../scripts/js-knowledge.html"; ?>

</body>

</html>