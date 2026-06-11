<?php
/**
 * ===================================================
 * DashboardController - แดชบอร์ดผู้ดูแลระบบ
 * ===================================================
 */
require_once BASE_PATH . 'models/Report.php';
require_once BASE_PATH . 'models/SurveyResponse.php';

class DashboardController extends Controller
{
    private Report $reportModel;
    private SurveyResponse $surveyModel;
    
    public function __construct()
    {
        $this->reportModel = new Report();
        $this->surveyModel = new SurveyResponse();
    }
    
    /**
     * หน้า Dashboard
     */
    public function index(): void
    {
        $stats = $this->reportModel->getDashboardStats();
        $years = $this->reportModel->getAvailableYears();
        
        // รับปีที่เลือก (0 = รวมทุกปี) ถ้าไม่มีระบุมาให้ใช้ 0 เป็นค่าเริ่มต้น
        $yearQuery = $this->query('year', '0');
        $selectedYear = (int)$yearQuery;
        
        if ($selectedYear > 0 && !in_array($selectedYear, $years)) {
            $selectedYear = 0; // ถ้าระบุปีที่ไม่มีข้อมูล ให้ตกกลับมาแสดงทุกปี
        }
        
        // สถิติกรองตามปีที่เลือก (หรือทั้งหมดถ้าเป็น 0)
        $yearStats = $this->reportModel->getDashboardStatsByYear($selectedYear);
        $yearlySummary = $this->reportModel->getYearlySummary($selectedYear);
        
        // ถ้าดูรวมทุกปี ให้ใช้ยอดรวมทั้งหมด, ถ้าดูเฉพาะปี ให้ใช้ยอดรวมของปีนั้น
        $totalThisYear = $selectedYear > 0 ? ($stats['by_year_map'][$selectedYear] ?? 0) : $stats['total_responses'];
        
        $this->view('dashboard/index', [
            'title'        => 'Dashboard',
            'stats'        => $stats,       // ข้อมูลรวมทุกปี
            'yearStats'    => $yearStats,   // ข้อมูลกรองตามปีที่เลือก
            'years'        => $years,
            'selectedYear' => $selectedYear,
            'yearlySummary'=> $yearlySummary,
            'totalThisYear'=> $totalThisYear,
            'flash'        => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * API: ดึงข้อมูลสถิติ (AJAX)
     */
    public function stats(): void
    {
        $stats = $this->reportModel->getDashboardStats();
        $this->json(['success' => true, 'data' => $stats]);
    }
    
    /**
     * API: ข้อมูลสำหรับ Chart (AJAX)
     */
    public function chartData(): void
    {
        $chartData = $this->reportModel->getChartData();
        $this->json(['success' => true, 'data' => $chartData]);
    }
}
