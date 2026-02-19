<?php


return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $result1 = pg_query($conn, " ALTER TABLE public.income
            RENAME COLUMN income_date TO start_income_date;
        ");

        if (!$result1) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result2 = pg_query($conn, " ALTER TABLE public.income
            ADD COLUMN IF NOT EXISTS end_income_date DATE;
        ");

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
            ALTER TABLE public.income
            DROP COLUMN IF EXISTS end_income_date;
        ");

        pg_query($conn, "
            ALTER TABLE public.income
            RENAME COLUMN start_income_date TO income_date;
        ");

        pg_query($conn, "COMMIT");
    }
};
