<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE home_branch
        ADD COLUMN type_values_id int4,
        ADD COLUMN icon_id int4,
        ADD COLUMN unitofvalue varchar(255)
    ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        ALTER TABLE home_branch
        DROP COLUMN IF EXISTS type_values_id,
        DROP COLUMN IF EXISTS icon_id,
        DROP COLUMN IF EXISTS unitofvalue
    ");

        pg_query($conn, "COMMIT");
    }
};
