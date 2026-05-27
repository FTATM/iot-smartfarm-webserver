<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE home_branch
        ADD COLUMN label varchar(255)
    ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE home_branch
        DROP COLUMN IF EXISTS label
    ");

        pg_query($conn, "COMMIT");
    }
};
