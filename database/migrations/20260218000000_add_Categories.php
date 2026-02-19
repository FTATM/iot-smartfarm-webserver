<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $sql = "
            CREATE TABLE IF NOT EXISTS public.categories (
                id int4 GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                name varchar(255) NOT NULL DEFAULT 'unknown',
                CONSTRAINT name_categories_unique UNIQUE (name)
            );
        ";

        $result = pg_query($conn, $sql);

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        $result = pg_query($conn, "
            DROP TABLE IF EXISTS public.categories;
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
