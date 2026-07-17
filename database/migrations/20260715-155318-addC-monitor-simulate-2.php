<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor ADD "is_sim" int4 DEFAULT 0 NULL;
        ');
    }

    public function down($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "is_sim";
        ');
    }
};
