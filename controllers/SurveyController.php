<?php
/**
 * ===================================================
 * SurveyController - จัดการข้อมูลแบบสอบถาม (Admin)
 * ===================================================
 */
require_once BASE_PATH . 'models/SurveyResponse.php';

class SurveyController extends Controller
{
    private SurveyResponse $surveyModel;
    
    public function __construct()
    {
        $this->surveyModel = new SurveyResponse();
    }
    
    /**
     * รายการ responses ทั้งหมด
     */
    public function index(): void
    {
        $page = max(1, (int)($this->query('page', 1)));
        $filters = [
            'year' => $this->query('year', ''),
            'district' => $this->query('district', ''),
            'affiliation' => $this->query('affiliation', ''),
            'status' => $this->query('status', ''),
            'search' => $this->query('search', ''),
        ];
        
        $result = $this->surveyModel->getList($page, PER_PAGE, $filters);
        
        $this->view('survey/index', [
            'title' => 'จัดการข้อมูลแบบสอบถาม',
            'responses' => $result['data'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'currentPage' => $result['current_page'],
            'filters' => $filters,
            'years' => array_column($this->surveyModel->countByYear(), 'budget_year'),
            'districts' => DISTRICTS,
            'affiliations' => AFFILIATIONS,
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * ดูรายละเอียด response
     */
    public function show(): void
    {
        $id = (int)$this->query('id', 0);
        $response = $this->surveyModel->getFullResponse($id);
        
        if (!$response) {
            Session::flash('error', 'ไม่พบข้อมูลที่ต้องการ');
            $this->redirect('admin/surveys');
            return;
        }
        
        $this->view('survey/view', [
            'title' => 'รายละเอียดแบบสอบถาม #' . $id,
            'response' => $response,
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * ฟอร์มแก้ไข response
     */
    public function edit(): void
    {
        $id = (int)$this->query('id', 0);
        $response = $this->surveyModel->getFullResponse($id);
        
        if (!$response) {
            Session::flash('error', 'ไม่พบข้อมูลที่ต้องการ');
            $this->redirect('admin/surveys');
            return;
        }
        
        $this->view('survey/edit', [
            'title' => 'แก้ไขแบบสอบถาม #' . $id,
            'response' => $response,
            'affiliations' => AFFILIATIONS,
            'districts' => DISTRICTS,
            'years' => range(YEAR_START, YEAR_END),
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * อัปเดต response
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/surveys');
            return;
        }
        
        CSRF::check();
        
        $id = (int)$this->input('id', 0);
        
        try {
            // อัปเดตข้อมูลหลัก
            $this->surveyModel->update($id, [
                'school_name_input' => Validator::sanitize($this->input('school_name', '')),
                'affiliation_input' => Validator::sanitize($this->input('affiliation', '')),
                'district_input' => Validator::sanitize($this->input('district', '')),
                'respondent_name' => Validator::sanitize($this->input('respondent_name', '')),
                'status' => Validator::sanitize($this->input('status', 'submitted')),
            ]);
            
            Session::flash('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
            
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        
        $this->redirect('admin/surveys/view?id=' . $id);
    }
    
    /**
     * ลบ response
     */
    public function delete(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/surveys');
            return;
        }
        
        CSRF::check();
        
        $id = (int)$this->input('id', 0);
        
        try {
            $this->surveyModel->delete($id);
            Session::flash('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาดในการลบ');
        }
        
        $this->redirect('admin/surveys');
    }
    
    /**
     * Export ข้อมูลเป็น CSV แบบครบทุกข้อ
     */
    public function export(): void
    {
        $year = $this->query('year', '');
        $district = $this->query('district', '');
        $affiliation = $this->query('affiliation', '');
        $status = $this->query('status', '');
        $search = $this->query('search', '');
        
        $filters = [];
        if ($year) $filters['year'] = $year;
        if ($district) $filters['district'] = $district;
        if ($affiliation) $filters['affiliation'] = $affiliation;
        if ($status) $filters['status'] = $status;
        if ($search) $filters['search'] = $search;
        
        $result = $this->surveyModel->getList(1, 99999, $filters);
        $responses = $result['data'];
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="edusurvey_export_' . date('Y-m-d') . '.csv"');
        echo "\xEF\xBB\xBF"; // BOM
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'ลำดับ', 'ชื่อโรงเรียน', 'สังกัด', 'อำเภอ', 'ผู้ตอบ', 'ตำแหน่ง', 'เบอร์โทร', 'ปี พ.ศ.', 'สถานะ', 'วันที่ส่ง',
            'Q1_ผู้เรียนที่ได้รับการพัฒนา', 'Q1_ผู้เรียนทั้งหมด', 'Q1_ภาษาที่ส่งเสริม', 'Q1_หมายเหตุ',
            'Q2_ได้รับการนิเทศ(yes/no)', 'Q2_จำนวนครั้ง/ปี', 'Q2_ชื่อเครือข่าย', 'Q2_วันที่นิเทศ', 'Q2_หมายเหตุ',
            'Q3_ผู้เรียนที่ได้รับการพัฒนา', 'Q3_ผู้เรียนทั้งหมด', 'Q3_กิจกรรม/โปรแกรม', 'Q3_หมายเหตุ',
            'Q4_ผู้เรียนที่ผ่านเกณฑ์', 'Q4_ผู้เรียนทั้งหมด', 'Q4_เครื่องมือประเมิน', 'Q4_ปีที่ประเมิน', 'Q4_หมายเหตุ',
            'Q5_มีการจัดกิจกรรม(yes/no)', 'Q5_จำนวนกิจกรรม/ปี', 'Q5_ชื่อภาคีเครือข่าย', 'Q5_ชื่อกิจกรรม', 'Q5_หมายเหตุ',
            'Q6_ผู้ได้รับรางวัล', 'Q6_ผู้เรียนทั้งหมด', 'Q6_ระดับรางวัล', 'Q6_ชื่อรางวัล', 'Q6_หมายเหตุ',
            'Q7_ผู้ได้รับการแนะแนว', 'Q7_ผู้เรียนทั้งหมด', 'Q7_ประเภทการแนะแนว', 'Q7_หมายเหตุ',
            'Q8_MOU_ปีปัจจุบัน', 'Q8_MOU_ปีที่แล้ว', 'Q8_ประเภทหน่วยงาน', 'Q8_หมายเหตุ',
            'Q9_ผู้เรียนที่มีรายได้', 'Q9_ผู้เรียนทั้งหมด', 'Q9_รูปแบบรายได้', 'Q9_หมายเหตุ',
            'Q10_ผลประเมินITA', 'Q10_คะแนนITA', 'Q10_ปีที่ประเมิน', 'Q10_ประเด็นพัฒนา', 'Q10_หมายเหตุ',
            'Q11_จำนวนหุ้นส่วน(แห่ง)', 'Q11_หมายเหตุ',
            'Q12_นวัตกรรม_หลักสูตร', 'Q12_นวัตกรรม_การสอน', 'Q12_นวัตกรรม_สื่อ', 'Q12_นวัตกรรม_วัดผล', 'Q12_นวัตกรรม_บริหาร', 'Q12_หมายเหตุ'
        ]);
        
        foreach ($responses as $i => $r) {
            $full = $this->surveyModel->getFullResponse($r['id']);
            $q1 = $full['q1'] ?? [];
            $q2 = $full['q2'] ?? [];
            $q3 = $full['q3'] ?? [];
            $q4 = $full['q4'] ?? [];
            $q5 = $full['q5'] ?? [];
            $q6 = $full['q6'] ?? [];
            $q7 = $full['q7'] ?? [];
            $q8 = $full['q8'] ?? [];
            $q9 = $full['q9'] ?? [];
            $q10 = $full['q10'] ?? [];
            $q11 = $full['q11'] ?? [];
            $q12 = $full['q12'] ?? [];
            
            // Helper to parse JSON arrays safely
            $parseJson = function($jsonStr) {
                if (!$jsonStr) return '-';
                $arr = json_decode($jsonStr, true);
                return is_array($arr) ? implode(', ', $arr) : $jsonStr;
            };

            fputcsv($output, [
                $i + 1,
                $r['school_name_input'],
                $r['affiliation_input'],
                $r['district_input'],
                $r['respondent_name'] ?? '-',
                $r['respondent_position'] ?? '-',
                $r['respondent_phone'] ?? '-',
                $r['budget_year'],
                $r['status'],
                $r['submitted_at'] ?? $r['created_at'],
                // Q1
                $q1['students_developed'] ?? '0', $q1['total_students'] ?? '0', $parseJson($q1['languages_promoted'] ?? ''), $q1['notes'] ?? '-',
                // Q2
                $q2['is_supervised'] ?? 'no', $q2['supervision_count'] ?? '0', $q2['network_name'] ?? '-', $q2['last_supervision_date'] ?? '-', $q2['notes'] ?? '-',
                // Q3
                $q3['students_developed'] ?? '0', $q3['total_students'] ?? '0', $q3['programs_used'] ?? '-', $q3['notes'] ?? '-',
                // Q4
                $q4['students_passed'] ?? '0', $q4['total_students'] ?? '0', $q4['assessment_tools'] ?? '-', $q4['assessment_year'] ?? '-', $q4['notes'] ?? '-',
                // Q5
                $q5['has_activities'] ?? 'no', $q5['activity_count'] ?? '0', $q5['partner_network'] ?? '-', $q5['activity_name'] ?? '-', $q5['notes'] ?? '-',
                // Q6
                $q6['students_awarded'] ?? '0', $q6['total_students'] ?? '0', $q6['award_level'] ?? '-', $q6['award_name'] ?? '-', $q6['notes'] ?? '-',
                // Q7
                $q7['students_guided'] ?? '0', $q7['total_students'] ?? '0', $parseJson($q7['guidance_types'] ?? ''), $q7['notes'] ?? '-',
                // Q8
                $q8['current_mou_count'] ?? '0', $q8['previous_mou_count'] ?? '0', $parseJson($q8['mou_partner_types'] ?? ''), $q8['notes'] ?? '-',
                // Q9
                $q9['students_with_income'] ?? '0', $q9['total_students'] ?? '0', $q9['income_type'] ?? '-', $q9['notes'] ?? '-',
                // Q10
                $q10['ita_result'] ?? '-', $q10['ita_score'] ?? '-', $q10['assessment_year'] ?? '-', $q10['improvement_areas'] ?? '-', $q10['notes'] ?? '-',
                // Q11
                $q11['partnership_count'] ?? '0', $q11['notes'] ?? '-',
                // Q12
                $q12['curriculum_count'] ?? '0', $q12['teaching_count'] ?? '0', $q12['media_count'] ?? '0', $q12['assessment_count'] ?? '0', $q12['management_count'] ?? '0', $q12['notes'] ?? '-'
            ]);
        }
        
        fclose($output);
        exit;
    }
}
