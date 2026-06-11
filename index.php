<?php
/**
 * ===================================================
 * EduSurvey - Front Controller (Entry Point)
 * ระบบแบบสอบถามตัวชี้วัดแผนพัฒนาการศึกษาจังหวัดปัตตานี
 * ===================================================
 * 
 * ทุก request จะถูก route ผ่านไฟล์นี้โดย .htaccess
 * จากนั้นจะ dispatch ไปยัง Controller ที่เหมาะสม
 */

// --- Error Reporting (ปิดใน Production) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Define Base Path ---
define('BASE_PATH', __DIR__ . '/');
define('BASE_URL', '/edusurvey/');

// --- Load Configuration ---
require_once BASE_PATH . 'config/app.php';
require_once BASE_PATH . 'config/database.php';

// --- Load Core Classes ---
require_once BASE_PATH . 'core/Session.php';
require_once BASE_PATH . 'core/CSRF.php';
require_once BASE_PATH . 'core/Auth.php';
require_once BASE_PATH . 'core/Validator.php';
require_once BASE_PATH . 'core/Model.php';
require_once BASE_PATH . 'core/Controller.php';
require_once BASE_PATH . 'core/App.php';

// --- Load Routes ---
require_once BASE_PATH . 'config/routes.php';

// --- Start Session ---
Session::start();

// --- Initialize & Run Application ---
$app = new App($routes);
$app->run();
