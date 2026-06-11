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
    
    echo "<h1>Database Updated Successfully!</h1>";
    echo "<p>อัปเดตฐานข้อมูลเรียบร้อยแล้ว คอลัมน์ <b>is_innovation_area</b> ถูกเพิ่มสำเร็จ</p>";
    echo "<p>คุณสามารถกลับไปทดสอบส่งแบบสอบถามได้เลยครับ</p>";
    echo '<a href="' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '">กลับหน้าแรก</a>';
    
} catch (PDOException $e) {
    echo "<h1>Database Update Failed</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
