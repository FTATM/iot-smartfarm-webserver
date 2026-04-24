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
                        <div id="edit-table" class="km-btn km-btn-edit" onclick="confirmEdit()">แก้ไข</div>
                        <div id="delete-table" class="km-btn km-btn-delete" onclick="ModalConfirmDeleteTable()">ลบ</div>
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
                <div id="table-list"></div>
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

    <script>
    let isLoading = true;
    let tables = [],
        table_m = [],
        table_c = [];
    let table = [],
        childen = [];
    let selectTable;

    document.addEventListener('DOMContentLoaded', async () => {
        await fetchs();
        initDataTable();
        await process();
        isLoading = false;
    });

    async function fetchs() {
        const res = await fetch("../../api-website/fetch-tableknowledge.php");
        const raw = await res.json();
        tables = raw['data'] ?? [];
        console.log(tables);
    }

    async function process() {}

    function initDataTable() {
        if (!tables?.length) {
            console.warn("Not found tables.");
            return;
        }
        table_m = tables.filter(t => t.child_of_table_id == null);
        table_c = tables.filter(t => t.child_of_table_id != null);
        selectTable = 15;
        filterTableKnowledge();
    }

    function filterTableKnowledge() {
        table = table_m.find(t => t.id == selectTable);
        childen = table_c.filter(c => c.child_of_table_id == selectTable);
        initListTable(table_m);
        initTable(table, childen);
        mapTable(table, childen);
    }

    /* --- sidebar --- */
    function initListTable(list) {
        const el = document.getElementById("table-list");
        if (!el) return;
        el.innerHTML = '';

        const newItem = document.createElement('div');
        newItem.className = 'km-item km-new';
        newItem.textContent = '+ สร้างตารางใหม่';
        newItem.onclick = () => redirectTo('knowledge-create');
        el.appendChild(newItem);

        list.forEach(l => {
            const div = document.createElement('div');
            div.className = 'km-item' + (l.id == selectTable ? ' active' : '');
            div.textContent = `[${l.id}] ${l.name}`;
            div.onclick = () => {
                selectTable = l.id;
                filterTableKnowledge();
            };
            el.appendChild(div);
        });
    }

    /* --- table head --- */
    function initTable(table, childen) {
        const tableE = document.getElementById("table");
        if (!tableE) return;
        tableE.innerHTML = '';

        const titleEl = document.getElementById('title-table');
        if (titleEl) titleEl.textContent = table?.name ?? "ไม่พบชื่อตาราง";

        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        tr.innerHTML = `<th>วัน</th><th>${table?.label ?? "..."}</th>`;
        childen.forEach(c => {
            tr.innerHTML += `<th>${c.label ?? "-"}</th>`;
        });
        thead.appendChild(tr);
        tableE.appendChild(thead);
    }

    /* --- helper: check if today's day-of-month is within range --- */
    function isCurrentDay(startDay, endDay) {
        if (startDay == null || endDay == null) return false;
        const today = new Date().getDate();
        return today >= Number(startDay) && today <= Number(endDay);
    }

    /* --- table body (view) --- */
    function mapTable(table, childen) {
        const tableE = document.getElementById("table");
        if (!tableE) return;
        const tbody = document.createElement('tbody');
        Object.values(table['rows']).forEach(r => {
            const tr = document.createElement('tr');
            if (isCurrentDay(r.d_start_day, r.d_end_day)) {
                tr.classList.add('km-row-active');
            }
            tr.innerHTML = `<td>${r.d_start_day} - ${r.d_end_day}</td><td>${r.d_value ?? "-"}</td>`;
            Object.values(childen).forEach(c => {
                const found = c['rows'].find(d => d.d_row_parent_id == r.d_id);
                tr.innerHTML += `<td>${found?.d_value ?? '-'}</td>`;
            });
            tbody.appendChild(tr);
        });
        tableE.appendChild(tbody);
    }

    /* --- table body (edit) --- */
    function EditTable() {
        const tableE = document.getElementById("table");
        if (!tableE) return;
        const tbody = tableE.querySelector('tbody');
        tbody.innerHTML = '';

        Object.values(table['rows']).forEach(r => {
            const tr = document.createElement('tr');

            // date range
            const tdDate = document.createElement('td');
            tdDate.innerHTML = `
        <div class="td-date">
          <input type="number" value="${r.d_start_day}"
            data-id="${r.d_id}" data-table_id="${r.d_name_table_id}" data-field="start_day">
          <span>–</span>
          <input type="number" value="${r.d_end_day}"
            data-id="${r.d_id}" data-table_id="${r.d_name_table_id}" data-field="end_day">
        </div>`;
            tr.appendChild(tdDate);

            // main value
            const tdVal = document.createElement('td');
            tdVal.innerHTML = `<input value="${r.d_value ?? ''}"
        data-id="${r.d_id}" data-table_id="${r.d_name_table_id}" data-field="main_value">`;
            tr.appendChild(tdVal);

            // children
            Object.values(childen).forEach(c => {
                const found = c['rows'].find(d => d.d_row_parent_id == r.d_id);
                const td = document.createElement('td');
                td.innerHTML = `<input value="${found?.d_value ?? ''}"
          data-parent="${r.d_id}" data-id="${found?.d_id ?? ''}"
          data-table_id="${found?.d_name_table_id ?? ''}"
          data-child="${c.id ?? ''}" data-field="child_value">`;
                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    function collectData() {
        return [...document.querySelectorAll("#table input")].map(i => ({
            id: i.dataset.id,
            parent: i.dataset.parent,
            table_id: i.dataset.table_id,
            field: i.dataset.field,
            value: i.value
        }));
    }

    function buildAllTables(result, table, children) {
        const mainMap = {};
        result.forEach(r => {
            if (r.table_id === table.id) {
                if (!mainMap[r.id]) mainMap[r.id] = {};
                mainMap[r.id][r.field] = r.value;
            }
        });
        const allTables = [{
            ...table,
            rows: []
        }, ...children.map(c => ({
            ...c,
            rows: []
        }))];
        const tableMap = {};
        allTables.forEach(t => {
            tableMap[t.id] = t;
        });
        Object.entries(mainMap).forEach(([id, data]) => {
            tableMap[table.id].rows.push({
                d_id: id,
                d_name_table_id: table.id,
                d_row_parent_id: null,
                d_start_day: data.start_day || null,
                d_end_day: data.end_day || null,
                d_value: data.main_value || null,
                d_second_label: null
            });
        });
        result.forEach(r => {
            if (r.table_id !== table.id && r.parent) {
                const t = tableMap[r.table_id],
                    parent = mainMap[r.parent];
                if (!t || !parent) return;
                t.rows.push({
                    d_id: r.id,
                    d_name_table_id: r.table_id,
                    d_row_parent_id: r.parent,
                    d_start_day: parent.start_day || null,
                    d_end_day: parent.end_day || null,
                    d_value: r.value,
                    d_second_label: null
                });
            }
        });
        allTables.forEach(t => t.rows.sort((a, b) => (a.d_start_day || 0) - (b.d_start_day || 0)));
        return allTables;
    }

    /* --- mode helpers --- */
    function setEditMode(on) {
        ['cancel-table', 'save-table'].forEach(id => document.getElementById(id).classList.toggle('hidden', !on));
        ['edit-table', 'delete-table'].forEach(id => document.getElementById(id).classList.toggle('hidden', on));
    }

    /* --- modal triggers --- */
    function ModalConfirmDeleteTable() {
        openModal("ยืนยันการลบ", "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?", confirmDelete, "#dc2626");
    }

    function ModalConfirmSaveTable() {
        openModal("ยืนยันการบันทึก", "คุณต้องการบันทึกข้อมูลนี้ใช่หรือไม่?", confirmSave, "var(--accent)");
    }

    function ModalConfirmCancelTable() {
        openModal("ยืนยันการยกเลิก", "คุณต้องการยกเลิกการแก้ไขนี้ใช่หรือไม่?", confirmCancel, "#64748b");
    }

    function confirmEdit() {
        EditTable();
        setEditMode(true);
    }

    function confirmDelete() {
        console.log("Confirm Delete");
    }

    function confirmSave() {
        const result = collectData();
        const all = buildAllTables(result, table, childen);
        console.log(all);
        confirmCancel();
    }

    function confirmCancel() {
        if (document.getElementById('cancel-table').classList.contains('hidden')) return;
        initTable(table, childen);
        mapTable(table, childen);
        setEditMode(false);
    }

    /* --- modal engine --- */
    let currentAction = null;

    function openModal(title, desc, action, color) {
        document.getElementById("confirmModal").classList.add("km-open");
        document.getElementById("modalTitle").textContent = title;
        document.getElementById("modalDesc").textContent = desc;
        document.getElementById("confirmBtn").style.background = color;
        currentAction = action;
    }

    function closeModal() {
        document.getElementById("confirmModal").classList.remove("km-open");
    }
    document.getElementById("confirmBtn").onclick = () => {
        if (currentAction) currentAction();
        closeModal();
    };
    </script>

</body>

</html>