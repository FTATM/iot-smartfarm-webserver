<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $result = pg_query($conn, " ALTER TABLE public.categories
            ADD COLUMN IF NOT EXISTS description VARCHAR(20);
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        $result = pg_query($conn, " ALTER TABLE public.categories
            DROP COLUMN IF EXISTS description;
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
