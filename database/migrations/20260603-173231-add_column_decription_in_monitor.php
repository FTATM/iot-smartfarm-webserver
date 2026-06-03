<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "
            ALTER TABLE page_data_manage_monitor
            ADD COLUMN IF NOT EXISTS description TEXT
        ");
    }

    public function down($conn)
    {
        pg_query($conn, "
            ALTER TABLE page_data_manage_monitor
            DROP COLUMN IF EXISTS description
        ");
    }
};