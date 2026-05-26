<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        UPDATE dashboard_main SET name = 'alert' WHERE id = 15;

    ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        UPDATE dashboard_main SET name = 'label-mid-9' WHERE id = 15;
    ");

        pg_query($conn, "COMMIT");
    }
};
