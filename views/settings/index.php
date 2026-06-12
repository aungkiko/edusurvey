<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-gear-fill me-2 text-primary"></i>
            ตั้งค่าระบบ
        </h2>
    </div>

    <form action="<?= BASE_URL ?>admin/settings/update" method="POST">
        <?= CSRF::field() ?>
        
        <div class="row g-4">
            <!-- ปีงบประมาณ -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 animate-fade-in h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="card-title mb-0">ตั้งค่าสถานะและปีงบประมาณ</h5>
                        <p class="text-muted small mt-1">กำหนดสถานะการเปิดรับคำตอบและช่วงปีงบประมาณ (พ.ศ.)</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="year_start" class="form-label fw-medium">ปีเริ่มต้น (พ.ศ.)</label>
                                <input type="number" class="form-control" id="year_start" name="year_start" 
                                       value="<?= htmlspecialchars($settings['year_start'] ?? YEAR_START) ?>" 
                                       min="2500" max="2600" required>
                                <div class="form-text">เช่น 2567</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="year_end" class="form-label fw-medium">ปีสิ้นสุด (พ.ศ.)</label>
                                <input type="number" class="form-control" id="year_end" name="year_end" 
                                       value="<?= htmlspecialchars($settings['year_end'] ?? YEAR_END) ?>" 
                                       min="2500" max="2600" required>
                                <div class="form-text">เช่น 2570</div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-start mb-0">
                                    <i class="bi bi-info-circle-fill me-2 fs-5 text-info"></i>
                                    <div>
                                        <strong>คำแนะนำ:</strong><br>
                                        หากต้องการให้มีให้เลือกเพียงปีเดียว ให้ตั้งค่า "ปีเริ่มต้น" และ "ปีสิ้นสุด" เป็นปีเดียวกัน
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4 pt-3 border-top">
                                <label class="form-label fw-medium d-block">สถานะการรับคำตอบแบบสำรวจ</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="survey_is_open" name="survey_is_open" value="1" <?= (isset($settings['survey_is_open']) && $settings['survey_is_open'] === false) ? '' : 'checked' ?> style="width: 3em; height: 1.5em; cursor: pointer;">
                                    <label class="form-check-label ms-3 mt-1 fw-bold text-<?= (isset($settings['survey_is_open']) && $settings['survey_is_open'] === false) ? 'danger' : 'success' ?>" for="survey_is_open" style="cursor: pointer;">
                                        <?= (isset($settings['survey_is_open']) && $settings['survey_is_open'] === false) ? 'ปิดรับคำตอบ' : 'เปิดรับคำตอบ' ?>
                                    </label>
                                </div>
                                <div class="form-text mt-2">หากปิดรับคำตอบ ผู้ใช้จะไม่สามารถส่งแบบสำรวจได้ และจะแสดงข้อความแจ้งเตือนที่หน้าแรก</div>
                            </div>
                            
                            <script>
                            document.getElementById('survey_is_open').addEventListener('change', function() {
                                var label = document.querySelector('label[for="survey_is_open"]');
                                if (this.checked) {
                                    label.textContent = 'เปิดรับคำตอบ';
                                    label.className = 'form-check-label ms-3 mt-1 fw-bold text-success';
                                } else {
                                    label.textContent = 'ปิดรับคำตอบ';
                                    label.className = 'form-check-label ms-3 mt-1 fw-bold text-danger';
                                }
                            });
                            </script>
                        </div>
                    </div>
                </div>
            </div>

            <!-- สังกัด -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 animate-fade-in h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="card-title mb-0">จัดการข้อมูล "สังกัด"</h5>
                        <p class="text-muted small mt-1">รายชื่อสังกัดทั้งหมดที่จะแสดงให้ผู้ตอบแบบสอบถามเลือก</p>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <label for="affiliations" class="form-label fw-medium">รายชื่อสังกัด (1 บรรทัดต่อ 1 สังกัด)</label>
                        <textarea class="form-control flex-grow-1" id="affiliations" name="affiliations" rows="7" required><?= htmlspecialchars($affiliations_text ?? '') ?></textarea>
                        <div class="form-text mt-2">
                            * พิมพ์ชื่อสังกัดบรรทัดละ 1 ชื่อ โดยสามารถแก้ไข เพิ่ม หรือลบ ได้ตามต้องการ
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
                            <i class="bi bi-save me-2"></i> บันทึกการตั้งค่าทั้งหมด
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
