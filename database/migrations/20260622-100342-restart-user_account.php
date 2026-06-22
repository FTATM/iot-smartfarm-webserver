<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "
            TRUNCATE TABLE user_account RESTART IDENTITY CASCADE
        ");

        pg_query($conn, "
            INSERT INTO user_account
            (
                username,
                password,
                name,
                email,
                role_id,
                branch_id
            )
            VALUES
            ('smartfarm',    'a68d22f2fa2f7f2cb2b2dd1cec5d44e6', 'global',       'global@smartfarm.com',       99, 1),

            ('superadmin01', '93b3b01aa6091522f955f170680eab39', 'superadmin01', 'superadmin01@smartfarm.com', 88, 1),
            ('superadmin02', '93b3b01aa6091522f955f170680eab39', 'superadmin02', 'superadmin02@smartfarm.com', 88, 2),
            ('superadmin03', '93b3b01aa6091522f955f170680eab39', 'superadmin03', 'superadmin03@smartfarm.com', 88, 3),
            ('superadmin04', '93b3b01aa6091522f955f170680eab39', 'superadmin04', 'superadmin04@smartfarm.com', 88, 4),
            ('superadmin05', '93b3b01aa6091522f955f170680eab39', 'superadmin05', 'superadmin05@smartfarm.com', 88, 5),

            ('admin01',      '21232f297a57a5a743894a0e4a801fc3', 'admin01',      'admin01@smartfarm.com',      55, 1),
            ('admin02',      '21232f297a57a5a743894a0e4a801fc3', 'admin02',      'admin02@smartfarm.com',      55, 2),
            ('admin03',      '21232f297a57a5a743894a0e4a801fc3', 'admin03',      'admin03@smartfarm.com',      55, 3),
            ('admin04',      '21232f297a57a5a743894a0e4a801fc3', 'admin04',      'admin04@smartfarm.com',      55, 4),
            ('admin05',      '21232f297a57a5a743894a0e4a801fc3', 'admin05',      'admin05@smartfarm.com',      55, 5),

            ('user01',       'ee11cbb19052e40b07aac0ca060c23ee', 'user01',       'user01@smartfarm.com',       1, 1),
            ('user02',       'ee11cbb19052e40b07aac0ca060c23ee', 'user02',       'user02@smartfarm.com',       1, 2),
            ('user03',       'ee11cbb19052e40b07aac0ca060c23ee', 'user03',       'user03@smartfarm.com',       1, 3),
            ('user04',       'ee11cbb19052e40b07aac0ca060c23ee', 'user04',       'user04@smartfarm.com',       1, 4),
            ('user05',       'ee11cbb19052e40b07aac0ca060c23ee', 'user05',       'user05@smartfarm.com',       1, 5)
        ");
    }

    public function down($conn)
    {
        pg_query($conn, "
            TRUNCATE TABLE user_account RESTART IDENTITY CASCADE
        ");
    }
};