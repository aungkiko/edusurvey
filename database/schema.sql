-- ===================================================
-- EduSurvey Database Schema
-- ระบบแบบสอบถามตัวชี้วัดแผนพัฒนาการศึกษาจังหวัดปัตตานี
-- พ.ศ. 2569-2573
-- ===================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- ===================================================
-- ตาราง users (ผู้ดูแลระบบ)
-- ===================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `role` ENUM('admin','editor','viewer') NOT NULL DEFAULT 'viewer',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_username` (`username`),
    INDEX `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ตาราง schools (โรงเรียน/สถานศึกษา)
-- ===================================================
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `school_name` VARCHAR(255) NOT NULL,
    `affiliation` VARCHAR(150) NOT NULL COMMENT 'สังกัด',
    `district` VARCHAR(100) NOT NULL COMMENT 'อำเภอ',
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `director_name` VARCHAR(150) NULL COMMENT 'ชื่อผู้อำนวยการ',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_school_name` (`school_name`),
    INDEX `idx_affiliation` (`affiliation`),
    INDEX `idx_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ตาราง questions (คำถาม - จัดการได้โดย Admin)
-- ===================================================
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `question_number` INT NOT NULL COMMENT 'หมายเลขข้อ',
    `strategy_number` INT NOT NULL COMMENT 'หมายเลขยุทธศาสตร์',
    `strategy_name` VARCHAR(500) NOT NULL COMMENT 'ชื่อยุทธศาสตร์',
    `question_text` TEXT NOT NULL COMMENT 'เนื้อหาคำถาม',
    `question_type` VARCHAR(50) NOT NULL DEFAULT 'percentage' COMMENT 'ประเภท: percentage, yes_no, rating',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_strategy` (`strategy_number`),
    INDEX `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ตาราง survey_responses (การตอบแบบสอบถาม)
-- ===================================================
DROP TABLE IF EXISTS `survey_responses`;
CREATE TABLE `survey_responses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `school_id` INT NULL,
    `school_name_input` VARCHAR(255) NOT NULL COMMENT 'ชื่อโรงเรียนที่กรอก',
    `affiliation_input` VARCHAR(150) NOT NULL COMMENT 'สังกัด',
    `district_input` VARCHAR(100) NOT NULL COMMENT 'อำเภอ',
    `respondent_name` VARCHAR(150) NULL COMMENT 'ชื่อผู้ตอบ',
    `respondent_position` VARCHAR(150) NULL COMMENT 'ตำแหน่งผู้ตอบ',
    `respondent_phone` VARCHAR(20) NULL,
    `budget_year` INT NOT NULL COMMENT 'ปีงบประมาณ พ.ศ.',
    `status` ENUM('draft','submitted','approved','rejected') NOT NULL DEFAULT 'submitted',
    `submitted_at` DATETIME NULL,
    `approved_by` INT NULL,
    `approved_at` DATETIME NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(500) NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_school` (`school_id`),
    INDEX `idx_year` (`budget_year`),
    INDEX `idx_status` (`status`),
    INDEX `idx_year_school` (`budget_year`, `school_name_input`),
    FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 1: ร้อยละผู้เรียนได้รับการพัฒนาทักษะภาษา อย่างน้อย 3 ภาษา
-- ยุทธศาสตร์ที่ 1
-- ===================================================
DROP TABLE IF EXISTS `response_q1`;
CREATE TABLE `response_q1` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_developed` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เรียนที่ได้รับการพัฒนา',
    `total_students` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เรียนทั้งหมด',
    `percentage` DECIMAL(5,2) NULL COMMENT 'ร้อยละ (คำนวณอัตโนมัติ)',
    `languages_promoted` JSON NULL COMMENT 'ภาษาที่ส่งเสริม (array)',
    `notes` TEXT NULL COMMENT 'หมายเหตุ',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 2: ร้อยละของสถานศึกษาได้รับการนิเทศ
-- ยุทธศาสตร์ที่ 1
-- ===================================================
DROP TABLE IF EXISTS `response_q2`;
CREATE TABLE `response_q2` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `is_supervised` ENUM('yes','no') NOT NULL DEFAULT 'no' COMMENT 'ได้รับการนิเทศ',
    `supervision_count` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนครั้ง/ปี',
    `network_name` VARCHAR(255) NULL COMMENT 'ชื่อเครือข่ายที่นิเทศ',
    `last_supervision_date` DATE NULL COMMENT 'วันที่นิเทศล่าสุด',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 3: ร้อยละผู้เรียนได้รับการพัฒนาตามความถนัดด้านพหุปัญญา
-- ยุทธศาสตร์ที่ 3
-- ===================================================
DROP TABLE IF EXISTS `response_q3`;
CREATE TABLE `response_q3` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_developed` INT NOT NULL DEFAULT 0,
    `total_students` INT NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `programs_used` VARCHAR(500) NULL COMMENT 'กิจกรรม/โปรแกรมที่ใช้',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 4: ร้อยละผู้เรียนที่ผ่านการประเมินสุขภาวะที่ดี
-- ยุทธศาสตร์ที่ 4
-- ===================================================
DROP TABLE IF EXISTS `response_q4`;
CREATE TABLE `response_q4` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_passed` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เรียนที่ผ่านเกณฑ์',
    `total_students` INT NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `assessment_tools` VARCHAR(500) NULL COMMENT 'เครื่องมือประเมิน',
    `assessment_year` VARCHAR(10) NULL COMMENT 'ปีการศึกษาที่ประเมิน',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 5: ร้อยละสถานศึกษาที่มีส่วนร่วมกับภาคีเครือข่าย
-- ยุทธศาสตร์ที่ 5
-- ===================================================
DROP TABLE IF EXISTS `response_q5`;
CREATE TABLE `response_q5` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `has_activities` ENUM('yes','no') NOT NULL DEFAULT 'no' COMMENT 'มีการจัดกิจกรรม',
    `activity_count` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนกิจกรรม (ครั้ง/ปี)',
    `partner_network` VARCHAR(500) NULL COMMENT 'ชื่อภาคีเครือข่าย',
    `activity_name` VARCHAR(500) NULL COMMENT 'ชื่อกิจกรรม/โครงการ',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 6: ร้อยละนักเรียนที่ได้รับรางวัลด้านคุณธรรม จริยธรรม
-- ยุทธศาสตร์ที่ 5
-- ===================================================
DROP TABLE IF EXISTS `response_q6`;
CREATE TABLE `response_q6` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_awarded` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนนักเรียนที่ได้รับรางวัล',
    `total_students` INT NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `award_level` VARCHAR(50) NULL COMMENT 'ระดับรางวัล',
    `award_name` VARCHAR(500) NULL COMMENT 'ชื่อรางวัล',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 7: ร้อยละผู้เรียนที่ได้รับการแนะแนว
-- ยุทธศาสตร์ที่ 6
-- ===================================================
DROP TABLE IF EXISTS `response_q7`;
CREATE TABLE `response_q7` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_guided` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เรียนที่ได้รับแนะแนว',
    `total_students` INT NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `guidance_types` JSON NULL COMMENT 'ประเภทการแนะแนว (array)',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 8: อัตราการเพิ่มขึ้นของ MOU
-- ยุทธศาสตร์ที่ 6
-- ===================================================
DROP TABLE IF EXISTS `response_q8`;
CREATE TABLE `response_q8` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `current_mou_count` INT NOT NULL DEFAULT 0 COMMENT 'จำนวน MOU ปีปัจจุบัน',
    `previous_mou_count` INT NOT NULL DEFAULT 0 COMMENT 'จำนวน MOU ปีที่แล้ว',
    `increase_rate` DECIMAL(5,2) NULL COMMENT 'อัตราการเพิ่มขึ้น (%)',
    `mou_partner_types` JSON NULL COMMENT 'ประเภทหน่วยงาน (array)',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 9: ร้อยละผู้เรียนที่มีรายได้ระหว่างเรียน
-- ยุทธศาสตร์ที่ 6
-- ===================================================
DROP TABLE IF EXISTS `response_q9`;
CREATE TABLE `response_q9` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `students_with_income` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เรียนที่มีรายได้',
    `total_students` INT NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `income_type` VARCHAR(100) NULL COMMENT 'รูปแบบการมีรายได้',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 10: ผลการประเมิน ITA
-- ยุทธศาสตร์ที่ 8
-- ===================================================
DROP TABLE IF EXISTS `response_q10`;
CREATE TABLE `response_q10` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `ita_result` VARCHAR(20) NULL COMMENT 'ผลการประเมิน ITA',
    `ita_score` DECIMAL(5,2) NULL COMMENT 'คะแนน ITA (0-100)',
    `assessment_year` VARCHAR(10) NULL COMMENT 'ปีที่ประเมิน (พ.ศ.)',
    `improvement_areas` TEXT NULL COMMENT 'ประเด็นที่ต้องพัฒนา',
    `notes` TEXT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อมูลเริ่มต้น (Seed Data)
-- ===================================================

-- Admin User (password: admin123)
INSERT INTO `users` (`username`, `email`, `password_hash`, `full_name`, `role`, `is_active`) VALUES
('admin', 'admin@edusurvey.go.th', '$2y$12$xq3YbfJsjW9cWpaguqVS2.XyUREvWI4juMevif.BVYV5mhNN0jNku', 'ผู้ดูแลระบบ', 'admin', 1);

-- คำถามเริ่มต้น (10 ข้อ)
INSERT INTO `questions` (`question_number`, `strategy_number`, `strategy_name`, `question_text`, `question_type`, `sort_order`) VALUES
(1, 1, 'ยุทธศาสตร์ที่ 1: ยกระดับทักษะภาษาไทยและพหุภาษาสู่สากล', 
   'ร้อยละผู้เรียนได้รับการพัฒนาทักษะภาษา อย่างน้อย 3 ภาษา (มลายู, อังกฤษ, จีน, อาหรับ, ญี่ปุ่น, เกาหลี หรือภาษาอื่นๆ ตามความสนใจ)', 
   'percentage', 1),

(2, 1, 'ยุทธศาสตร์ที่ 1: ยกระดับทักษะภาษาไทยและพหุภาษาสู่สากล', 
   'ร้อยละของสถานศึกษาได้รับการนิเทศ ติดตามการอ่านการเขียนภาษาไทย โดยเครือข่ายของแต่ละสังกัดในจังหวัดปัตตานี', 
   'yes_no', 2),

(3, 3, 'ยุทธศาสตร์ที่ 3: สร้างโอกาส ความเสมอภาคและความเท่าเทียมทางการศึกษาคนทุกช่วงวัย', 
   'ร้อยละของผู้เรียนได้รับการพัฒนาตามความสามารถตามความถนัดด้านพหุปัญญา', 
   'percentage', 3),

(4, 4, 'ยุทธศาสตร์ที่ 4: ค้นหาความถนัด พัฒนาศักยภาพ และสร้างอัตลักษณ์ เพื่ออนาคตเด็กปัตตานี', 
   'ร้อยละของผู้เรียนที่ผ่านการประเมินสุขภาวะที่ดีตามเกณฑ์มาตรฐาน', 
   'percentage', 4),

(5, 5, 'ยุทธศาสตร์ที่ 5: ปลูกฝังคุณธรรม จริยธรรม ความรักชาติ ศาสน์ กษัตริย์ และค่านิยมพหุวัฒนธรรม สู่การเป็นพลเมืองโลก', 
   'ร้อยละของสถานศึกษาที่มีส่วนร่วมกับภาคีเครือข่ายในการจัดกิจกรรมส่งเสริมการรักชาติ ศาสน์ กษัตริย์', 
   'yes_no', 5),

(6, 5, 'ยุทธศาสตร์ที่ 5: ปลูกฝังคุณธรรม จริยธรรม ความรักชาติ ศาสน์ กษัตริย์ และค่านิยมพหุวัฒนธรรม สู่การเป็นพลเมืองโลก', 
   'ร้อยละของนักเรียนที่ได้รับรางวัลด้านคุณธรรม จริยธรรมและความเป็นพลเมือง', 
   'percentage', 6),

(7, 6, 'ยุทธศาสตร์ที่ 6: ส่งเสริมการศึกษาเพื่ออาชีพ สร้างผู้ประกอบการรุ่นใหม่ วางเส้นทางอนาคตที่มั่นคง เพื่อเศรษฐกิจปัตตานียั่งยืน', 
   'ร้อยละผู้เรียนที่ได้รับการแนะแนวการศึกษา/อาชีพ/ส่วนตัว/สังคม อย่างน้อยปีละ 1 ครั้ง', 
   'percentage', 7),

(8, 6, 'ยุทธศาสตร์ที่ 6: ส่งเสริมการศึกษาเพื่ออาชีพ สร้างผู้ประกอบการรุ่นใหม่ วางเส้นทางอนาคตที่มั่นคง เพื่อเศรษฐกิจปัตตานียั่งยืน', 
   'อัตราการเพิ่มขึ้นของเครือข่ายความร่วมมือ (MOU) ระหว่างสถานศึกษากับสถานประกอบการ ชุมชน และหน่วยงานภาคเอกชน', 
   'percentage', 8),

(9, 6, 'ยุทธศาสตร์ที่ 6: ส่งเสริมการศึกษาเพื่ออาชีพ สร้างผู้ประกอบการรุ่นใหม่ วางเส้นทางอนาคตที่มั่นคง เพื่อเศรษฐกิจปัตตานียั่งยืน', 
   'ร้อยละผู้เรียน (ม.ปลาย/อาชีวศึกษา/อนุปริญญา) ที่มีรายได้ระหว่างเรียน', 
   'percentage', 9),

(10, 8, 'ยุทธศาสตร์ที่ 8: การบริหารจัดการที่เน้นการมีส่วนร่วม โปร่งใส ทันสมัย และมุ่งผลสัมฤทธิ์', 
    'ร้อยละของหน่วยงานทางการศึกษาที่มีผลการประเมินคุณธรรมความโปร่งใส (ITA) ในระดับ AA', 
    'rating', 10);

SET FOREIGN_KEY_CHECKS = 1;

-- ===================================================
-- สร้าง password hash สำหรับ admin (ใช้ PHP)
-- รัน: php -r "echo password_hash('admin123', PASSWORD_BCRYPT, ['cost'=>12]);"
-- ===================================================

-- ===================================================
-- Migration: ยุทธศาสตร์ที่ 7 (ดูไฟล์ migration_strategy7.sql)
-- ===================================================
-- เพิ่ม display_number column ใน questions table
-- เพิ่ม question_number 11 (หุ้นส่วนความร่วมมือ) และ 12 (นวัตกรรม) สำหรับ strategy 7
-- สร้างตาราง response_q11 และ response_q12
