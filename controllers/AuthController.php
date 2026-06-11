<?php
/**
 * ===================================================
 * AuthController - ระบบ Login/Logout
 * ===================================================
 */
require_once BASE_PATH . 'models/User.php';

class AuthController extends Controller
{
    private User $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    /**
     * แสดงหน้า Login
     */
    public function loginForm(): void
    {
        // ถ้า login แล้ว redirect ไป dashboard
        if (Auth::check()) {
            $this->redirect('admin/dashboard');
            return;
        }
        
        $this->view('auth/login', [
            'title' => 'เข้าสู่ระบบผู้ดูแล',
            'flash' => $this->getFlash(),
        ], 'main');
    }
    
    /**
     * ดำเนินการ Login
     */
    public function login(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/login');
            return;
        }
        
        CSRF::check();
        
        $username = Validator::sanitize($this->input('username', ''));
        $password = $this->input('password', '');
        
        // Validate
        $validator = new Validator($_POST);
        $validator->required('username', 'ชื่อผู้ใช้')
                  ->required('password', 'รหัสผ่าน');
        
        if ($validator->fails()) {
            Session::flash('error', implode('<br>', $validator->errors()));
            $this->redirect('admin/login');
            return;
        }
        
        // ค้นหาผู้ใช้
        $user = $this->userModel->findByUsername($username);
        
        if (!$user || !Auth::verifyPassword($password, $user['password_hash'])) {
            Session::flash('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
            $this->redirect('admin/login');
            return;
        }
        
        // ตรวจสอบสถานะ active
        if (!$user['is_active']) {
            Session::flash('error', 'บัญชีผู้ใช้ถูกระงับ กรุณาติดต่อผู้ดูแลระบบ');
            $this->redirect('admin/login');
            return;
        }
        
        // Login สำเร็จ
        Auth::login($user);
        $this->userModel->updateLastLogin($user['id']);
        
        Session::flash('success', 'ยินดีต้อนรับ คุณ' . $user['full_name']);
        $this->redirect('admin/dashboard');
    }
    
    /**
     * Logout
     */
    public function logout(): void
    {
        Auth::logout();
        Session::start();
        Session::flash('success', 'ออกจากระบบเรียบร้อยแล้ว');
        $this->redirect('admin/login');
    }
}
