-- ===================================================
-- Migration: เพิ่มยุทธศาสตร์ที่ 7 - นวัตกรรมการศึกษา ปัตตานี
-- คำถาม ข้อ 11 (จำนวนหุ้นส่วนความร่วมมือ) และ ข้อ 12 (จำนวนนวัตกรรมที่ใช้จริง)
-- ===================================================

SET NAMES utf8mb4;

-- ===================================================
-- อัปเดตตาราง survey_responses
-- ===================================================
-- เพิ่มคอลัมน์พื้นที่นวัตกรรม (ยุทธศาสตร์ที่ 7)
ALTER TABLE `survey_responses` 
ADD COLUMN IF NOT EXISTS `is_innovation_area` ENUM('yes','no') NULL DEFAULT NULL COMMENT 'เป็นพื้นที่นวัตกรรมทางการศึกษาหรือไม่' AFTER `budget_year`;

-- ===================================================
-- เพิ่ม display_number column ใน questions table
-- (ใช้สำหรับแสดงหมายเลขข้อในหน้าจอ แยกจาก question_number ที่ใช้ใน DB)
-- ===================================================
ALTER TABLE `questions`
    ADD COLUMN `display_number` INT NOT NULL DEFAULT 0 COMMENT 'หมายเลขที่แสดงในหน้าจอ' AFTER `question_number`;

-- อัปเดต display_number สำหรับคำถามเดิมทั้ง 10 ข้อ
UPDATE `questions` SET `display_number` = `question_number`;

-- ===================================================
-- เพิ่มคำถามใหม่: ยุทธศาสตร์ที่ 7 (แทรกก่อน strategy 6)
-- ===================================================
INSERT INTO `questions` (`question_number`, `display_number`, `strategy_number`, `strategy_name`, `question_text`, `question_type`, `sort_order`) VALUES
(11, 10, 7, 'ยุทธศาสตร์ที่ 7: ยกระดับ ปรับโฉม และขยายพื้นที่นวัตกรรมการศึกษา จังหวัดปัตตานี',
 'จำนวนหุ้นส่วนความร่วมมือทั้งภาครัฐ เอกชน และประชาสังคม',
 'count', 6),

(12, 11, 7, 'ยุทธศาสตร์ที่ 7: ยกระดับ ปรับโฉม และขยายพื้นที่นวัตกรรมการศึกษา จังหวัดปัตตานี',
 'จำนวนนวัตกรรมที่ใช้จริง',
 'count', 7);

-- เลื่อน sort_order ของ strategy 6 และ 8 ให้อยู่หลัง strategy 7
UPDATE `questions` SET `sort_order` = `sort_order` + 2 WHERE `strategy_number` IN (6, 8);

-- ===================================================
-- ข้อ 11: จำนวนหุ้นส่วนความร่วมมือ (ยุทธศาสตร์ที่ 7)
-- ===================================================
CREATE TABLE IF NOT EXISTS `response_q11` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `partnership_count` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนหุ้นส่วนความร่วมมือ (แห่ง)',
    `notes` TEXT NULL COMMENT 'หมายเหตุ',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================
-- ข้อ 12: จำนวนนวัตกรรมที่ใช้จริง (ยุทธศาสตร์ที่ 7)
-- ===================================================
CREATE TABLE IF NOT EXISTS `response_q12` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `response_id` INT NOT NULL,
    `has_innovations` ENUM('yes','no') NOT NULL DEFAULT 'no' COMMENT 'มีการใช้นวัตกรรมหรือไม่',
    `curriculum_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมด้านหลักสูตร (เรื่อง)',
    `teaching_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมการจัดการเรียนการสอน (เรื่อง)',
    `media_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมด้านสื่อและแหล่งเรียนรู้ (เรื่อง)',
    `assessment_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมด้านการวัดและประเมินผล (เรื่อง)',
    `management_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมด้านการบริหารจัดการสถานศึกษา (เรื่อง)',
    `other_count` INT NOT NULL DEFAULT 0 COMMENT 'นวัตกรรมด้านอื่นๆ (เรื่อง)',
    `notes` TEXT NULL COMMENT 'หมายเหตุ',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uq_response` (`response_id`),
    FOREIGN KEY (`response_id`) REFERENCES `survey_responses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
