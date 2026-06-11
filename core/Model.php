<?php
/**
 * ===================================================
 * Base Model - PDO Wrapper
 * ===================================================
 * คลาสพื้นฐานสำหรับ Model ทุกตัว
 * ใช้ Prepared Statements ทุก query
 */
class Model
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';
    
    public function __construct()
    {
        $this->db = getDB();
    }
    
    /**
     * ค้นหาทั้งหมด (พร้อม pagination)
     */
    public function findAll(int $page = 1, int $perPage = PER_PAGE, string $orderBy = 'id DESC'): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy} LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * ค้นหาด้วย ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * ค้นหาด้วยเงื่อนไข
     */
    public function findWhere(array $conditions, string $orderBy = 'id DESC'): array
    {
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        $whereStr = implode(' AND ', $where);
        
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$whereStr} ORDER BY {$orderBy}"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * ค้นหาแถวเดียวด้วยเงื่อนไข
     */
    public function findOneWhere(array $conditions): ?array
    {
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        $whereStr = implode(' AND ', $where);
        
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$whereStr} LIMIT 1"
        );
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * นับจำนวนแถวทั้งหมด
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['total'];
    }
    
    /**
     * เพิ่มข้อมูลใหม่
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
        );
        
        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }
        
        $stmt->execute($params);
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * อัปเดตข้อมูล
     */
    public function update(int $id, array $data): bool
    {
        $set = [];
        $params = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        $params[':id'] = $id;
        
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$this->primaryKey} = :id"
        );
        
        return $stmt->execute($params);
    }
    
    /**
     * ลบข้อมูล
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * รัน Custom Query
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * รัน Custom Execute (INSERT/UPDATE/DELETE)
     */
    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * เริ่ม Transaction
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    
    /**
     * Commit Transaction
     */
    public function commit(): void
    {
        $this->db->commit();
    }
    
    /**
     * Rollback Transaction
     */
    public function rollback(): void
    {
        $this->db->rollBack();
    }
}
