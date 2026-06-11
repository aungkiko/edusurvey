<?php
/**
 * Script to update database schema
 * Run this on the server by visiting edusurvey.timevela.com/update_db.php
 */
require_once __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // 1. เพิ่มคอลัมน์ is_innovation_area ถ้ายังไม่มี
    $pdo->exec("ALTER TABLE `survey_responses` ADD COLUMN IF NOT EXISTS `is_innovation_area` ENUM('yes','no') NULL DEFAULT NULL COMMENT 'เป็นพื้นที่นวัตกรรมทางการศึกษาหรือไม่' AFTER `budget_year`;");
    
    // 2. เพิ่มคอลัมน์สำหรับข้อ 9 (แยกตามระดับชั้น)
    $q9_columns = [
        "ADD COLUMN IF NOT EXISTS `has_mattayom_pak` TINYINT(1) NOT NULL DEFAULT 0 AFTER `income_type`",
        "ADD COLUMN IF NOT EXISTS `mattayom_income` INT NOT NULL DEFAULT 0 AFTER `has_mattayom_pak`",
        "ADD COLUMN IF NOT EXISTS `mattayom_total` INT NOT NULL DEFAULT 0 AFTER `mattayom_income`",
        "ADD COLUMN IF NOT EXISTS `has_vocational` TINYINT(1) NOT NULL DEFAULT 0 AFTER `mattayom_total`",
        "ADD COLUMN IF NOT EXISTS `vocational_income` INT NOT NULL DEFAULT 0 AFTER `has_vocational`",
        "ADD COLUMN IF NOT EXISTS `vocational_total` INT NOT NULL DEFAULT 0 AFTER `vocational_income`",
        "ADD COLUMN IF NOT EXISTS `has_associate` TINYINT(1) NOT NULL DEFAULT 0 AFTER `vocational_total`",
        "ADD COLUMN IF NOT EXISTS `associate_income` INT NOT NULL DEFAULT 0 AFTER `has_associate`",
        "ADD COLUMN IF NOT EXISTS `associate_total` INT NOT NULL DEFAULT 0 AFTER `associate_income`"
    ];
    $pdo->exec("ALTER TABLE `response_q9` " . implode(", ", $q9_columns) . ";");
    
    // 3. รันคำสั่ง SQL จาก migration_strategy7.sql
    // แต่ก่อนอื่น ให้ลบคำถามของยุทธศาสตร์ที่ 7 ที่อาจซ้ำซ้อนออกก่อนเพื่อป้องกันปัญหา
    $pdo->exec("DELETE FROM `questions` WHERE `strategy_number` = 7;");
    
    $sqlFile = __DIR__ . '/database/migration_strategy7.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Split by semicolon and execute individually to bypass duplicate errors
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $stmt) {
            if (empty($stmt)) continue;
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                // Ignore duplicate column/table/key errors
                $errorCode = $e->getCode();
                $mysqlCode = $e->errorInfo[1] ?? 0;
                if (!in_array($mysqlCode, [1050, 1060, 1061, 1062])) {
                    // 1050: Table exists, 1060: Duplicate column, 1061: Duplicate key name, 1062: Duplicate entry
                    echo "<p style='color:red;'>Warning on query: " . htmlspecialchars($stmt) . "<br>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        }
        echo "<p>รันสคริปต์สร้างตารางสำหรับยุทธศาสตร์ที่ 7 สำเร็จ</p>";
    } else {
        echo "<p>ไม่พบไฟล์ migration_strategy7.sql</p>";
    }
    
    echo "<h1>Database Updated Successfully!</h1>";
    echo "<p>อัปเดตฐานข้อมูลเรียบร้อยแล้ว คอลัมน์ <b>is_innovation_area</b> ถูกเพิ่มสำเร็จ</p>";
    echo "<p>คุณสามารถกลับไปทดสอบส่งแบบสอบถามได้เลยครับ</p>";
    echo '<a href="' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '">กลับหน้าแรก</a>';
    
} catch (PDOException $e) {
    echo "<h1>Database Update Failed</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
