<?php
/**
 * ===================================================
 * Base Controller
 * ===================================================
 * คลาสพื้นฐานสำหรับ Controller ทุกตัว
 * มี helper functions: view(), redirect(), json()
 */
class Controller
{
    /**
     * แสดง View พร้อมส่งข้อมูล
     * @param string $viewPath เส้นทาง view (เช่น 'home/index')
     * @param array $data ข้อมูลที่ส่งไปยัง view
     * @param string $layout layout ที่ใช้ ('main' หรือ 'admin')
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        // แยกตัวแปรจาก array ให้ view ใช้งานได้
        extract($data);
        
        // สร้าง CSRF token สำหรับฟอร์ม
        $csrfToken = CSRF::generate();
        
        // เก็บ content ของ view ไว้ใน buffer
        $viewFile = BASE_PATH . 'views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            die("View not found: {$viewPath}");
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        // โหลด layout
        $layoutFile = BASE_PATH . 'views/layouts/' . $layout . '.php';
        
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * Redirect ไปยัง URL อื่น
     */
    protected function redirect(string $url, array $flash = []): void
    {
        // เก็บ flash message
        if (!empty($flash)) {
            $_SESSION['flash'] = $flash;
        }
        
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit;
    }
    
    /**
     * ส่ง JSON Response (สำหรับ AJAX)
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * ดึงข้อมูลจาก POST request
     */
    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * ดึงข้อมูลจาก GET request
     */
    protected function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * ตรวจสอบว่าเป็น POST request
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * ตรวจสอบว่าเป็น AJAX request
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * ดึง flash message และลบออกจาก session
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
