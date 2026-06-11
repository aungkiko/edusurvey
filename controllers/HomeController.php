<?php
/**
 * ===================================================
 * HomeController - หน้าแรก & ฟอร์มแบบสอบถาม
 * ===================================================
 */
require_once BASE_PATH . 'models/SurveyResponse.php';
require_once BASE_PATH . 'models/Question.php';

class HomeController extends Controller
{
    private SurveyResponse $surveyModel;
    private Question $questionModel;
    
    public function __construct()
    {
        $this->surveyModel = new SurveyResponse();
        $this->questionModel = new Question();
    }
    
    /**
     * หน้าแรก - แสดงฟอร์มแบบสอบถาม
     */
    public function index(): void
    {
        $questions = $this->questionModel->getGroupedByStrategy();
        
        $data = [
            'title' => 'แบบสอบถามตัวชี้วัดแผนพัฒนาการศึกษา',
            'questions' => $questions,
            'affiliations' => AFFILIATIONS,
            'districts' => DISTRICTS,
            'years' => range(YEAR_START, YEAR_END),
            'flash' => $this->getFlash(),
        ];
        
        $this->view('home/index', $data, 'main');
    }
    
    /**
     * บันทึกแบบสอบถาม
     */
    public function submit(): void
    {
        if (!$this->isPost()) {
            $this->redirect('');
            return;
        }
        
        // ตรวจสอบ CSRF
        CSRF::check();
        
        // Validate ข้อมูลพื้นฐาน
        $validator = new Validator($_POST);
        $validator->required('school_name', 'ชื่อโรงเรียน')
                  ->required('affiliation', 'สังกัด')
                  ->required('district', 'อำเภอ')
                  ->required('budget_year', 'ปีงบประมาณ')
                  ->integer('budget_year', 'ปีงบประมาณ')
                  ->required('respondent_name', 'ชื่อผู้ตอบ')
                  ->required('respondent_position', 'ตำแหน่ง')
                  ->required('respondent_phone', 'เบอร์โทรศัพท์');
        
        if ($validator->fails()) {
            die("<h2>Validation Error</h2><p>" . implode('<br>', $validator->errors()) . "</p>");
        }
        
        $year = Validator::sanitizeInt($_POST['budget_year']);
        $schoolName = Validator::sanitize($_POST['school_name']);
        
        // ตรวจสอบว่าส่งข้อมูลปีนี้แล้วหรือยัง
        if ($this->surveyModel->hasSubmitted($schoolName, $year)) {
            die("<h2>ส่งข้อมูลซ้ำ (Duplicate Submission)</h2><p>โรงเรียน {$schoolName} ได้ส่งข้อมูลสำหรับปี พ.ศ. {$year} แล้ว</p>");
        }
        
        try {
            // ข้อมูลหลัก
            $mainData = [
                'school_name_input' => $schoolName,
                'affiliation_input' => Validator::sanitize($_POST['affiliation']),
                'district_input' => Validator::sanitize($_POST['district']),
                'respondent_name' => Validator::sanitize($_POST['respondent_name'] ?? ''),
                'respondent_position' => Validator::sanitize($_POST['respondent_position'] ?? ''),
                'respondent_phone' => Validator::sanitize($_POST['respondent_phone'] ?? ''),
                'budget_year' => $year,
                'is_innovation_area' => in_array($_POST['is_innovation_area'] ?? '', ['yes', 'no']) ? $_POST['is_innovation_area'] : null,
                'status' => 'submitted',
                'submitted_at' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ];
            
            // ข้อมูลรายข้อ
            $questionsData = $this->processQuestionData();
            
            $responseId = $this->surveyModel->createFull($mainData, $questionsData);
            
            Session::flash('success', 'บันทึกข้อมูลแบบสอบถามเรียบร้อยแล้ว');
            $this->redirect('survey/success');
            
        } catch (\Exception $e) {
            // FOR DEBUGGING ONLY: Show the exact error message on screen
            die("<h2>เกิดข้อผิดพลาดระดับฐานข้อมูล (Database Error)</h2>" .
                "<p><strong>ข้อความแจ้งเตือน:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                "<p><strong>ไฟล์:</strong> " . htmlspecialchars($e->getFile()) . " (บรรทัด " . $e->getLine() . ")</p>" .
                "<p>กรุณาแคปหน้าจอนี้ส่งให้ทีมพัฒนาครับ</p>");
            
            // Session::flash('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
            // $this->redirect('');
        }
    }
    
    /**
     * หน้าบันทึกสำเร็จ
     */
    public function success(): void
    {
        $this->view('home/success', [
            'title' => 'บันทึกสำเร็จ',
            'flash' => $this->getFlash(),
        ], 'main');
    }
    
    /**
     * แปลงข้อมูลคำถามจากฟอร์ม
     */
    private function processQuestionData(): array
    {
        $data = [];
        
        // ข้อ 1: ทักษะภาษา
        $data[1] = [
            'students_developed' => Validator::sanitizeInt($_POST['q1_students_developed'] ?? 0),
            'total_students' => Validator::sanitizeInt($_POST['q1_total_students'] ?? 0),
            'percentage' => $this->calcPercentage($_POST['q1_students_developed'] ?? 0, $_POST['q1_total_students'] ?? 0),
            'languages_promoted' => json_encode($_POST['q1_languages'] ?? [], JSON_UNESCAPED_UNICODE),
            'notes' => Validator::sanitize($_POST['q1_notes'] ?? ''),
        ];
        
        // ข้อ 2: การนิเทศ
        $data[2] = [
            'is_supervised' => in_array($_POST['q2_supervised'] ?? '', ['yes', 'no']) ? $_POST['q2_supervised'] : 'no',
            'supervision_count' => Validator::sanitizeInt($_POST['q2_count'] ?? 0),
            'network_name' => Validator::sanitize($_POST['q2_network'] ?? ''),
            'last_supervision_date' => !empty($_POST['q2_date']) ? $_POST['q2_date'] : null,
            'notes' => Validator::sanitize($_POST['q2_notes'] ?? ''),
        ];
        
        // ข้อ 3: พหุปัญญา
        $data[3] = [
            'students_developed' => Validator::sanitizeInt($_POST['q3_students_developed'] ?? 0),
            'total_students' => Validator::sanitizeInt($_POST['q3_total_students'] ?? 0),
            'percentage' => $this->calcPercentage($_POST['q3_students_developed'] ?? 0, $_POST['q3_total_students'] ?? 0),
            'programs_used' => Validator::sanitize($_POST['q3_programs'] ?? ''),
            'notes' => Validator::sanitize($_POST['q3_notes'] ?? ''),
        ];
        
        // ข้อ 4: สุขภาวะ
        $data[4] = [
            'students_passed' => Validator::sanitizeInt($_POST['q4_students_passed'] ?? 0),
            'total_students' => Validator::sanitizeInt($_POST['q4_total_students'] ?? 0),
            'percentage' => $this->calcPercentage($_POST['q4_students_passed'] ?? 0, $_POST['q4_total_students'] ?? 0),
            'assessment_tools' => Validator::sanitize($_POST['q4_tools'] ?? ''),
            'assessment_year' => Validator::sanitize($_POST['q4_assessment_year'] ?? ''),
            'notes' => Validator::sanitize($_POST['q4_notes'] ?? ''),
        ];
        
        // ข้อ 5: กิจกรรมส่งเสริมรักชาติ
        $data[5] = [
            'has_activities' => in_array($_POST['q5_activities'] ?? '', ['yes', 'no']) ? $_POST['q5_activities'] : 'no',
            'activity_count' => Validator::sanitizeInt($_POST['q5_count'] ?? 0),
            'partner_network' => Validator::sanitize($_POST['q5_partner'] ?? ''),
            'activity_name' => Validator::sanitize($_POST['q5_activity_name'] ?? ''),
            'notes' => Validator::sanitize($_POST['q5_notes'] ?? ''),
        ];
        
        // ข้อ 6: รางวัลคุณธรรม
        $data[6] = [
            'students_awarded' => Validator::sanitizeInt($_POST['q6_students_awarded'] ?? 0),
            'total_students' => Validator::sanitizeInt($_POST['q6_total_students'] ?? 0),
            'percentage' => $this->calcPercentage($_POST['q6_students_awarded'] ?? 0, $_POST['q6_total_students'] ?? 0),
            'award_level' => '',
            'award_name' => '',
            'notes' => Validator::sanitize($_POST['q6_notes'] ?? ''),
        ];
        
        // ข้อ 7: การแนะแนว
        $data[7] = [
            'students_guided' => Validator::sanitizeInt($_POST['q7_students_guided'] ?? 0),
            'total_students' => Validator::sanitizeInt($_POST['q7_total_students'] ?? 0),
            'percentage' => $this->calcPercentage($_POST['q7_students_guided'] ?? 0, $_POST['q7_total_students'] ?? 0),
            'guidance_types' => json_encode($_POST['q7_guidance_types'] ?? [], JSON_UNESCAPED_UNICODE),
            'notes' => Validator::sanitize($_POST['q7_notes'] ?? ''),
        ];
        
        // ข้อ 8: MOU
        $data[8] = [
            'current_mou_count' => Validator::sanitizeInt($_POST['q8_current'] ?? 0),
            'previous_mou_count' => Validator::sanitizeInt($_POST['q8_previous'] ?? 0),
            'increase_rate' => $this->calcIncreaseRate($_POST['q8_current'] ?? 0, $_POST['q8_previous'] ?? 0),
            'mou_partner_types' => json_encode($_POST['q8_partner_types'] ?? [], JSON_UNESCAPED_UNICODE),
            'notes' => Validator::sanitize($_POST['q8_notes'] ?? ''),
        ];
        
        // ข้อ 9: รายได้ระหว่างเรียน (แยกตามระดับชั้น)
        $hasMattayom  = !empty($_POST['q9_has_mattayom'])  ? 1 : 0;
        $hasVocational = !empty($_POST['q9_has_vocational']) ? 1 : 0;
        $hasAssociate  = !empty($_POST['q9_has_associate'])  ? 1 : 0;

        $mattayomIncome   = $hasMattayom  ? Validator::sanitizeInt($_POST['q9_mattayom_income']   ?? 0) : 0;
        $mattayomTotal    = $hasMattayom  ? Validator::sanitizeInt($_POST['q9_mattayom_total']    ?? 0) : 0;
        $vocationalIncome = $hasVocational ? Validator::sanitizeInt($_POST['q9_vocational_income'] ?? 0) : 0;
        $vocationalTotal  = $hasVocational ? Validator::sanitizeInt($_POST['q9_vocational_total']  ?? 0) : 0;
        $associateIncome  = $hasAssociate  ? Validator::sanitizeInt($_POST['q9_associate_income']  ?? 0) : 0;
        $associateTotal   = $hasAssociate  ? Validator::sanitizeInt($_POST['q9_associate_total']   ?? 0) : 0;

        $totalIncome  = $mattayomIncome  + $vocationalIncome  + $associateIncome;
        $totalStudents = $mattayomTotal  + $vocationalTotal   + $associateTotal;

        $data[9] = [
            'students_with_income' => $totalIncome,
            'total_students'       => $totalStudents,
            'percentage'           => $this->calcPercentage($totalIncome, $totalStudents),
            'income_type'          => Validator::sanitize($_POST['q9_income_type'] ?? ''),
            'has_mattayom_pak'     => $hasMattayom,
            'mattayom_income'      => $mattayomIncome,
            'mattayom_total'       => $mattayomTotal,
            'has_vocational'       => $hasVocational,
            'vocational_income'    => $vocationalIncome,
            'vocational_total'     => $vocationalTotal,
            'has_associate'        => $hasAssociate,
            'associate_income'     => $associateIncome,
            'associate_total'      => $associateTotal,
            'notes'                => Validator::sanitize($_POST['q9_notes'] ?? ''),
        ];
        
        // ข้อ 10 (ITA): ผลการประเมินคุณธรรมความโปร่งใส
        $data[10] = [
            'ita_result' => Validator::sanitize($_POST['q10_result'] ?? ''),
            'ita_score' => Validator::sanitizeFloat($_POST['q10_score'] ?? 0),
            'assessment_year' => Validator::sanitize($_POST['q10_year'] ?? ''),
            'improvement_areas' => Validator::sanitize($_POST['q10_improvement'] ?? ''),
            'notes' => Validator::sanitize($_POST['q10_notes'] ?? ''),
        ];
        
        $isInnovationArea = in_array($_POST['is_innovation_area'] ?? '', ['yes', 'no']) ? $_POST['is_innovation_area'] : null;
        
        // ข้อ 11: จำนวนหุ้นส่วนความร่วมมือ (ยุทธศาสตร์ที่ 7)
        $data[11] = [
            'partnership_count' => $isInnovationArea === 'yes' ? Validator::sanitizeInt($_POST['q11_partnership_count'] ?? 0) : 0,
            'notes' => $isInnovationArea === 'yes' ? Validator::sanitize($_POST['q11_notes'] ?? '') : '',
        ];
        
        // ข้อ 12: จำนวนนวัตกรรมที่ใช้จริง (ยุทธศาสตร์ที่ 7)
        $hasInnovations = in_array($_POST['q12_has_innovations'] ?? '', ['yes', 'no']) ? $_POST['q12_has_innovations'] : 'no';
        $data[12] = [
            'has_innovations' => $isInnovationArea === 'yes' ? $hasInnovations : 'no',
            'curriculum_count'  => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_curriculum'] ?? 0) : 0,
            'teaching_count'    => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_teaching'] ?? 0) : 0,
            'media_count'       => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_media'] ?? 0) : 0,
            'assessment_count'  => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_assessment'] ?? 0) : 0,
            'management_count'  => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_management'] ?? 0) : 0,
            'other_count'       => ($isInnovationArea === 'yes' && $hasInnovations === 'yes') ? Validator::sanitizeInt($_POST['q12_other'] ?? 0) : 0,
            'notes' => $isInnovationArea === 'yes' ? Validator::sanitize($_POST['q12_notes'] ?? '') : '',
        ];
        
        return $data;
    }
    
    /**
     * คำนวณร้อยละ
     */
    private function calcPercentage($numerator, $denominator): float
    {
        $num = (float)$numerator;
        $den = (float)$denominator;
        if ($den <= 0) return 0;
        return round(($num / $den) * 100, 2);
    }
    
    /**
     * คำนวณอัตราการเพิ่มขึ้น
     */
    private function calcIncreaseRate($current, $previous): float
    {
        $cur = (float)$current;
        $prev = (float)$previous;
        if ($prev <= 0) return ($cur > 0 ? 100 : 0);
        return round((($cur - $prev) / $prev) * 100, 2);
    }
}
