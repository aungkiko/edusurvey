<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="hero-title animate-fade-in">
                    <i class="bi bi-clipboard-data me-2"></i>
                    แบบสำรวจตามตัวชี้วัด
                </h1>
                <p class="hero-subtitle animate-fade-in-delay">
                    <?= APP_SUBTITLE ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Survey Form -->
<section class="survey-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Welcome / Instructions Card -->
                <div id="welcomeCard" class="glass-card mb-4 animate-slide-up">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h2 class="fw-bold" style="color: var(--primary-color);">คำชี้แจง</h2>
                    </div>
                    
                    <div class="instruction-text px-md-4" style="font-size: 1.1rem; line-height: 1.8;">
                        <p class="mb-4 text-indent" style="text-indent: 2rem;">
                            แบบสำรวจนี้จัดทำขึ้นเพื่อสำรวจและประเมินผลการดำเนินงานตามตัวชี้วัดแผนพัฒนาการศึกษาจังหวัดปัตตานี ฉบับทบทวน พ.ศ. 2569–2573 ของสำนักงานศึกษาธิการจังหวัดปัตตานี ข้อมูลและความคิดเห็นของท่านจะมีคุณค่าอย่างยิ่งในการวิเคราะห์สภาพการดำเนินงาน รับทราบปัญหาและอุปสรรค ตลอดจนนำผลที่ได้ไปใช้เป็นแนวทางในการพัฒนา ปรับปรุง และยกระดับคุณภาพการศึกษาในจังหวัดปัตตานีให้มีประสิทธิภาพสูงสุด
                        </p>
                        <p class="mb-4 text-indent" style="text-indent: 2rem;">
                            <i class="bi bi-shield-lock-fill text-success me-2"></i>ข้อมูลส่วนบุคคลของท่านจะถูกเก็บรักษาไว้เป็นความลับ และนำเสนอผลในภาพรวมเพื่อประโยชน์ทางวิชาการและการบริหารจัดการศึกษาเท่านั้น
                        </p>
                    </div>
                    
                    <div class="text-center mt-5">
                        <?php if ($survey_is_open ?? true): ?>
                        <button type="button" id="startSurveyBtn" class="btn btn-primary btn-lg px-5 py-3 shadow-sm" style="border-radius: 50px; font-weight: bold; font-size: 1.2rem;">
                            เริ่มตอบแบบสำรวจ <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                        <?php else: ?>
                        <div class="alert alert-danger d-inline-block px-5 py-3 shadow-sm rounded-pill fw-bold" style="font-size: 1.2rem; border-left: none;">
                            <i class="bi bi-x-circle-fill me-2"></i> ขณะนี้ระบบได้ปิดรับคำตอบแบบสำรวจแล้ว
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Container (Hidden initially) -->
                <div id="surveyContainer" style="display: none;">
                    <!-- Progress Bar -->
                <div class="glass-card mb-4 animate-slide-up">
                    <div class="progress-steps">
                        <div class="step-indicator active" data-step="0">
                            <span class="step-num">1</span>
                            <span class="step-label">ข้อมูลโรงเรียน</span>
                        </div>
                        <?php $stepNum = 2; foreach ($questions as $stratNum => $strategy): ?>
                        <div class="step-indicator" data-step="<?= $stepNum - 1 ?>">
                            <span class="step-num"><?= $stepNum ?></span>
                            <span class="step-label d-none d-md-inline">ยุทธ์ <?= $stratNum ?></span>
                            <span class="step-label d-md-none">ย.<?= $stratNum ?></span>
                        </div>
                        <?php $stepNum++; endforeach; ?>
                    </div>
                    <div class="progress mt-3" style="height:6px;">
                        <div class="progress-bar progress-bar-custom" id="progressBar" style="width:0%"></div>
                    </div>
                </div>

                <!-- Survey Form -->
                <form id="surveyForm" action="<?= BASE_URL ?>survey/submit" method="POST" novalidate>
                    <?= CSRF::field() ?>
                    
                    <!-- Step 0: ข้อมูลโรงเรียน -->
                    <div class="form-step active" id="step-0">
                        <div class="glass-card animate-slide-up">
                            <div class="card-header-custom">
                                <div class="card-header-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <h3 class="card-title-custom">ข้อมูลสถานศึกษา</h3>
                                    <p class="card-subtitle-custom">กรุณากรอกข้อมูลพื้นฐานของสถานศึกษา</p>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label-custom" for="school_name">
                                        ชื่อโรงเรียน/สถานศึกษา <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="school_name" name="school_name" 
                                           placeholder="กรอกชื่อโรงเรียน..." required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label-custom" for="budget_year">
                                        ปีงบประมาณ (พ.ศ.) <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-custom" id="budget_year" name="budget_year" required>
                                        <option value="">-- เลือกปี --</option>
                                        <?php foreach ($years as $y): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label-custom" for="affiliation">
                                        สังกัด <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-custom" id="affiliation" name="affiliation" required>
                                        <option value="">-- เลือกสังกัด --</option>
                                        <?php foreach ($affiliations as $aff): ?>
                                        <option value="<?= htmlspecialchars($aff) ?>"><?= htmlspecialchars($aff) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label-custom" for="district">
                                        อำเภอ <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-custom" id="district" name="district" required>
                                        <option value="">-- เลือกอำเภอ --</option>
                                        <?php foreach ($districts as $dist): ?>
                                        <option value="<?= htmlspecialchars($dist) ?>"><?= htmlspecialchars($dist) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label-custom" for="respondent_name">
                                        ชื่อผู้ตอบ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="respondent_name" name="respondent_name" placeholder="ชื่อ-นามสกุล" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom" for="respondent_position">
                                        ตำแหน่ง <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-custom" id="respondent_position" name="respondent_position" placeholder="ตำแหน่ง" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom" for="respondent_phone">
                                        เบอร์โทรศัพท์ <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control form-control-custom" id="respondent_phone" name="respondent_phone" placeholder="0xx-xxx-xxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Steps: Questions grouped by Strategy -->
                    <?php $stepIndex = 1; foreach ($questions as $stratNum => $strategy): ?>
                    <div class="form-step" id="step-<?= $stepIndex ?>">
                        <div class="glass-card animate-slide-up">
                            <div class="card-header-custom">
                                <div class="card-header-icon strategy-icon">
                                    <span><?= $stratNum ?></span>
                                </div>
                                <div>
                                    <h3 class="card-title-custom"><?= htmlspecialchars($strategy['strategy_name']) ?></h3>
                                </div>
                            </div>
                            
                            <?php if ($stratNum == 7): ?>
                            <div class="question-block bg-light border-success mb-4" style="border-left: 4px solid var(--success-color);">
                                <div class="question-body">
                                    <label class="form-label-custom fs-5 text-dark">สถานศึกษาของท่านเป็นสถานศึกษาในพื้นที่นวัตกรรมทางการศึกษาหรือไม่ <span class="text-danger">*</span></label>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_innovation_area" id="inno_yes" value="yes" required onchange="toggleInnovationQuestions(this.value)">
                                            <label class="form-check-label fw-bold text-success" for="inno_yes">ใช่</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_innovation_area" id="inno_no" value="no" required onchange="toggleInnovationQuestions(this.value)">
                                            <label class="form-check-label fw-bold text-danger" for="inno_no">ไม่ใช่</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="innovation_questions_container" style="display: none;">
                            <?php endif; ?>

                            <?php foreach ($strategy['questions'] as $q): ?>
                            <div class="question-block" id="question-<?= $q['question_number'] ?>">
                                <div class="question-header">
                                    <span class="question-number"><?= $q['display_number'] ?></span>
                                    <h5 class="question-text"><?= htmlspecialchars($q['question_text']) ?></h5>
                                </div>
                                
                                <div class="question-body">
                                    <?php 
                                    $qn = $q['question_number'];
                                    // แสดงฟิลด์ตามข้อคำถาม
                                    switch($qn):
                                        case 1: // ทักษะภาษา
                                    ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนที่ได้รับการพัฒนา (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q1_students_developed" data-calc="q1" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q1_total_students" data-calc="q1" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">ร้อยละ (คำนวณอัตโนมัติ)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q1" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">ภาษาที่ส่งเสริม (เลือกได้หลายข้อ)</label>
                                            <div class="checkbox-group">
                                                <?php foreach(['มลายู','อังกฤษ','จีน','อาหรับ','ญี่ปุ่น','เกาหลี','อื่นๆ'] as $lang): ?>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" name="q1_languages[]" value="<?= $lang ?>">
                                                    <span class="checkmark"></span>
                                                    <?= $lang ?>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q1_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 2: // การนิเทศ ?>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label-custom">ได้รับการนิเทศ <span class="text-danger">*</span></label>
                                            <select class="form-select form-control-custom" name="q2_supervised" id="q2_supervised" required onchange="toggleQ2Details(this.value)">
                                                <option value="">-- กรุณาเลือก --</option>
                                                <option value="no">ยังไม่ได้รับการนิเทศ</option>
                                                <option value="yes">ได้รับการนิเทศ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="q2_details" style="display: none; margin-top: 1rem;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label-custom">จำนวนครั้งที่ได้รับการนิเทศ (ครั้ง/ปี) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-custom q2-detail-input" name="q2_count" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">หน่วยงาน/เครือข่ายที่นิเทศ <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-custom q2-detail-input" name="q2_network" placeholder="ระบุชื่อเครือข่าย">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">วันที่นิเทศล่าสุด</label>
                                                <input type="date" class="form-control form-control-custom q2-detail-input" name="q2_date" id="q2_date">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label-custom">หมายเหตุ</label>
                                                <textarea class="form-control form-control-custom" name="q2_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    function toggleQ2Details(val) {
                                        var details = document.getElementById('q2_details');
                                        var inputs = document.querySelectorAll('.q2-detail-input');
                                        if (val === 'yes') {
                                            details.style.display = 'block';
                                            inputs.forEach(function(inp) { 
                                                // ไม่บังคับช่องวันที่
                                                if(inp.name !== 'q2_date') {
                                                    inp.setAttribute('required', 'required'); 
                                                }
                                            });
                                        } else {
                                            details.style.display = 'none';
                                            inputs.forEach(function(inp) {
                                                inp.removeAttribute('required');
                                                inp.value = '';
                                                inp.classList.remove('is-invalid');
                                            });
                                        }
                                    }
                                    </script>
                                    <?php break; case 3: // พหุปัญญา ?>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="alert alert-success border-0 bg-success bg-opacity-10 small mb-2">
                                                <strong>หมายเหตุ คำอธิบายองค์ประกอบตามความถนัดด้านพหุปัญญา:</strong><br>
                                                1. ความสามารถด้านภาษา (Verbal / Linguistic Intelligence)<br>
                                                2. ความสามารถด้านตรรกะและคณิตศาสตร์ (Logical / Mathematical Intelligence)<br>
                                                3. ความสามารถด้านมิติสัมพันธ์ (Spatial Intelligence)<br>
                                                4. ความสามารถด้านดนตรี / จังหวะ (Musical Intelligence)<br>
                                                5. ความสามารถด้านร่างกายและการเคลื่อนไหว (Bodily / Kinesthetic Intelligence)<br>
                                                6. ความสามารถด้านความสัมพันธ์กับผู้อื่น (Interpersonal Intelligence)<br>
                                                7. ความสามารถด้านการเข้าใจตนเอง (Intrapersonal Intelligence)<br>
                                                8. ความสามารถด้านการรอบรู้ธรรมชาติ (Natural Intelligence)
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนที่ได้รับการพัฒนา (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q3_students_developed" data-calc="q3" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q3_total_students" data-calc="q3" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">ร้อยละ (คำนวณอัตโนมัติ)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q3" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">กิจกรรม/โปรแกรมที่ใช้พัฒนาพหุปัญญา</label>
                                            <input type="text" class="form-control form-control-custom" name="q3_programs" placeholder="เช่น ดนตรี ศิลปะ กีฬา วิทย์...">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q3_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 4: // สุขภาวะ ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนที่ผ่านเกณฑ์ (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q4_students_passed" data-calc="q4" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q4_total_students" data-calc="q4" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">ร้อยละ (คำนวณอัตโนมัติ)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q4" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">เครื่องมือ/แบบประเมินที่ใช้</label>
                                            <input type="text" class="form-control form-control-custom" name="q4_tools" placeholder="เช่น BMI, SDQ, ร่างกาย-จิตใจ">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">ระยะเวลาประเมิน (ปีการศึกษา)</label>
                                            <input type="text" class="form-control form-control-custom" name="q4_assessment_year" placeholder="เช่น 2567">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q4_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 5: // กิจกรรมรักชาติ ?>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label-custom">มีการจัดกิจกรรม <span class="text-danger">*</span></label>
                                            <select class="form-select form-control-custom" name="q5_activities" id="q5_activities" required onchange="toggleQ5Details(this.value)">
                                                <option value="">-- กรุณาเลือก --</option>
                                                <option value="no">ยังไม่มีการจัดกิจกรรม</option>
                                                <option value="yes">มีการจัดกิจกรรม</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="q5_details" style="display: none; margin-top: 1rem;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label-custom">จำนวนกิจกรรม (ครั้ง/ปี) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-custom q5-detail-input" name="q5_count" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">ชื่อภาคีเครือข่ายที่ร่วม</label>
                                                <input type="text" class="form-control form-control-custom q5-detail-input" name="q5_partner" placeholder="ระบุชื่อหน่วยงาน/ชุมชน">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">ชื่อกิจกรรม/โครงการ</label>
                                                <input type="text" class="form-control form-control-custom q5-detail-input" name="q5_activity_name" placeholder="ระบุชื่อกิจกรรมสำคัญ">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label-custom">หมายเหตุ</label>
                                                <textarea class="form-control form-control-custom" name="q5_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                    function toggleQ5Details(val) {
                                        var details = document.getElementById('q5_details');
                                        var inputs = document.querySelectorAll('.q5-detail-input');
                                        if (val === 'yes') {
                                            details.style.display = 'block';
                                            inputs.forEach(function(inp) { 
                                                if(inp.name === 'q5_count') {
                                                    inp.setAttribute('required', 'required'); 
                                                }
                                            });
                                        } else {
                                            details.style.display = 'none';
                                            inputs.forEach(function(inp) {
                                                inp.removeAttribute('required');
                                                inp.value = '';
                                                inp.classList.remove('is-invalid');
                                            });
                                        }
                                    }
                                    </script>
                                    <?php break; case 6: // รางวัลคุณธรรม ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนนักเรียนที่ได้รับรางวัล (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q6_students_awarded" data-calc="q6" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนนักเรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q6_total_students" data-calc="q6" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">ร้อยละ (คำนวณอัตโนมัติ)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q6" readonly>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q6_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 7: // การแนะแนว ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนที่ได้รับแนะแนว (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q7_students_guided" data-calc="q7" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวนผู้เรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q7_total_students" data-calc="q7" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">ร้อยละ (คำนวณอัตโนมัติ)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q7" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">ประเภทการแนะแนวที่จัดให้ (เลือกได้หลายข้อ)</label>
                                            <div class="checkbox-group">
                                                <?php foreach(['การศึกษา','อาชีพ','ส่วนตัว','สังคม'] as $type): ?>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" name="q7_guidance_types[]" value="<?= $type ?>">
                                                    <span class="checkmark"></span>
                                                    <?= $type ?>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q7_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 8: // MOU ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวน MOU ปีปัจจุบัน (ฉบับ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q8_current" data-calc="q8" data-role="numerator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">จำนวน MOU ปีที่แล้ว (ฉบับ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-custom calc-input" name="q8_previous" data-calc="q8" data-role="denominator" min="0" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-custom">อัตราการเพิ่มขึ้น (%)</label>
                                            <input type="text" class="form-control form-control-custom calc-result bg-light" id="calc-q8" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">ประเภทหน่วยงานที่ทำ MOU (เลือกได้หลายข้อ)</label>
                                            <div class="checkbox-group">
                                                <?php foreach(['สถานประกอบการ','ชุมชน','ภาครัฐ','ภาคเอกชน','มหาวิทยาลัย'] as $type): ?>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" name="q8_partner_types[]" value="<?= $type ?>">
                                                    <span class="checkmark"></span>
                                                    <?= $type ?>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q8_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 9: // รายได้ระหว่างเรียน ?>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label-custom fw-semibold">โรงเรียนมีระดับชั้นใดบ้าง? <span class="text-danger">*</span> <small class="text-muted fw-normal">(เลือกได้หลายข้อ)</small></label>
                                            <div class="d-flex gap-4 mt-1 flex-wrap">
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" id="q9_has_mattayom" name="q9_has_mattayom" value="1" onchange="toggleQ9Level('mattayom', this.checked); uncheckQ9None();">
                                                    <span class="checkmark"></span>
                                                    ม.ปลาย
                                                </label>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" id="q9_has_vocational" name="q9_has_vocational" value="1" onchange="toggleQ9Level('vocational', this.checked); uncheckQ9None();">
                                                    <span class="checkmark"></span>
                                                    อาชีวศึกษา
                                                </label>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" id="q9_has_associate" name="q9_has_associate" value="1" onchange="toggleQ9Level('associate', this.checked); uncheckQ9None();">
                                                    <span class="checkmark"></span>
                                                    อนุปริญญา
                                                </label>
                                                <label class="checkbox-custom">
                                                    <input type="checkbox" id="q9_has_none" name="q9_has_none" value="1" onchange="toggleQ9None(this.checked)">
                                                    <span class="checkmark"></span>
                                                    ไม่มี ม.ปลาย/อาชีวศึกษา/อนุปริญญา
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ม.ปลาย -->
                                    <div id="q9_section_mattayom" style="display:none; margin-top:1rem;">
                                        <div class="p-3 border rounded-3" style="background: rgba(40,167,69,0.05); border-color: rgba(40,167,69,0.3) !important;">
                                            <h6 class="fw-semibold text-success mb-3"><i class="bi bi-mortarboard me-2"></i>ระดับ ม.ปลาย</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนที่มีรายได้ (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-income-input q9-mattayom-input" name="q9_mattayom_income" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-total-input q9-mattayom-input" name="q9_mattayom_total" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">ร้อยละ</label>
                                                    <input type="text" class="form-control form-control-custom bg-light" id="calc-q9-mattayom" readonly placeholder="0.00%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- อาชีวศึกษา -->
                                    <div id="q9_section_vocational" style="display:none; margin-top:1rem;">
                                        <div class="p-3 border rounded-3" style="background: rgba(0,123,255,0.05); border-color: rgba(0,123,255,0.3) !important;">
                                            <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-tools me-2"></i>ระดับอาชีวศึกษา</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนที่มีรายได้ (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-income-input q9-vocational-input" name="q9_vocational_income" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-total-input q9-vocational-input" name="q9_vocational_total" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">ร้อยละ</label>
                                                    <input type="text" class="form-control form-control-custom bg-light" id="calc-q9-vocational" readonly placeholder="0.00%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- อนุปริญญา -->
                                    <div id="q9_section_associate" style="display:none; margin-top:1rem;">
                                        <div class="p-3 border rounded-3" style="background: rgba(111,66,193,0.05); border-color: rgba(111,66,193,0.3) !important;">
                                            <h6 class="fw-semibold mb-3" style="color:#6f42c1;"><i class="bi bi-award me-2"></i>ระดับอนุปริญญา</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนที่มีรายได้ (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-income-input q9-associate-input" name="q9_associate_income" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">จำนวนนักเรียนทั้งหมด (คน) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-custom q9-total-input q9-associate-input" name="q9_associate_total" min="0" placeholder="0" oninput="calcQ9Total()">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">ร้อยละ</label>
                                                    <input type="text" class="form-control form-control-custom bg-light" id="calc-q9-associate" readonly placeholder="0.00%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ผลรวม + หมายเหตุ -->
                                    <div id="q9_rest_container">
                                        <div class="row g-3 mt-1">
                                            <div class="col-md-4">
                                                <label class="form-label-custom text-muted">รวม: ผู้เรียนที่มีรายได้ทุกระดับ (คน)</label>
                                                <input type="number" class="form-control form-control-custom bg-light" id="q9_sum_income" name="q9_students" min="0" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-custom text-muted">รวม: ผู้เรียนทั้งหมดทุกระดับ (คน)</label>
                                                <input type="number" class="form-control form-control-custom bg-light" id="q9_sum_total" name="q9_total_students" min="0" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label-custom text-muted">ร้อยละรวม (คำนวณอัตโนมัติ)</label>
                                                <input type="text" class="form-control form-control-custom bg-light" id="calc-q9" readonly placeholder="0.00%">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">รูปแบบการมีรายได้</label>
                                                <select class="form-select form-control-custom" name="q9_income_type">
                                                    <option value="">-- กรุณาเลือก --</option>
                                                    <?php foreach (INCOME_TYPES as $key => $label): ?>
                                                    <option value="<?= $key ?>"><?= $label ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label-custom">หมายเหตุ</label>
                                                <textarea class="form-control form-control-custom" name="q9_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                    function toggleQ9None(checked) {
                                        var container = document.getElementById('q9_rest_container');
                                        var inputs = container.querySelectorAll('input, select, textarea');
                                        if (checked) {
                                            // Uncheck other options
                                            ['mattayom', 'vocational', 'associate'].forEach(function(level) {
                                                var cb = document.getElementById('q9_has_' + level);
                                                if (cb && cb.checked) {
                                                    cb.checked = false;
                                                    toggleQ9Level(level, false);
                                                }
                                            });
                                            // Hide the rest container
                                            container.style.display = 'none';
                                            // Clear values
                                            inputs.forEach(function(inp) {
                                                inp.value = '';
                                                inp.classList.remove('is-invalid');
                                            });
                                        } else {
                                            container.style.display = 'block';
                                        }
                                    }
                                    function uncheckQ9None() {
                                        var noneCb = document.getElementById('q9_has_none');
                                        if (noneCb && noneCb.checked) {
                                            noneCb.checked = false;
                                            toggleQ9None(false);
                                        }
                                    }
                                    function toggleQ9Level(level, show) {
                                        var section = document.getElementById('q9_section_' + level);
                                        var inputs = document.querySelectorAll('.q9-' + level + '-input');
                                        if (show) {
                                            section.style.display = 'block';
                                            inputs.forEach(function(inp) { inp.setAttribute('required', 'required'); });
                                        } else {
                                            section.style.display = 'none';
                                            inputs.forEach(function(inp) {
                                                inp.removeAttribute('required');
                                                inp.value = '';
                                                inp.classList.remove('is-invalid');
                                            });
                                            calcQ9Total();
                                        }
                                        calcQ9Total();
                                    }
                                    function calcQ9PercLevel(incomeId, totalId, resultId) {
                                        var inc = parseFloat(document.querySelector('[name="' + incomeId + '"]')?.value) || 0;
                                        var tot = parseFloat(document.querySelector('[name="' + totalId + '"]')?.value) || 0;
                                        var el = document.getElementById(resultId);
                                        if (el) el.value = (tot > 0) ? (inc / tot * 100).toFixed(2) + '%' : '0.00%';
                                    }
                                    function calcQ9Total() {
                                        var sumInc = 0, sumTot = 0;
                                        [['mattayom','q9_section_mattayom'],['vocational','q9_section_vocational'],['associate','q9_section_associate']].forEach(function(pair) {
                                            var sec = document.getElementById(pair[1]);
                                            if (sec && sec.style.display !== 'none') {
                                                var inc = parseFloat(document.querySelector('[name="q9_' + pair[0] + '_income"]')?.value) || 0;
                                                var tot = parseFloat(document.querySelector('[name="q9_' + pair[0] + '_total"]')?.value) || 0;
                                                sumInc += inc;
                                                sumTot += tot;
                                                calcQ9PercLevel('q9_' + pair[0] + '_income', 'q9_' + pair[0] + '_total', 'calc-q9-' + pair[0]);
                                            }
                                        });
                                        document.getElementById('q9_sum_income').value = sumInc;
                                        document.getElementById('q9_sum_total').value = sumTot;
                                        var resultEl = document.getElementById('calc-q9');
                                        if (resultEl) resultEl.value = (sumTot > 0) ? (sumInc / sumTot * 100).toFixed(2) + '%' : '0.00%';
                                    }
                                    </script>
                                    <?php break; case 10: // ITA ?>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">ผลการประเมิน ITA ปีล่าสุด <span class="text-danger">*</span></label>
                                            <select class="form-select form-control-custom" name="q10_result" required>
                                                <option value="">-- กรุณาเลือก --</option>
                                                <?php foreach (ITA_LEVELS as $key => $label): ?>
                                                <option value="<?= $key ?>"><?= $label ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">คะแนน ITA (ถ้ามี)</label>
                                            <input type="number" class="form-control form-control-custom" name="q10_score" min="0" max="100" step="0.01" placeholder="0-100">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">ปีที่ประเมิน (พ.ศ.) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-custom" name="q10_year" placeholder="เช่น 2567" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label-custom">ประเด็นที่ต้องพัฒนา (ถ้ามี)</label>
                                            <input type="text" class="form-control form-control-custom" name="q10_improvement" placeholder="ระบุประเด็น">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q10_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 11: // หุ้นส่วนความร่วมมือ (Strategy 7) ?>
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label class="form-label-custom">จำนวนหุ้นส่วนความร่วมมือ (แห่ง) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-custom" name="q11_partnership_count" min="0" placeholder="0" required>
                                                <span class="input-group-text">แห่ง</span>
                                            </div>
                                            <small class="text-muted mt-1 d-block">นับรวมทั้งภาครัฐ เอกชน และประชาสังคม</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q11_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <?php break; case 12: // นวัตกรรมที่ใช้จริง (Strategy 7) ?>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label-custom">มีนวัตกรรมที่ใช้จริงในสถานศึกษาหรือไม่ <span class="text-danger">*</span></label>
                                            <select class="form-select form-control-custom" name="q12_has_innovations" id="q12_has_innovations" required
                                                onchange="toggleQ12Details(this.value)">
                                                <option value="">กรุณาเลือก</option>
                                                <option value="no">ไม่มีนวัตกรรมที่ใช้จริง</option>
                                                <option value="yes">มีนวัตกรรมที่ใช้จริง</option>
                                            </select>
                                        </div>
                                        <div id="q12_details" style="display:none;" class="col-12">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 small mb-0">
                                                        <i class="bi bi-lightbulb me-1"></i>
                                                        <strong>กรุณาระบุจำนวนนวัตกรรมแต่ละด้านที่ใช้จริงในสถานศึกษาในปีงบประมาณนี้</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมด้านหลักสูตร (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_curriculum" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมการจัดการเรียนการสอน (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_teaching" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมด้านสื่อและแหล่งเรียนรู้ (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_media" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมด้านการวัดและประเมินผล (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_assessment" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมด้านการบริหารจัดการสถานศึกษา (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_management" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label-custom">นวัตกรรมด้านอื่นๆ (เรื่อง)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-custom q12-detail-input" name="q12_other" min="0" placeholder="0" value="0">
                                                        <span class="input-group-text">เรื่อง</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label-custom">หมายเหตุ</label>
                                            <textarea class="form-control form-control-custom" name="q12_notes" rows="2" placeholder="ระบุรายละเอียดเพิ่มเติม..."></textarea>
                                        </div>
                                    </div>
                                    <script>
                                    function toggleQ12Details(val) {
                                        var details = document.getElementById('q12_details');
                                        var inputs = document.querySelectorAll('.q12-detail-input');
                                        if (val === 'yes') {
                                            details.style.display = 'block';
                                        } else {
                                            details.style.display = 'none';
                                            inputs.forEach(function(inp) {
                                                inp.value = '0';
                                                inp.classList.remove('is-invalid');
                                            });
                                        }
                                    }
                                    </script>
                                    <?php break; endswitch; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <?php if ($stratNum == 7): ?>
                            </div> <!-- End innovation_questions_container -->
                            <script>
                            function toggleInnovationQuestions(val) {
                                const container = document.getElementById('innovation_questions_container');
                                const inputs = container.querySelectorAll('input, select, textarea');
                                
                                if (val === 'yes') {
                                    container.style.display = 'block';
                                    // Re-enable required attributes if they had them (we'll handle validation in survey-form.js)
                                } else {
                                    container.style.display = 'none';
                                    // Clear values when hiding
                                    inputs.forEach(inp => {
                                        if (inp.type === 'radio' || inp.type === 'checkbox') {
                                            inp.checked = false;
                                        } else {
                                            inp.value = '';
                                        }
                                        inp.classList.remove('is-invalid');
                                    });
                                }
                            }
                            </script>
                            <?php endif; ?>

                        </div>
                    </div>
                    <?php $stepIndex++; endforeach; ?>

                    <!-- Navigation Buttons -->
                    <div class="form-navigation mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-lg" id="prevBtn" style="display:none;">
                            <i class="bi bi-arrow-left me-1"></i> ย้อนกลับ
                        </button>
                        <div class="ms-auto d-flex gap-2">
                            <button type="button" class="btn btn-gold btn-lg" id="nextBtn">
                                ถัดไป <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display:none;">
                                <i class="bi bi-check-circle me-1"></i> ส่งแบบสอบถาม
                            </button>
                        </div>
                    </div>
                    </div>
                </form>
                </div> <!-- End surveyContainer -->
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('startSurveyBtn');
    const welcomeCard = document.getElementById('welcomeCard');
    const surveyContainer = document.getElementById('surveyContainer');

    if(startBtn) {
        startBtn.addEventListener('click', function() {
            welcomeCard.style.display = 'none';
            surveyContainer.style.display = 'block';
            window.scrollTo({ top: surveyContainer.offsetTop - 100, behavior: 'smooth' });
        });
    }
});
</script>

<script src="<?= BASE_URL ?>assets/js/survey-form.js"></script>

