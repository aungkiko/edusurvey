<?php
/**
 * ===================================================
 * Question Model
 * ===================================================
 * สำหรับจัดการคำถาม (Admin สามารถเพิ่ม/แก้ไข/ลบ)
 */
require_once BASE_PATH . 'core/Model.php';

class Question extends Model
{
    protected string $table = 'questions';
    
    /**
     * ดึงคำถามทั้งหมดที่ active (เรียงตาม sort_order)
     */
    public function getActiveQuestions(): array
    {
        return $this->findWhere(['is_active' => 1], 'sort_order ASC');
    }
    
    /**
     * ดึงคำถามตามยุทธศาสตร์
     */
    public function getByStrategy(int $strategyNumber): array
    {
        return $this->findWhere([
            'strategy_number' => $strategyNumber,
            'is_active' => 1,
        ], 'sort_order ASC');
    }
    
    /**
     * ดึงคำถามตามหมายเลข
     */
    public function getByNumber(int $questionNumber): ?array
    {
        return $this->findOneWhere(['question_number' => $questionNumber]);
    }
    
    /**
     * ดึงหมายเลขข้อสูงสุด
     */
    public function getMaxNumber(): int
    {
        $result = $this->query("SELECT MAX(question_number) as max_num FROM {$this->table}");
        return (int)($result[0]['max_num'] ?? 0);
    }
    
    /**
     * จัดกลุ่มคำถามตามยุทธศาสตร์
     */
    public function getGroupedByStrategy(): array
    {
        $questions = $this->getActiveQuestions();
        $grouped = [];
        
        foreach ($questions as $q) {
            $stratNum = $q['strategy_number'];
            if (!isset($grouped[$stratNum])) {
                $grouped[$stratNum] = [
                    'strategy_number' => $stratNum,
                    'strategy_name' => $q['strategy_name'],
                    'questions' => [],
                ];
            }
            $grouped[$stratNum]['questions'][] = $q;
        }
        
        return $grouped;
    }
}
