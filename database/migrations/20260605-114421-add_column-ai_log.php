<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "
            ALTER TABLE ai_log
            ADD COLUMN IF NOT EXISTS category TEXT
        ");
    }

    public function down($conn)
    {
        pg_query($conn, "
            ALTER TABLE ai_log
            DROP COLUMN IF EXISTS category
        ");
    }
};