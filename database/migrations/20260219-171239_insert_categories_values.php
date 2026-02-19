<?php

return new class {

    public function up($conn)
    {
        pg_query($conn, "BEGIN");

        $resultdesc = pg_query($conn, "
            ALTER TABLE public.categories
            ALTER COLUMN description TYPE VARCHAR(255);
        ");

        if (!$resultdesc) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result = pg_query($conn, "
        INSERT INTO public.categories (name, type, group_name, description) VALUES
        ('Crop Sales', 'income', 'plant', 'รายได้จากการขายผักหรือพืชไร่'),
        ('Fruit Sales', 'income', 'plant', 'รายได้จากการขายผลไม้'),
        ('Herb Sales', 'income', 'plant', 'รายได้จากการขายสมุนไพร'),
        ('Egg Sales', 'income', 'livestock', 'รายได้จากการขายไข่'),
        ('Meat Sales', 'income', 'livestock', 'รายได้จากการขายสัตว์เนื้อ'),
        ('Live Animal Sales', 'income', 'livestock', 'รายได้จากการขายสัตว์เป็น'),
        ('Milk Sales', 'income', 'livestock', 'รายได้จากการขายนม'),
        ('Processed Products', 'income', 'value_added', 'รายได้จากสินค้าแปรรูป'),
        ('Seedling Sales', 'income', 'plant', 'รายได้จากการขายต้นกล้าหรือพันธุ์พืช'),
        ('Breeding Stock Sales', 'income', 'livestock', 'รายได้จากการขายสัตว์พันธุ์'),
        ('Equipment Rental', 'income', 'other', 'รายได้จากการให้เช่าอุปกรณ์'),
        ('Land Rental', 'income', 'other', 'รายได้จากการให้เช่าที่ดิน'),
        ('Government Subsidy', 'income', 'other', 'เงินสนับสนุนหรือเงินอุดหนุนจากภาครัฐ'),
        ('Other Income', 'income', 'other', 'รายได้อื่น ๆ ที่ไม่ได้อยู่ในหมวดหลัก');
        ");

        if (!$result) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result2 = pg_query($conn, "
        INSERT INTO public.categories (name, type, group_name, description) VALUES
        ('Seeds', 'expense', 'plant', 'ค่าใช้จ่ายสำหรับซื้อเมล็ดพันธุ์พืช'),
        ('Fertilizer', 'expense', 'plant', 'ค่าใช้จ่ายสำหรับปุ๋ย'),
        ('Pesticide', 'expense', 'plant', 'ค่าใช้จ่ายสำหรับยาหรือสารป้องกันศัตรูพืช'),
        ('Animal Feed', 'expense', 'livestock', 'ค่าใช้จ่ายสำหรับอาหารสัตว์'),
        ('Livestock Purchase', 'expense', 'livestock', 'ค่าใช้จ่ายสำหรับซื้อสัตว์มาเลี้ยง'),
        ('Labor Cost', 'expense', 'operation', 'ค่าแรงงานประจำหรือรายวัน'),
        ('Water Bill', 'expense', 'utility', 'ค่าใช้น้ำประปาหรือค่าน้ำสำหรับการผลิต'),
        ('Electricity Bill', 'expense', 'utility', 'ค่าใช้ไฟฟ้า'),
        ('Fuel Cost', 'expense', 'utility', 'ค่าเชื้อเพลิงหรือน้ำมัน'),
        ('Equipment', 'expense', 'asset', 'ค่าใช้จ่ายในการซื้อเครื่องมือหรืออุปกรณ์'),
        ('Maintenance', 'expense', 'operation', 'ค่าใช้จ่ายในการซ่อมแซมและบำรุงรักษา'),
        ('Packaging', 'expense', 'sales', 'ค่าใช้จ่ายสำหรับบรรจุภัณฑ์สินค้า'),
        ('Transportation', 'expense', 'sales', 'ค่าใช้จ่ายในการขนส่งสินค้า'),
        ('Construction', 'expense', 'asset', 'ค่าใช้จ่ายในการก่อสร้างหรือปรับปรุงโครงสร้าง'),
        ('Loan Interest', 'expense', 'finance', 'ดอกเบี้ยเงินกู้'),
        ('Miscellaneous', 'expense', 'operation', 'ค่าใช้จ่ายเบ็ดเตล็ด'),
        ('Other Expense', 'expense', 'other', 'รายจ่ายอื่น ๆ ที่ไม่ได้อยู่ในหมวดหลัก');
        ");

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }


        pg_query($conn, "COMMIT");
    }

    public function down($conn)
    {
        pg_query($conn, "BEGIN");

        $result1 = pg_query($conn, "
            DELETE FROM public.categories
            WHERE type = 'income'
            AND name IN (
                'Crop Sales',
                'Fruit Sales',
                'Herb Sales',
                'Egg Sales',
                'Meat Sales',
                'Live Animal Sales',
                'Milk Sales',
                'Processed Products',
                'Seedling Sales',
                'Breeding Stock Sales',
                'Equipment Rental',
                'Land Rental',
                'Government Subsidy',
                'Other Income'
            );
        ");

        if (!$result1) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result2 = pg_query($conn, "
            DELETE FROM public.categories
            WHERE type = 'expense'
            AND name IN (
                'Seeds',
                'Fertilizer',
                'Pesticide',
                'Animal Feed',
                'Livestock Purchase',
                'Labor Cost',
                'Water Bill',
                'Electricity Bill',
                'Fuel Cost',
                'Equipment',
                'Maintenance',
                'Packaging',
                'Transportation',
                'Construction',
                'Loan Interest',
                'Miscellaneous',
                'Other Expense'
            );
        ");

        if (!$result2) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }

        $result3 = pg_query($conn, "
            ALTER TABLE public.categories
            ALTER COLUMN type TYPE VARCHAR(20);
        ");

        if (!$result3) {
            pg_query($conn, "ROLLBACK");
            throw new Exception(pg_last_error($conn));
        }
        pg_query($conn, "COMMIT");
    }
};
