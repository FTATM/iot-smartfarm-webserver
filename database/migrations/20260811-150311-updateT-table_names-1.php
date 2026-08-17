<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.table_names
            DROP CONSTRAINT type_table_id_unique;
        ');
    }

    public function down($conn)
    {
        pg_query($conn, '
            ALTER TABLE public.table_names
            ADD CONSTRAINT type_table_id_unique
            UNIQUE (table_type_id);
        ');
    }
};