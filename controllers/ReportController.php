<?php
/**
 * ===================================================
 * ReportController - รายงานและการเปรียบเทียบ
 * ===================================================
 */
require_once BASE_PATH . 'models/Report.php';

class ReportController extends Controller
{
    private Report $reportModel;
    
    public function __construct()
    {
        $this->reportModel = new Report();
    }
    
    /**
     * ตารางสรุปข้อมูลรายปี
     */
    public function yearly(): void
    {
        $year = (int)$this->query('year', YEAR_START);
        $summary = $this->reportModel->getYearlySummary($year);
        
        $this->view('reports/yearly', [
            'title' => "สรุปข้อมูลรายปี พ.ศ. {$year}",
            'year' => $year,
            'summary' => $summary,
            'years' => $this->reportModel->getAvailableYears(),
            'totalResponses' => $this->reportModel->count(['budget_year' => $year]),
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * ตารางเปรียบเทียบข้อมูลแต่ละปี
     */
    public function compare(): void
    {
        $comparison = $this->reportModel->getComparison();
        
        $this->view('reports/compare', [
            'title' => 'เปรียบเทียบข้อมูลแต่ละปี',
            'comparison' => $comparison,
            'years' => $this->reportModel->getAvailableYears(),
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * Export รายงาน (CSV)
     */
    public function exportReport(): void
    {
        $year = (int)$this->query('year', YEAR_START);
        $summary = $this->reportModel->getYearlySummary($year);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="report_' . $year . '_' . date('Y-m-d') . '.csv"');
        echo "\xEF\xBB\xBF"; // BOM for Excel Thai
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['ตัวชี้วัด', 'ค่าเฉลี่ย (%)', 'จำนวนที่ผ่าน/ได้รับ', 'จำนวนทั้งหมด']);
        
        $labels = [
            'q1' => 'ข้อ 1: ทักษะภาษา 3 ภาษา',
            'q3' => 'ข้อ 3: พหุปัญญา',
            'q4' => 'ข้อ 4: สุขภาวะ',
            'q6' => 'ข้อ 6: รางวัลคุณธรรม',
            'q7' => 'ข้อ 7: การแนะแนว',
            'q9' => 'ข้อ 9: รายได้ระหว่างเรียน',
        ];
        
        foreach ($labels as $key => $label) {
            $d = $summary[$key][0] ?? [];
            fputcsv($output, [
                $label,
                round($d['avg_percentage'] ?? 0, 2),
                $d['total_developed'] ?? $d['total_passed'] ?? $d['total_awarded'] ?? $d['total_guided'] ?? $d['total_income'] ?? 0,
                $d['total_all'] ?? 0,
            ]);
        }
        
        fclose($output);
        exit;
    }
}
