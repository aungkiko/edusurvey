<?php
/**
 * ===================================================
 * Route Definitions
 * ===================================================
 * รูปแบบ: 'URL_PATTERN' => ['controller' => 'ControllerName', 'method' => 'methodName', 'auth' => true/false]
 * auth = true หมายถึงต้อง login ก่อน
 */

$routes = [
    // === หน้าสาธารณะ (Public) ===
    ''                    => ['controller' => 'HomeController',      'method' => 'index',    'auth' => false],
    'survey/submit'       => ['controller' => 'HomeController',      'method' => 'submit',   'auth' => false],
    'survey/success'      => ['controller' => 'HomeController',      'method' => 'success',  'auth' => false],
    
    // === ระบบ Authentication ===
    'admin/login'         => ['controller' => 'AuthController',      'method' => 'loginForm','auth' => false],
    'admin/authenticate'  => ['controller' => 'AuthController',      'method' => 'login',    'auth' => false],
    'admin/logout'        => ['controller' => 'AuthController',      'method' => 'logout',   'auth' => true],
    
    // === Dashboard ===
    'admin/dashboard'     => ['controller' => 'DashboardController', 'method' => 'index',    'auth' => true],
    'admin/dashboard/stats' => ['controller' => 'DashboardController', 'method' => 'stats',  'auth' => true],
    
    // === จัดการแบบสอบถาม (Admin) ===
    'admin/surveys'       => ['controller' => 'SurveyController',    'method' => 'index',    'auth' => true],
    'admin/surveys/view'  => ['controller' => 'SurveyController',    'method' => 'show',     'auth' => true],
    'admin/surveys/edit'  => ['controller' => 'SurveyController',    'method' => 'edit',     'auth' => true],
    'admin/surveys/update'=> ['controller' => 'SurveyController',    'method' => 'update',   'auth' => true],
    'admin/surveys/delete'=> ['controller' => 'SurveyController',    'method' => 'delete',   'auth' => true],
    'admin/surveys/export'=> ['controller' => 'SurveyController',    'method' => 'export',   'auth' => true],
    
    // === จัดการคำถาม (Admin) ===
    'admin/questions'       => ['controller' => 'QuestionController', 'method' => 'index',   'auth' => true],
    'admin/questions/create'=> ['controller' => 'QuestionController', 'method' => 'create',  'auth' => true],
    'admin/questions/store' => ['controller' => 'QuestionController', 'method' => 'store',   'auth' => true],
    'admin/questions/edit'  => ['controller' => 'QuestionController', 'method' => 'edit',    'auth' => true],
    'admin/questions/update'=> ['controller' => 'QuestionController', 'method' => 'update',  'auth' => true],
    'admin/questions/delete'=> ['controller' => 'QuestionController', 'method' => 'delete',  'auth' => true],
    
    // === รายงาน (Admin) ===
    'admin/reports/yearly'  => ['controller' => 'ReportController',  'method' => 'yearly',   'auth' => true],
    'admin/reports/compare' => ['controller' => 'ReportController',  'method' => 'compare',  'auth' => true],
    'admin/reports/export'  => ['controller' => 'ReportController',  'method' => 'exportReport', 'auth' => true],
    
    // === ตั้งค่าระบบ (Admin) ===
    'admin/settings'        => ['controller' => 'SettingController', 'method' => 'index',    'auth' => true],
    'admin/settings/update' => ['controller' => 'SettingController', 'method' => 'update',   'auth' => true],
    
    // === API Endpoints (AJAX) ===
    'api/survey/autosave'   => ['controller' => 'HomeController',    'method' => 'autosave', 'auth' => false],
    'api/dashboard/chart'   => ['controller' => 'DashboardController','method' => 'chartData','auth' => true],
];
