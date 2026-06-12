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
        
        // Q1
        $q1 = $summary['q1'][0] ?? [];
        fputcsv($output, ['ข้อ 1: ทักษะภาษา 3 ภาษา', round($q1['avg_percentage'] ?? 0, 2), $q1['total_developed'] ?? 0, $q1['total_all'] ?? 0]);

        // Q2
        $q2 = $summary['q2'][0] ?? [];
        $pct2 = ($q2['total_count'] > 0) ? ($q2['supervised_count'] / $q2['total_count'] * 100) : 0;
        fputcsv($output, ['ข้อ 2: การได้รับการนิเทศ', round($pct2, 2), $q2['supervised_count'] ?? 0, $q2['total_count'] ?? 0]);

        // Q3
        $q3 = $summary['q3'][0] ?? [];
        fputcsv($output, ['ข้อ 3: พหุปัญญา', round($q3['avg_percentage'] ?? 0, 2), $q3['total_developed'] ?? 0, $q3['total_all'] ?? 0]);

        // Q4
        $q4 = $summary['q4'][0] ?? [];
        fputcsv($output, ['ข้อ 4: สุขภาวะ', round($q4['avg_percentage'] ?? 0, 2), $q4['total_passed'] ?? 0, $q4['total_all'] ?? 0]);

        // Q5
        $q5 = $summary['q5'][0] ?? [];
        $pct5 = ($q5['total_count'] > 0) ? ($q5['activity_count'] / $q5['total_count'] * 100) : 0;
        fputcsv($output, ['ข้อ 5: กิจกรรมรักชาติและประวัติศาสตร์', round($pct5, 2), $q5['activity_count'] ?? 0, $q5['total_count'] ?? 0]);

        // Q6
        $q6 = $summary['q6'][0] ?? [];
        fputcsv($output, ['ข้อ 6: รางวัลคุณธรรม', round($q6['avg_percentage'] ?? 0, 2), $q6['total_awarded'] ?? 0, $q6['total_all'] ?? 0]);

        // Q7
        $q7 = $summary['q7'][0] ?? [];
        fputcsv($output, ['ข้อ 7: การแนะแนว', round($q7['avg_percentage'] ?? 0, 2), $q7['total_guided'] ?? 0, $q7['total_all'] ?? 0]);

        // Q8
        $q8 = $summary['q8'][0] ?? [];
        fputcsv($output, ['ข้อ 8: เครือข่าย MOU อาชีวศึกษา', round($q8['avg_rate'] ?? 0, 2), $q8['total_current'] ?? 0, $q8['total_previous'] ?? 0]);

        // Q9
        $q9 = $summary['q9'][0] ?? [];
        fputcsv($output, ['ข้อ 9: รายได้ระหว่างเรียน', round($q9['avg_percentage'] ?? 0, 2), $q9['total_income'] ?? 0, $q9['total_all'] ?? 0]);

        // Q11 (ข้อ 10)
        $q11 = $summary['q11'][0] ?? [];
        fputcsv($output, ['ข้อ 10: หุ้นส่วนความร่วมมือ', round($q11['avg_count'] ?? 0, 2), $q11['total_count'] ?? 0, '-']);

        // Q12 (ข้อ 11)
        $q12 = $summary['q12'][0] ?? [];
        $pct12 = ($q12['total_count'] > 0) ? ($q12['with_innovations'] / $q12['total_count'] * 100) : 0;
        fputcsv($output, ['ข้อ 11: จำนวนนวัตกรรมที่ใช้จริง', round($pct12, 2), $q12['with_innovations'] ?? 0, $q12['total_count'] ?? 0]);

        // Q10 (ข้อ 12)
        $itaData = $summary['q10'] ?? [];
        $aaCount = 0; $totalIta = 0;
        foreach($itaData as $ita) {
            $totalIta += $ita['count'];
            if(strpos($ita['ita_result'], 'AA') !== false) $aaCount += $ita['count'];
        }
        $pct10 = ($totalIta > 0) ? ($aaCount / $totalIta * 100) : 0;
        fputcsv($output, ['ข้อ 12: การประเมิน ITA', round($pct10, 2), $aaCount, $totalIta]);
        
        fclose($output);
        exit;
    }
}
