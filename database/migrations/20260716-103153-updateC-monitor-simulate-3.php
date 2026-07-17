<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor
                ALTER COLUMN "sim-pull"
                TYPE int4
                USING ROUND("sim-pull" * 100)::int4;

            ALTER TABLE public.page_data_manage_monitor
                ALTER COLUMN "sim-pull"
                SET DEFAULT 5;
        ');
    }

    public function down($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor
                ALTER COLUMN "sim-pull"
                TYPE float4
                USING ("sim-pull"::float4 / 100);

            ALTER TABLE public.page_data_manage_monitor
                ALTER COLUMN "sim-pull"
                SET DEFAULT 0.05;
        ');
    }

};