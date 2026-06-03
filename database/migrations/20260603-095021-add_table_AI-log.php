<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
        CREATE TABLE ai_log (
            id BIGSERIAL PRIMARY KEY,

            branch_id INT,

            ai_mode INT,

            ai_model VARCHAR(100),

            question TEXT,

            planner_prompt TEXT,
            planner_response JSONB,

            collected_data JSONB,

            decision_prompt TEXT,
            decision_response JSONB,

            execution_result JSONB,

            created_at TIMESTAMP DEFAULT NOW()
        );
        ");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
            DROP TABLE IF EXISTS ai_log;
        ");

        pg_query($conn, "COMMIT");
    }
};
