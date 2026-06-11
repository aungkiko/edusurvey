<?php
/**
 * ===================================================
 * Authentication Helper
 * ===================================================
 * จัดการ login/logout และตรวจสอบสิทธิ์
 */
class Auth
{
    /**
     * ตรวจสอบว่าผู้ใช้ login อยู่หรือไม่
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Login: บันทึกข้อมูลผู้ใช้ลง Session
     */
    public static function login(array $user): void
    {
        // Regenerate session ID เพื่อป้องกัน Session Fixation
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();
    }
    
    /**
     * Logout: ทำลาย Session
     */
    public static function logout(): void
    {
        Session::destroy();
    }
    
    /**
     * ดึง User ID ปัจจุบัน
     */
    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * ดึงชื่อผู้ใช้ปัจจุบัน
     */
    public static function user(): array
    {
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? '',
            'full_name' => $_SESSION['full_name'] ?? '',
            'role' => $_SESSION['role'] ?? '',
        ];
    }
    
    /**
     * ตรวจสอบ role
     */
    public static function isAdmin(): bool
    {
        return ($_SESSION['role'] ?? '') === 'admin';
    }
    
    /**
     * ตรวจสอบ Password (ใช้ password_verify)
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Hash Password (ใช้ bcrypt)
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
