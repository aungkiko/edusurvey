<?php
/**
 * ===================================================
 * School Model
 * ===================================================
 */
require_once BASE_PATH . 'core/Model.php';

class School extends Model
{
    protected string $table = 'schools';
    
    /**
     * ค้นหาโรงเรียนตามสังกัด
     */
    public function findByAffiliation(string $affiliation): array
    {
        return $this->findWhere(['affiliation' => $affiliation], 'school_name ASC');
    }
    
    /**
     * ค้นหาโรงเรียนตามอำเภอ
     */
    public function findByDistrict(string $district): array
    {
        return $this->findWhere(['district' => $district], 'school_name ASC');
    }
    
    /**
     * ค้นหาโรงเรียน (autocomplete)
     */
    public function search(string $keyword, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE school_name LIKE :keyword AND is_active = 1 
             ORDER BY school_name ASC LIMIT :limit"
        );
        $stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
