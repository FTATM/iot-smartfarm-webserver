<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $sql = "
            CREATE TABLE public.page_data_manage_specified (
                id int4 GENERATED ALWAYS AS IDENTITY NOT NULL,
                name varchar NOT NULL,
                group_id int4 DEFAULT 0 NOT NULL,
                device_id int4 DEFAULT 0 NOT NULL,
                type_id int4 DEFAULT 0 NOT NULL,
                datax_id int4 DEFAULT 0 NOT NULL,
                CONSTRAINT page_data_manage_specified_pkey PRIMARY KEY (id),
                CONSTRAINT name_page_data_manage_specified_unique UNIQUE (name)
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

        $sql = "DROP TABLE IF EXISTS public.page_data_manage_specified;";

        $result = pg_query($conn, $sql);

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
