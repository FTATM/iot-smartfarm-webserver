<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "
            ALTER TABLE ai_log
            ADD COLUMN IF NOT EXISTS description TEXT,
            ADD COLUMN IF NOT EXISTS is_success INT4
        ");
    }

    public function down($conn)
    {
        pg_query($conn, "
            ALTER TABLE ai_log
            DROP COLUMN IF EXISTS description,
            DROP COLUMN IF EXISTS is_success
        ");
    }
};