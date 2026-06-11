<?php
/**
 * ===================================================
 * CSRF Protection
 * ===================================================
 * ป้องกัน Cross-Site Request Forgery
 * สร้าง token ใหม่ทุกครั้ง และตรวจสอบก่อนประมวลผลฟอร์ม
 */
class CSRF
{
    /**
     * สร้าง CSRF Token
     */
    public static function generate(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        return $token;
    }
    
    /**
     * สร้าง hidden input สำหรับฟอร์ม
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * ตรวจสอบ CSRF Token
     */
    public static function validate(?string $token = null): bool
    {
        $token = $token ?? ($_POST['csrf_token'] ?? '');
        
        // ตรวจว่ามี token ใน session
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }
        
        // ตรวจว่า token ตรงกัน
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        
        // ตรวจว่า token ยังไม่หมดอายุ
        if (time() - ($_SESSION['csrf_token_time'] ?? 0) > CSRF_LIFETIME) {
            return false;
        }
        
        return true;
    }
    
    /**
     * ตรวจสอบ CSRF Token และ die ถ้าไม่ผ่าน
     */
    public static function check(): void
    {
        if (!self::validate()) {
            http_response_code(403);
            die('<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เซสชันหมดอายุ - การรักษาความปลอดภัย</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: #fff3cd;
            color: #ffc107;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
        }
        .title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .description {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: left;
        }
        .btn-back {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background: #2980b9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-wrapper">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        <h3 class="title">หมดเวลาเชื่อมต่อชั่วคราว</h3>
        
        <div class="description">
            <p class="mb-2">ระบบไม่สามารถดำเนินการต่อได้ เพื่อความปลอดภัยของข้อมูลคุณ (CSRF Token Expired) ซึ่งมักเกิดจาก:</p>
            <ul class="mb-0 text-muted">
                <li>เปิดหน้าเว็บนี้ทิ้งไว้นานเกินไป</li>
                <li>มีการเปิดใช้งานระบบในแท็บอื่นพร้อมกัน</li>
            </ul>
            <p class="mt-3 text-center text-primary mb-0"><strong>ไม่ต้องตกใจครับ ข้อมูลของคุณยังปลอดภัย!</strong></p>
        </div>
        
        <a href="javascript:history.back()" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            ย้อนกลับไปทำรายการใหม่
        </a>
    </div>
</body>
</html>');
        }
    }
}
