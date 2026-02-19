<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $result = pg_query($conn, " ALTER TABLE public.categories
            ADD COLUMN IF NOT EXISTS type VARCHAR(20);
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result01 = pg_query($conn, " ALTER TABLE public.categories
            ADD CONSTRAINT categories_type_check
            CHECK (type IN ('income','expense'));
        ");

        if (!$result01) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        /* 1️⃣ rename column */
        $result1 = pg_query($conn, "
            ALTER TABLE public.income
            RENAME COLUMN category TO category_id;
        ");

        if (!$result1) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result2 = pg_query($conn, "
            ALTER TABLE public.expense
            RENAME COLUMN category TO category_id;
        ");

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        /* 2️⃣ เปลี่ยน type เป็น int4 และบังคับค่าเป็น 1 */
        $result3 = pg_query($conn, "
            ALTER TABLE public.income
            ALTER COLUMN category_id TYPE int4 USING 1;
        ");

        if (!$result3) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result4 = pg_query($conn, "
            ALTER TABLE public.expense
            ALTER COLUMN category_id TYPE int4 USING 1;
        ");

        if (!$result4) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        /* 3️⃣ set NOT NULL */
        $result5 = pg_query($conn, "
            ALTER TABLE public.income
            ALTER COLUMN category_id SET NOT NULL;
        ");

        if (!$result5) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result6 = pg_query($conn, "
            ALTER TABLE public.expense
            ALTER COLUMN category_id SET NOT NULL;
        ");

        if (!$result6) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        /* 2️⃣ เปลี่ยน type กลับเป็น varchar */
        $result3 = pg_query($conn, " ALTER TABLE public.income
            ALTER COLUMN category_id TYPE VARCHAR(100);
        ");

        if (!$result3) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result4 = pg_query($conn, "
            ALTER TABLE public.expense
            ALTER COLUMN category_id TYPE VARCHAR(100);
        ");

        if (!$result4) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        /* 3️⃣ rename กลับ */
        $result5 = pg_query($conn, "
            ALTER TABLE public.income
            RENAME COLUMN category_id TO category;
        ");

        if (!$result5) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result6 = pg_query($conn, "
            ALTER TABLE public.expense
            RENAME COLUMN category_id TO category;
        ");

        if (!$result6) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }
        /* 1️⃣ ลบ constraint ก่อน */
        $result7 = pg_query($conn, "
            ALTER TABLE public.categories
            DROP CONSTRAINT IF EXISTS categories_type_check;
        ");

        if (!$result7) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        /* 2️⃣ ลบ column type */
        $result8 = pg_query($conn, "
            ALTER TABLE public.categories
            DROP COLUMN IF EXISTS type;
        ");

        if (!$result8) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
