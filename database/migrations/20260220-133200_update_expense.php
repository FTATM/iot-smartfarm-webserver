<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $result = pg_query($conn, " ALTER TABLE public.expense
            ADD COLUMN IF NOT EXISTS end_expense_date DATE;
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        $result2 = pg_query($conn, "ALTER TABLE public.expense
            RENAME COLUMN expense_date TO start_expense_date;
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

        $result = pg_query($conn, " ALTER TABLE public.expense
            DROP COLUMN IF EXISTS end_expense_date;
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        /* 3️⃣ rename กลับ */
        $result2 = pg_query($conn, "ALTER TABLE public.expense
            RENAME COLUMN start_expense_date TO expanse_date;
        ");

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
