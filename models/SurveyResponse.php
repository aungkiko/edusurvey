<?php
/**
 * ===================================================
 * SurveyResponse Model
 * ===================================================
 * จัดการข้อมูลการตอบแบบสอบถาม ทั้ง response หลัก
 * และข้อมูลรายข้อ (q1-q10)
 */
require_once BASE_PATH . 'core/Model.php';

class SurveyResponse extends Model
{
    protected string $table = 'survey_responses';
    
    /**
     * สร้าง response หลัก พร้อมข้อมูลรายข้อ (transaction)
     */
    public function createFull(array $mainData, array $questionsData): int
    {
        $this->beginTransaction();
        
        try {
            // บันทึก response หลัก
            $responseId = $this->create($mainData);
            
            // บันทึกข้อมูลรายข้อ (q1-q10)
            foreach ($questionsData as $qNum => $qData) {
                $qData['response_id'] = $responseId;
                $tableName = "response_q{$qNum}";
                
                $columns = implode(', ', array_keys($qData));
                $placeholders = ':' . implode(', :', array_keys($qData));
                
                $stmt = $this->db->prepare(
                    "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})"
                );
                
                $params = [];
                foreach ($qData as $key => $value) {
                    $params[":{$key}"] = $value;
                }
                $stmt->execute($params);
            }
            
            $this->commit();
            return $responseId;
            
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * ดึง response พร้อมข้อมูลรายข้อทั้งหมด
     */
    public function getFullResponse(int $id): ?array
    {
        $response = $this->findById($id);
        if (!$response) return null;
        
        // ดึงข้อมูลรายข้อ q1-q12
        for ($i = 1; $i <= 12; $i++) {
            $stmt = $this->db->prepare("SELECT * FROM response_q{$i} WHERE response_id = :id");
            $stmt->execute([':id' => $id]);
            $response["q{$i}"] = $stmt->fetch() ?: null;
        }
        
        return $response;
    }
    
    /**
     * ดึงรายการ responses (พร้อม pagination + filter)
     */
    public function getList(int $page = 1, int $perPage = PER_PAGE, array $filters = []): array
    {
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['year'])) {
            $where[] = 'sr.budget_year = :year';
            $params[':year'] = $filters['year'];
        }
        
        if (!empty($filters['district'])) {
            $where[] = 'sr.district_input = :district';
            $params[':district'] = $filters['district'];
        }
        
        if (!empty($filters['affiliation'])) {
            $where[] = 'sr.affiliation_input = :affiliation';
            $params[':affiliation'] = $filters['affiliation'];
        }
        
        if (!empty($filters['status'])) {
            $where[] = 'sr.status = :status';
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = '(sr.school_name_input LIKE :search OR sr.respondent_name LIKE :search2)';
            $params[':search'] = "%{$filters['search']}%";
            $params[':search2'] = "%{$filters['search']}%";
        }
        
        $whereStr = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;
        
        // นับจำนวนทั้งหมด
        $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} sr WHERE {$whereStr}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetch()['total'];
        
        // ดึงข้อมูล
        $sql = "SELECT sr.* FROM {$this->table} sr 
                WHERE {$whereStr} 
                ORDER BY sr.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page,
        ];
    }
    
    /**
     * อัปเดต response รายข้อ
     */
    public function updateQuestion(int $responseId, int $qNum, array $data): bool
    {
        $tableName = "response_q{$qNum}";
        $set = [];
        $params = [':response_id' => $responseId];
        
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        return $this->execute(
            "UPDATE {$tableName} SET " . implode(', ', $set) . " WHERE response_id = :response_id",
            $params
        );
    }
    
    /**
     * ตรวจสอบว่าโรงเรียนส่งข้อมูลปีนี้แล้วหรือยัง
     */
    public function hasSubmitted(string $schoolName, int $year): bool
    {
        $result = $this->findOneWhere([
            'school_name_input' => $schoolName,
            'budget_year' => $year,
        ]);
        return $result !== null;
    }
    
    /**
     * นับจำนวน response ตามปี
     */
    public function countByYear(): array
    {
        return $this->query(
            "SELECT budget_year, COUNT(*) as total 
             FROM {$this->table} 
             GROUP BY budget_year 
             ORDER BY budget_year ASC"
        );
    }
    
    /**
     * นับจำนวน response ตามสถานะ
     */
    public function countByStatus(): array
    {
        return $this->query(
            "SELECT status, COUNT(*) as total 
             FROM {$this->table} 
             GROUP BY status"
        );
    }
}
