<?php
/**
 * ===================================================
 * App - Main Router & Dispatcher
 * ===================================================
 * แยกวิเคราะห์ URL แล้ว dispatch ไปยัง Controller ที่ถูกต้อง
 */
class App
{
    private array $routes;
    
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
    
    /**
     * รันแอปพลิเคชัน - วิเคราะห์ URL แล้ว dispatch
     */
    public function run(): void
    {
        // ดึง URL จาก query string
        $url = $this->parseUrl();
        
        // ค้นหา route ที่ตรงกัน
        $route = $this->matchRoute($url);
        
        if ($route === null) {
            $this->show404();
            return;
        }
        
        // ตรวจสอบ authentication
        if ($route['auth'] && !Auth::check()) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
        
        // โหลดและเรียก Controller
        $this->dispatch($route);
    }
    
    /**
     * แยกวิเคราะห์ URL จาก query string
     */
    private function parseUrl(): string
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return strtolower($url);
    }
    
    /**
     * ค้นหา route ที่ตรงกับ URL
     */
    private function matchRoute(string $url): ?array
    {
        // ตรงกันแบบ exact match
        if (isset($this->routes[$url])) {
            return $this->routes[$url];
        }
        
        return null;
    }
    
    /**
     * Dispatch ไปยัง Controller
     */
    private function dispatch(array $route): void
    {
        $controllerName = $route['controller'];
        $methodName = $route['method'];
        $controllerFile = BASE_PATH . 'controllers/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            $this->show404();
            return;
        }
        
        require_once $controllerFile;
        
        if (!class_exists($controllerName)) {
            $this->show404();
            return;
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            $this->show404();
            return;
        }
        
        // เรียก method
        $controller->$methodName();
    }
    
    /**
     * แสดงหน้า 404
     */
    private function show404(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - ไม่พบหน้าที่ต้องการ | ' . APP_NAME . '</title>
            <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
            <style>
                * { margin:0; padding:0; box-sizing:border-box; }
                body { font-family:"Noto Sans Thai",sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center;
                       background:linear-gradient(135deg,#1B5E20 0%,#2E7D32 50%,#F9A825 100%); color:#fff; }
                .container { text-align:center; padding:40px; }
                h1 { font-size:120px; font-weight:700; opacity:0.3; }
                h2 { font-size:28px; margin:10px 0; }
                p { font-size:16px; opacity:0.8; margin-bottom:30px; }
                a { display:inline-block; padding:12px 32px; background:rgba(255,255,255,0.2); color:#fff; text-decoration:none;
                    border-radius:50px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.3);
                    transition:all 0.3s; }
                a:hover { background:rgba(255,255,255,0.3); transform:translateY(-2px); }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>404</h1>
                <h2>ไม่พบหน้าที่ต้องการ</h2>
                <p>หน้าที่คุณกำลังค้นหาอาจถูกย้าย ลบ หรือไม่มีอยู่</p>
                <a href="' . BASE_URL . '">🏠 กลับหน้าแรก</a>
            </div>
        </body>
        </html>';
    }
}
