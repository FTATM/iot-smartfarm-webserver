<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor ADD "sim-min" int4 DEFAULT 0 NULL;
            ALTER TABLE public.page_data_manage_monitor ADD "sim-max" int4 DEFAULT 0 NULL;
            ALTER TABLE public.page_data_manage_monitor ADD "sim-base" int4 DEFAULT 0 NULL;
            ALTER TABLE public.page_data_manage_monitor ADD "sim-step" float4 DEFAULT 0.0 NULL;
            ALTER TABLE public.page_data_manage_monitor ADD "sim-noise" float4 DEFAULT 0.0 NULL;
            ALTER TABLE public.page_data_manage_monitor ADD "sim-pull" float4 DEFAULT 0.05 NULL;
        ');
    }

    public function down($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-min";
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-max";
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-base";
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-step";
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-noise";
            ALTER TABLE public.page_data_manage_monitor DROP COLUMN IF EXISTS "sim-pull";
        ');
    }
};
