<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-min" TO sim_min;
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-max" TO sim_max;
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-base" TO sim_base;
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-step" TO sim_step;
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-noise" TO sim_noise;
            ALTER TABLE public.page_data_manage_monitor RENAME COLUMN "sim-pull" TO sim_pull;
        ');
    }

    public function down($conn)
    {
        pg_query($conn, '
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_min TO "sim-min";
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_max TO "sim-max";
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_base TO "sim-base";
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_step TO "sim-step";
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_noise TO "sim-noise";
        ALTER TABLE public.page_data_manage_monitor RENAME COLUMN sim_pull TO "sim-pull";
    ');
    }
};
