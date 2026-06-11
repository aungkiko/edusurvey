<?php
/**
 * ===================================================
 * User Model
 * ===================================================
 */
require_once BASE_PATH . 'core/Model.php';

class User extends Model
{
    protected string $table = 'users';
    
    /**
     * ค้นหาผู้ใช้จาก username
     */
    public function findByUsername(string $username): ?array
    {
        return $this->findOneWhere(['username' => $username]);
    }
    
    /**
     * อัปเดตเวลา login ล่าสุด
     */
    public function updateLastLogin(int $id): bool
    {
        return $this->execute(
            "UPDATE {$this->table} SET last_login = NOW() WHERE id = :id",
            [':id' => $id]
        );
    }
}
