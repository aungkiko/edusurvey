<?php
/**
 * ===================================================
 * Application Configuration
 * ===================================================
 * ค่าตั้งต้นของระบบ EduSurvey
 */

// --- App Settings ---
define('APP_NAME', 'EduSurvey');
define('APP_FULL_NAME', 'ระบบแบบสำรวจตามตัวชี้วัดแผนพัฒนาการศึกษาจังหวัดปัตตานี');
define('APP_SUBTITLE', 'แผนพัฒนาการศึกษาจังหวัดปัตตานี ฉบับทบทวน พ.ศ. 2569-2573');
define('APP_VERSION', '1.0.0');

// --- ช่วงปีที่จัดเก็บข้อมูล (โหลดจาก settings.json) ---
$settingsFile = BASE_PATH . 'config/settings.json';
$appSettings = [];
if (file_exists($settingsFile)) {
    $json = file_get_contents($settingsFile);
    $appSettings = json_decode($json, true) ?: [];
}

define('YEAR_START', isset($appSettings['year_start']) ? (int)$appSettings['year_start'] : 2569);
define('YEAR_END', isset($appSettings['year_end']) ? (int)$appSettings['year_end'] : 2573);
define('SURVEY_IS_OPEN', isset($appSettings['survey_is_open']) ? (bool)$appSettings['survey_is_open'] : true);

// --- Pagination ---
define('PER_PAGE', 15);

// --- Upload Settings ---
define('UPLOAD_DIR', BASE_PATH . 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// --- Session Settings ---
define('SESSION_LIFETIME', 3600); // 1 ชั่วโมง

// --- CSRF Token Lifetime ---
define('CSRF_LIFETIME', 1800); // 30 นาที

// --- สังกัด (Affiliation) ---
$affiliationsFile = BASE_PATH . 'config/affiliations.json';
if (file_exists($affiliationsFile)) {
    $affJson = file_get_contents($affiliationsFile);
    $affList = json_decode($affJson, true);
    define('AFFILIATIONS', is_array($affList) && !empty($affList) ? $affList : ['สังกัดที่ 1', 'สังกัดที่ 2']);
} else {
    define('AFFILIATIONS', [
        'สพป.ปัตตานี เขต 1',
        'สพป.ปัตตานี เขต 2',
        'สพป.ปัตตานี เขต 3',
        'สพม.ปัตตานี',
        'สำนักงานคณะกรรมการการอาชีวศึกษา',
        'สำนักงาน กศน.จังหวัดปัตตานี',
        'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน (สช.)',
        'สำนักงานการศึกษาพิเศษ',
        'องค์กรปกครองส่วนท้องถิ่น (อปท.)',
        'สถาบันอุดมศึกษา',
        'อื่นๆ',
    ]);
}

// --- อำเภอในจังหวัดปัตตานี ---
define('DISTRICTS', [
    'เมืองปัตตานี',
    'โคกโพธิ์',
    'หนองจิก',
    'ปะนาเระ',
    'มายอ',
    'ทุ่งยางแดง',
    'สายบุรี',
    'ไม้แก่น',
    'ยะหริ่ง',
    'ยะรัง',
    'แม่ลาน',
    'กะพ้อ',
]);

// --- ยุทธศาสตร์ ---
define('STRATEGIES', [
    1 => 'ยุทธศาสตร์ที่ 1: ยกระดับทักษะภาษาไทยและพหุภาษาสู่สากล',
    3 => 'ยุทธศาสตร์ที่ 3: สร้างโอกาส ความเสมอภาคและความเท่าเทียมทางการศึกษาคนทุกช่วงวัย',
    4 => 'ยุทธศาสตร์ที่ 4: ค้นหาความถนัด พัฒนาศักยภาพ และสร้างอัตลักษณ์ เพื่ออนาคตเด็กปัตตานี',
    5 => 'ยุทธศาสตร์ที่ 5: ปลูกฝังคุณธรรม จริยธรรม ความรักชาติ ศาสน์ กษัตริย์ และค่านิยมพหุวัฒนธรรม สู่การเป็นพลเมืองโลก',
    6 => 'ยุทธศาสตร์ที่ 6: ส่งเสริมการศึกษาเพื่ออาชีพ สร้างผู้ประกอบการรุ่นใหม่ วางเส้นทางอนาคตที่มั่นคง เพื่อเศรษฐกิจปัตตานียั่งยืน',
    8 => 'ยุทธศาสตร์ที่ 8: การบริหารจัดการที่เน้นการมีส่วนร่วม โปร่งใส ทันสมัย และมุ่งผลสัมฤทธิ์',
]);

// --- ITA Levels ---
define('ITA_LEVELS', [
    'AA' => 'AA (95-100 คะแนน)',
    'A'  => 'A (85-94.99 คะแนน)',
    'B+' => 'B+ (75-84.99 คะแนน)',
    'B'  => 'B (65-74.99 คะแนน)',
    'C'  => 'C (55-64.99 คะแนน)',
    'D'  => 'D (50-54.99 คะแนน)',
    'F'  => 'F (ต่ำกว่า 50 คะแนน)',
    'not_assessed' => 'ยังไม่ได้รับการประเมิน',
]);

// --- ระดับรางวัล ---
define('AWARD_LEVELS', [
    'school'        => 'ระดับสถานศึกษา',
    'district'      => 'ระดับอำเภอ',
    'provincial'    => 'ระดับจังหวัด',
    'regional'      => 'ระดับภาค',
    'national'      => 'ระดับชาติ',
    'international' => 'ระดับนานาชาติ',
]);

// --- รูปแบบการมีรายได้ ---
define('INCOME_TYPES', [
    'parttime'  => 'ทำงานพาร์ทไทม์',
    'online'    => 'ขายของออนไลน์',
    'internship'=> 'ฝึกงาน/สหกิจศึกษา',
    'business'  => 'ธุรกิจส่วนตัว',
    'freelance' => 'รับจ้างทั่วไป/Freelance',
    'other'     => 'อื่นๆ',
]);
