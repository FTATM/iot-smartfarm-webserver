<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        // 1️⃣ farm_types
        $sql = "
        CREATE TABLE IF NOT EXISTS farm_types (
            id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT NOW()
        );
        ";
        if (!pg_query($conn, $sql)) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        // 2️⃣ metric_definitions
        $sql = "
            CREATE TABLE IF NOT EXISTS metric_definitions (
                id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                farm_type_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                label VARCHAR(150) NOT NULL,
                unit VARCHAR(50),
                data_type VARCHAR(20) DEFAULT 'float',
                is_required BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT NOW(),

                CONSTRAINT unique_metric_per_type UNIQUE(farm_type_id, name)
            );
            ";
        if (!pg_query($conn, $sql)) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        // 3️⃣ farm_batches
        $sql = "
            CREATE TABLE IF NOT EXISTS farm_batches (
                id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                user_id INT NOT NULL,
                farm_type_id INT NOT NULL,
                name VARCHAR(150),
                start_date DATE NOT NULL,
                initial_quantity INT,
                status VARCHAR(50) DEFAULT 'active',
                created_at TIMESTAMP DEFAULT NOW()
            );
            ";
        if (!pg_query($conn, $sql)) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        // 4️⃣ daily_records
        $sql = "
            CREATE TABLE IF NOT EXISTS daily_records (
                id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                batch_id INT NOT NULL,
                record_date DATE NOT NULL,
                created_at TIMESTAMP DEFAULT NOW(),

                CONSTRAINT unique_batch_date UNIQUE(batch_id, record_date)
            );
            ";
        if (!pg_query($conn, $sql)) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        // 5️⃣ daily_record_values
        $sql = "
            CREATE TABLE IF NOT EXISTS daily_record_values (
                id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                daily_record_id INT NOT NULL,
                metric_id INT NOT NULL,
                value NUMERIC(12,4) NOT NULL,
                created_at TIMESTAMP DEFAULT NOW(),

                CONSTRAINT unique_record_metric UNIQUE(daily_record_id, metric_id)
            );
            ";
        if (!pg_query($conn, $sql)) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        // 6️⃣ Index สำหรับ performance
        pg_query($conn, "CREATE INDEX IF NOT EXISTS idx_metric_farm_type ON metric_definitions(farm_type_id);");
        pg_query($conn, "CREATE INDEX IF NOT EXISTS idx_batch_farm_type ON farm_batches(farm_type_id);");
        pg_query($conn, "CREATE INDEX IF NOT EXISTS idx_daily_batch ON daily_records(batch_id);");
        pg_query($conn, "CREATE INDEX IF NOT EXISTS idx_record_metric ON daily_record_values(metric_id);");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        $tables = [
            "daily_record_values",
            "daily_records",
            "farm_batches",
            "metric_definitions",
            "farm_types"
        ];

        foreach ($tables as $table) {
            $sql = "DROP TABLE IF EXISTS $table;";
            if (!pg_query($conn, $sql)) {
                pg_query($conn, "ROLLBACK");
                throw new Exception(pg_last_error($conn));
            }
        }

        pg_query($conn, "COMMIT");
    }
};
