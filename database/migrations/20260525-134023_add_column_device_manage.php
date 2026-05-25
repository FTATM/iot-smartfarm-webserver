<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE page_data_manage_device
        ADD COLUMN target_id int4
    ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE page_data_manage_device
        DROP COLUMN target_id
    ");

        pg_query($conn, "COMMIT");
    }
};
