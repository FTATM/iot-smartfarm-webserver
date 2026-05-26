<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        // ลบ column target_id ออกจาก page_data_manage_device
        pg_query($conn, "
            ALTER TABLE page_data_manage_device
            DROP COLUMN IF EXISTS target_id
        ");

        // เพิ่ม column target_id ไปที่ page_data_manage_monitor
        pg_query($conn, "
            ALTER TABLE page_data_manage_monitor
            ADD COLUMN target_id int4
        ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        // ลบ column target_id ออกจาก page_data_manage_monitor
        pg_query($conn, "
            ALTER TABLE page_data_manage_monitor
            DROP COLUMN IF EXISTS target_id
        ");

        // เพิ่มกลับไปที่ page_data_manage_device
        pg_query($conn, "
            ALTER TABLE page_data_manage_device
            ADD COLUMN target_id int4
        ");

        pg_query($conn, "COMMIT");
    }
};
