<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "
            CREATE TABLE sensor_decision_log (
                log_id              SERIAL          PRIMARY KEY,
                sensor_id           VARCHAR(20)     NOT NULL,
                action              VARCHAR(10)     NOT NULL CHECK (action IN ('RUN', 'STOP', 'IDLE')),
                reason              TEXT,
                weather_will_rain   BOOLEAN,
                weather_max_rain_mm NUMERIC(6,2)    DEFAULT 0,
                decided_at          TIMESTAMP       NOT NULL DEFAULT NOW()
            )
        ");

        pg_query($conn, "CREATE INDEX idx_sdl_sensor_id  ON sensor_decision_log (sensor_id)");
        pg_query($conn, "CREATE INDEX idx_sdl_decided_at ON sensor_decision_log (decided_at DESC)");
        pg_query($conn, "CREATE INDEX idx_sdl_action     ON sensor_decision_log (action)");

        pg_query($conn, "COMMENT ON TABLE  sensor_decision_log                     IS 'Log การตัดสินใจควบคุม sensor จาก AI'");
        pg_query($conn, "COMMENT ON COLUMN sensor_decision_log.sensor_id           IS 'อ้างอิง sensors.sensor_id'");
        pg_query($conn, "COMMENT ON COLUMN sensor_decision_log.action              IS 'RUN=ทำงาน, STOP=หยุด(มีฝน), IDLE=หยุด(นอกเวลา)'");
        pg_query($conn, "COMMENT ON COLUMN sensor_decision_log.weather_will_rain   IS 'AI ประเมินว่าจะมีฝนหรือไม่'");
        pg_query($conn, "COMMENT ON COLUMN sensor_decision_log.weather_max_rain_mm IS 'ปริมาณฝนสูงสุดใน forecast (มม./ชม.)'");

        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        pg_query($conn, "DROP INDEX IF EXISTS idx_sdl_action");
        pg_query($conn, "DROP INDEX IF EXISTS idx_sdl_decided_at");
        pg_query($conn, "DROP INDEX IF EXISTS idx_sdl_sensor_id");
        pg_query($conn, "DROP TABLE IF EXISTS sensor_decision_log");

        pg_query($conn, "COMMIT");
    }
};