<?php
/**
 * ===================================================
 * Session Management
 * ===================================================
 * การจัดการ Session อย่างปลอดภัย
 */
class Session
{
    /**
     * เริ่ม Session พร้อมการตั้งค่าความปลอดภัย
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // ตั้งค่า Session ที่ปลอดภัย
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
            
            session_name('EDUSURVEY_SID');
            session_start();
            
            // ตรวจสอบว่า session หมดอายุหรือไม่
            if (isset($_SESSION['last_activity'])) {
                if (time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
                    self::destroy();
                    session_start();
                }
            }
            
            $_SESSION['last_activity'] = time();
            
            // Regenerate session ID ทุก 30 นาที เพื่อป้องกัน Session Fixation
            if (!isset($_SESSION['created_at'])) {
                $_SESSION['created_at'] = time();
            } elseif (time() - $_SESSION['created_at'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created_at'] = time();
            }
        }
    }
    
    /**
     * ทำลาย Session
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * ตั้งค่า Session Variable
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * ดึงค่า Session Variable
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * ลบ Session Variable
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * ตรวจสอบว่ามี Session Variable หรือไม่
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * ตั้ง Flash Message (แสดงครั้งเดียว)
     */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * ดึง Flash Message และลบ
     */
    public static function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
