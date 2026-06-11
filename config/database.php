<?php
/**
 * ===================================================
 * Database Configuration - PDO Connection
 * ===================================================
 * ใช้ PDO กับ Prepared Statements เพื่อความปลอดภัย
 */

// --- Database Settings ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'edusurvey_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * สร้าง PDO Connection
 * @return PDO
 */
function getDB(): PDO
{
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // แสดง error message ที่เป็นมิตร (ไม่เปิดเผยข้อมูลสำคัญ)
            die('<div style="font-family:sans-serif;padding:40px;text-align:center;">
                <h2>⚠️ ไม่สามารถเชื่อมต่อฐานข้อมูลได้</h2>
                <p>กรุณาตรวจสอบการตั้งค่าฐานข้อมูลใน config/database.php</p>
                <p style="color:#999;font-size:12px;">Error: ' . $e->getMessage() . '</p>
            </div>');
        }
    }
    
    return $pdo;
}
