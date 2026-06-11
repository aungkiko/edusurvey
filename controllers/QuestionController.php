<?php
/**
 * ===================================================
 * QuestionController - จัดการคำถาม (Admin)
 * ===================================================
 */
require_once BASE_PATH . 'models/Question.php';

class QuestionController extends Controller
{
    private Question $questionModel;
    
    public function __construct()
    {
        $this->questionModel = new Question();
    }
    
    /**
     * รายการคำถามทั้งหมด
     */
    public function index(): void
    {
        $questions = $this->questionModel->getGroupedByStrategy();
        
        $this->view('questions/index', [
            'title' => 'จัดการคำถาม',
            'questions' => $questions,
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * ฟอร์มเพิ่มคำถามใหม่
     */
    public function create(): void
    {
        $this->view('questions/form', [
            'title' => 'เพิ่มคำถามใหม่',
            'question' => null,
            'strategies' => STRATEGIES,
            'nextNumber' => $this->questionModel->getMaxNumber() + 1,
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * บันทึกคำถามใหม่
     */
    public function store(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/questions');
            return;
        }
        
        CSRF::check();
        
        $validator = new Validator($_POST);
        $validator->required('question_number', 'หมายเลขข้อ')
                  ->required('strategy_number', 'ยุทธศาสตร์')
                  ->required('question_text', 'เนื้อหาคำถาม');
        
        if ($validator->fails()) {
            Session::flash('error', implode('<br>', $validator->errors()));
            $this->redirect('admin/questions/create');
            return;
        }
        
        $stratNum = Validator::sanitizeInt($this->input('strategy_number'));
        $strategies = STRATEGIES;
        
        try {
            $this->questionModel->create([
                'question_number' => Validator::sanitizeInt($this->input('question_number')),
                'strategy_number' => $stratNum,
                'strategy_name' => $strategies[$stratNum] ?? "ยุทธศาสตร์ที่ {$stratNum}",
                'question_text' => Validator::sanitize($this->input('question_text')),
                'question_type' => Validator::sanitize($this->input('question_type', 'percentage')),
                'sort_order' => Validator::sanitizeInt($this->input('sort_order', 0)),
                'is_active' => 1,
            ]);
            
            Session::flash('success', 'เพิ่มคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        
        $this->redirect('admin/questions');
    }
    
    /**
     * ฟอร์มแก้ไขคำถาม
     */
    public function edit(): void
    {
        $id = (int)$this->query('id', 0);
        $question = $this->questionModel->findById($id);
        
        if (!$question) {
            Session::flash('error', 'ไม่พบคำถามที่ต้องการ');
            $this->redirect('admin/questions');
            return;
        }
        
        $this->view('questions/form', [
            'title' => 'แก้ไขคำถาม',
            'question' => $question,
            'strategies' => STRATEGIES,
            'nextNumber' => null,
            'flash' => $this->getFlash(),
        ], 'admin');
    }
    
    /**
     * อัปเดตคำถาม
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/questions');
            return;
        }
        
        CSRF::check();
        
        $id = (int)$this->input('id', 0);
        $stratNum = Validator::sanitizeInt($this->input('strategy_number'));
        $strategies = STRATEGIES;
        
        try {
            $this->questionModel->update($id, [
                'question_number' => Validator::sanitizeInt($this->input('question_number')),
                'strategy_number' => $stratNum,
                'strategy_name' => $strategies[$stratNum] ?? "ยุทธศาสตร์ที่ {$stratNum}",
                'question_text' => Validator::sanitize($this->input('question_text')),
                'question_type' => Validator::sanitize($this->input('question_type', 'percentage')),
                'sort_order' => Validator::sanitizeInt($this->input('sort_order', 0)),
                'is_active' => (int)$this->input('is_active', 1),
            ]);
            
            Session::flash('success', 'อัปเดตคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        
        $this->redirect('admin/questions');
    }
    
    /**
     * ลบคำถาม
     */
    public function delete(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/questions');
            return;
        }
        
        CSRF::check();
        $id = (int)$this->input('id', 0);
        
        try {
            // Soft delete: set is_active = 0
            $this->questionModel->update($id, ['is_active' => 0]);
            Session::flash('success', 'ลบคำถามเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Session::flash('error', 'เกิดข้อผิดพลาด');
        }
        
        $this->redirect('admin/questions');
    }
}
