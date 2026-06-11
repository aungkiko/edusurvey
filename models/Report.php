<?php
/**
 * ===================================================
 * Report Model
 * ===================================================
 * สำหรับรายงานและวิเคราะห์ข้อมูล
 */
require_once BASE_PATH . 'core/Model.php';

class Report extends Model
{
    protected string $table = 'survey_responses';
    
    /**
     * ดึงปีทั้งหมดที่มีข้อมูล
     */
    public function getAvailableYears(): array
    {
        $result = $this->query("SELECT DISTINCT budget_year FROM {$this->table} ORDER BY budget_year ASC");
        $years = [];
        foreach ($result as $row) {
            $years[] = (int)$row['budget_year'];
        }
        
        // Add a default range so the dropdown always has options even if DB is empty
        $defaultRange = range(2569, 2573);
        $years = array_unique(array_merge($years, $defaultRange));
        sort($years);
        
        return $years;
    }
    
    /**
     * สรุปข้อมูลรายปี (ค่าเฉลี่ยแต่ละตัวชี้วัด)
     * @param int $year ปีที่ต้องการดูข้อมูล (0 = รวมทุกปี)
     */
    public function getYearlySummary(int $year): array
    {
        $summary = [];
        $where = $year > 0 ? "WHERE sr.budget_year = :year" : "";
        $params = $year > 0 ? [':year' => $year] : [];
        
        // ข้อ 1: ร้อยละผู้เรียนได้รับการพัฒนาทักษะภาษา
        $summary['q1'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_developed) as total_developed, SUM(q.total_students) as total_all
             FROM response_q1 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 2: ร้อยละสถานศึกษาได้รับการนิเทศ
        $summary['q2'] = $this->query(
            "SELECT 
                COUNT(CASE WHEN q.is_supervised = 'yes' THEN 1 END) as supervised_count,
                COUNT(*) as total_count,
                AVG(q.supervision_count) as avg_supervision_count
             FROM response_q2 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 3: ร้อยละผู้เรียนพัฒนาด้านพหุปัญญา
        $summary['q3'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_developed) as total_developed, SUM(q.total_students) as total_all
             FROM response_q3 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 4: ร้อยละผู้เรียนผ่านประเมินสุขภาวะ
        $summary['q4'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_passed) as total_passed, SUM(q.total_students) as total_all
             FROM response_q4 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 5: ร้อยละสถานศึกษาที่มีส่วนร่วมกับภาคี
        $summary['q5'] = $this->query(
            "SELECT 
                COUNT(CASE WHEN q.has_activities = 'yes' THEN 1 END) as activity_count,
                COUNT(*) as total_count,
                AVG(q.activity_count) as avg_activities
             FROM response_q5 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 6: ร้อยละนักเรียนได้รับรางวัลคุณธรรม
        $summary['q6'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_awarded) as total_awarded, SUM(q.total_students) as total_all
             FROM response_q6 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 7: ร้อยละผู้เรียนได้รับการแนะแนว
        $summary['q7'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_guided) as total_guided, SUM(q.total_students) as total_all
             FROM response_q7 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 8: อัตราการเพิ่มขึ้นของ MOU
        $summary['q8'] = $this->query(
            "SELECT AVG(q.increase_rate) as avg_rate, SUM(q.current_mou_count) as total_current, SUM(q.previous_mou_count) as total_previous
             FROM response_q8 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 9: ร้อยละผู้เรียนที่มีรายได้
        $summary['q9'] = $this->query(
            "SELECT AVG(q.percentage) as avg_percentage, SUM(q.students_with_income) as total_income, SUM(q.total_students) as total_all
             FROM response_q9 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 10: ผลการประเมิน ITA
        $summary['q10'] = $this->query(
            "SELECT 
                q.ita_result, COUNT(*) as count, AVG(q.ita_score) as avg_score
             FROM response_q10 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where} GROUP BY q.ita_result", $params
        );
        
        // ข้อ 11 (ยุทธ์ 7): จำนวนหุ้นส่วนความร่วมมือ
        $summary['q11'] = $this->query(
            "SELECT AVG(q.partnership_count) as avg_count, SUM(q.partnership_count) as total_count
             FROM response_q11 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        // ข้อ 12 (ยุทธ์ 7): จำนวนนวัตกรรมที่ใช้จริง
        $summary['q12'] = $this->query(
            "SELECT 
                COUNT(CASE WHEN q.has_innovations = 'yes' THEN 1 END) as with_innovations,
                COUNT(*) as total_count,
                SUM(q.curriculum_count + q.teaching_count + q.media_count + q.assessment_count + q.management_count + q.other_count) as total_innovations
             FROM response_q12 q JOIN survey_responses sr ON q.response_id = sr.id
             {$where}", $params
        );
        
        return $summary;
    }
    
    /**
     * เปรียบเทียบข้อมูลแต่ละปี
     */
    public function getComparison(): array
    {
        $years = $this->getAvailableYears();
        $comparison = [];
        
        foreach ($years as $year) {
            $comparison[$year] = [
                'total_responses' => $this->count(['budget_year' => $year]),
                'summary' => $this->getYearlySummary($year),
            ];
        }
        
        return $comparison;
    }
    
    /**
     * ข้อมูลสำหรับ Dashboard (สถิติรวม)
     */
    public function getDashboardStats(): array
    {
        $stats = [];
        
        // จำนวน response ทั้งหมด
        $stats['total_responses'] = $this->count();
        
        // จำนวนโรงเรียนที่ตอบ (ไม่ซ้ำ - ทั้งหมดทุกปี)
        $result = $this->query("SELECT COUNT(DISTINCT school_name_input) as total FROM {$this->table}");
        $stats['total_schools'] = $result[0]['total'] ?? 0;
        
        // จำนวน response ตามปี
        $stats['by_year'] = $this->query(
            "SELECT budget_year, COUNT(*) as total FROM {$this->table} GROUP BY budget_year ORDER BY budget_year ASC"
        );
        
        // map สำหรับ lookup ง่ายๆ: year => count
        $stats['by_year_map'] = [];
        foreach ($stats['by_year'] as $row) {
            $stats['by_year_map'][(int)$row['budget_year']] = (int)$row['total'];
        }
        
        // response ล่าสุด (ทุกปี)
        $stats['recent'] = $this->query(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT 5"
        );
        
        return $stats;
    }
    
    /**
     * สถิติ Dashboard กรองตามปีที่เลือก (0 = รวมทุกปี)
     */
    public function getDashboardStatsByYear(int $year): array
    {
        $stats = [];
        $where = $year > 0 ? "WHERE budget_year = :year" : "";
        $params = $year > 0 ? [':year' => $year] : [];
        
        // จำนวนโรงเรียนที่ตอบปีนั้น (ไม่ซ้ำ)
        $result = $this->query(
            "SELECT COUNT(DISTINCT school_name_input) as total FROM {$this->table} {$where}",
            $params
        );
        $stats['total_schools_year'] = $result[0]['total'] ?? 0;
        
        // จำนวน response ตามอำเภอ เฉพาะปีที่เลือก
        $stats['by_district'] = $this->query(
            "SELECT district_input, COUNT(*) as total FROM {$this->table} {$where} GROUP BY district_input ORDER BY total DESC",
            $params
        );
        
        // จำนวน response ตามสังกัด เฉพาะปีที่เลือก
        $stats['by_affiliation'] = $this->query(
            "SELECT affiliation_input, COUNT(*) as total FROM {$this->table} {$where} GROUP BY affiliation_input ORDER BY total DESC",
            $params
        );
        
        // response ล่าสุด เฉพาะปีที่เลือก
        $stats['recent'] = $this->query(
            "SELECT * FROM {$this->table} {$where} ORDER BY created_at DESC LIMIT 5",
            $params
        );
        
        return $stats;
    }
    
    /**
     * ข้อมูลสำหรับ Chart (ค่าเฉลี่ยร้อยละแต่ละตัวชี้วัด แยกตามปี)
     */
    public function getChartData(): array
    {
        $years = $this->getAvailableYears();
        $chartData = [];
        
        $indicators = [
            'q1' => 'response_q1', 'q3' => 'response_q3',
            'q4' => 'response_q4', 'q6' => 'response_q6',
            'q7' => 'response_q7', 'q9' => 'response_q9',
        ];
        
        foreach ($indicators as $key => $table) {
            $chartData[$key] = [];
            foreach ($years as $year) {
                $result = $this->query(
                    "SELECT AVG(q.percentage) as avg_pct 
                     FROM {$table} q JOIN survey_responses sr ON q.response_id = sr.id
                     WHERE sr.budget_year = :year",
                    [':year' => $year]
                );
                $chartData[$key][] = round($result[0]['avg_pct'] ?? 0, 2);
            }
        }
        
        return ['years' => $years, 'data' => $chartData];
    }
}
