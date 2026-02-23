<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $sql = "
        INSERT INTO public.page_data_manage_specified 
        (name, group_id, device_id, type_id, datax_id)
        VALUES
        ('electricity', 1, 18, 1, 1),
        ('water', 1, 19, 1, 1);
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

        $sql = "
        DELETE FROM public.page_data_manage_specified
        WHERE name IN (
            'electricity',
            'water'
        );
    ";

        $result = pg_query($conn, $sql);

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        pg_query($conn, "COMMIT");
    }
};
