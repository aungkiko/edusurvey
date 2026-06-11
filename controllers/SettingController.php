<?php
/**
 * ===================================================
 * SettingController - จัดการการตั้งค่าระบบ
 * ===================================================
 */

class SettingController extends Controller
{
    private string $settingsFile;

    public function __construct()
    {
        $this->settingsFile = BASE_PATH . 'config/settings.json';
    }

    /**
     * หน้าแรก แสดงฟอร์มการตั้งค่า
     */
    public function index(): void
    {
        $settings = $this->getSettings();
        
        $data = [
            'title' => 'ตั้งค่าระบบ',
            'settings' => $settings,
            'affiliations_text' => implode("\n", AFFILIATIONS),
            'flash' => $this->getFlash(),
        ];
        
        $this->view('settings/index', $data, 'admin');
    }

    /**
     * บันทึกการตั้งค่า
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('admin/settings');
            return;
        }

        CSRF::check();

        $validator = new Validator($_POST);
        $validator->required('year_start', 'ปีเริ่มต้น')
                  ->integer('year_start', 'ปีเริ่มต้น')
                  ->required('year_end', 'ปีสิ้นสุด')
                  ->integer('year_end', 'ปีสิ้นสุด');

        if ($validator->fails()) {
            Session::flash('error', implode('<br>', $validator->errors()));
            $this->redirect('admin/settings');
            return;
        }

        $yearStart = Validator::sanitizeInt($_POST['year_start']);
        $yearEnd = Validator::sanitizeInt($_POST['year_end']);

        if ($yearEnd < $yearStart) {
            Session::flash('error', 'ปีสิ้นสุดต้องมากกว่าหรือเท่ากับปีเริ่มต้น');
            $this->redirect('admin/settings');
            return;
        }

        $settings = $this->getSettings();
        $settings['year_start'] = $yearStart;
        $settings['year_end'] = $yearEnd;

        $saveSettingsSuccess = file_put_contents($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // บันทึกสังกัด (Affiliations)
        $affiliationsText = $_POST['affiliations'] ?? '';
        $affiliationsArray = array_values(array_filter(array_map('trim', explode("\n", $affiliationsText))));
        
        $saveAffSuccess = true;
        if (!empty($affiliationsArray)) {
            $affiliationsFile = BASE_PATH . 'config/affiliations.json';
            $saveAffSuccess = file_put_contents($affiliationsFile, json_encode($affiliationsArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        if ($saveSettingsSuccess !== false && $saveAffSuccess !== false) {
            Session::flash('success', 'บันทึกการตั้งค่าเรียบร้อยแล้ว');
        } else {
            Session::flash('error', 'ไม่สามารถบันทึกไฟล์การตั้งค่าได้ โปรดตรวจสอบสิทธิ์การเขียนไฟล์');
        }

        $this->redirect('admin/settings');
    }

    /**
     * ดึงการตั้งค่าปัจจุบัน
     */
    private function getSettings(): array
    {
        if (file_exists($this->settingsFile)) {
            $json = file_get_contents($this->settingsFile);
            return json_decode($json, true) ?: [];
        }
        return [
            'year_start' => YEAR_START,
            'year_end' => YEAR_END
        ];
    }
}
