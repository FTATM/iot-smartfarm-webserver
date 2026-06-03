<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE ai_log
        ALTER COLUMN collected_data TYPE text
        ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE ai_log
        ALTER COLUMN collected_data TYPE jsonb
        ");

        pg_query($conn, "COMMIT");
    }
};
