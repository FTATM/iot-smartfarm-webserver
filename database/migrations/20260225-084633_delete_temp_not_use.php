<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $sql = "
            DROP TABLE IF EXISTS public.market_trends;
        ";

        $result = pg_query($conn, $sql);

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $sql = "
            DROP TABLE IF EXISTS public.resource_today;
        ";

        $result = pg_query($conn, $sql);

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }
        $sql = "
            DROP TABLE IF EXISTS public.shrimp_price_event;
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

        $sql1 = " CREATE TABLE public.market_trends (
        	    trend_id serial4 NOT NULL,
	            market_name varchar(100) NOT NULL,
	            trend_date date NOT NULL,
	            trend_type varchar(50) NULL,
	            trend_value numeric(15, 2) NULL,
	            trend_direction varchar(20) NULL,
	            confidence_percent numeric(5, 2) NULL,
	            note text NULL,
	            created_by int4 NULL,
	            created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	            CONSTRAINT market_trends_pkey PRIMARY KEY (trend_id)
            );
        ";

        $result1 = pg_query($conn, $sql1);

        if (!$result1) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $sql2 = " CREATE TABLE public.resource_today (
	            id int4 GENERATED ALWAYS AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE) NOT NULL,
	            water_value numeric NULL,
	            water_unit varchar NULL,
	            electric_unit varchar NULL,
	            status varchar NULL,
	            electric_value numeric NULL,
	            updated_at timestamp DEFAULT now() NULL,
	            CONSTRAINT resource_today_pk PRIMARY KEY (id)
            );
        ";

        $result2 = pg_query($conn, $sql2);

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $sql3 = " CREATE TABLE public.shrimp_price_event (
            	id int4 GENERATED ALWAYS AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 2147483647 START 1 CACHE 1 NO CYCLE) NOT NULL,
	            event_price numeric NULL,
	            data_table_id int4 NULL,
	            event_month date NULL,
	            CONSTRAINT shrimp_price_event_pk PRIMARY KEY (id)
            );
        ";

        $result2 = pg_query($conn, $sql3);

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }
        pg_query($conn, "COMMIT");
    }
};
