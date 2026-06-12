<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">รายละเอียดแบบสอบถาม</h4>
        <div class="text-muted small">
            <i class="bi bi-building me-1"></i><?= htmlspecialchars($response['school_name_input']) ?>
            <span class="mx-2">|</span>
            <i class="bi bi-calendar3 me-1"></i>ปีงบประมาณ พ.ศ. <?= $response['budget_year'] ?>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>admin/surveys" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>กลับ
        </a>
        <a href="<?= BASE_URL ?>admin/surveys/edit?id=<?= $response['id'] ?>" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>แก้ไขสถานะ
        </a>
        <button class="btn btn-outline-success" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>พิมพ์
        </button>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- ข้อมูลพื้นฐาน -->
        <div class="glass-card mb-4 print-no-shadow">
            <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-info-circle me-2"></i>ข้อมูลพื้นฐาน</h5>
            
            <div class="mb-3">
                <small class="text-muted d-block">ชื่อสถานศึกษา</small>
                <div class="fw-medium"><?= htmlspecialchars($response['school_name_input']) ?></div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">สังกัด</small>
                <div><?= htmlspecialchars($response['affiliation_input']) ?></div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">อำเภอ</small>
                <div><?= htmlspecialchars($response['district_input']) ?></div>
            </div>
            <hr>
            <div class="mb-3">
                <small class="text-muted d-block">ผู้ตอบแบบสอบถาม</small>
                <div><?= htmlspecialchars($response['respondent_name'] ?: '-') ?></div>
                <div class="small text-muted"><?= htmlspecialchars($response['respondent_position'] ?: 'ไม่ได้ระบุตำแหน่ง') ?></div>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block">เบอร์โทรศัพท์</small>
                <div><?= htmlspecialchars($response['respondent_phone'] ?: '-') ?></div>
            </div>
            <hr>
            <div class="mb-3">
                <small class="text-muted d-block">สถานะ</small>
                <?php
                $statusBadge = match($response['status']) {
                    'submitted' => 'bg-primary', 'approved' => 'bg-success',
                    'rejected' => 'bg-danger', default => 'bg-secondary',
                };
                $statusLabel = match($response['status']) {
                    'submitted' => 'ส่งแล้ว', 'approved' => 'อนุมัติ',
                    'rejected' => 'ปฏิเสธ', default => 'ฉบับร่าง',
                };
                ?>
                <span class="badge <?= $statusBadge ?> fs-6"><?= $statusLabel ?></span>
            </div>
            <div class="mb-1">
                <small class="text-muted d-block">วันที่ส่งข้อมูล</small>
                <div class="small"><?= date('d/m/Y H:i:s', strtotime($response['submitted_at'] ?? $response['created_at'])) ?></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="glass-card print-no-shadow">
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-list-check me-2"></i>ผลการดำเนินงาน 12 ตัวชี้วัด</h5>
            
            <div class="accordion accordion-custom" id="surveyAccordion">
                
                <!-- Q1 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ1">
                            <span class="badge bg-gold text-dark me-2">ข้อ 1</span> ทักษะภาษา 3 ภาษา
                        </button>
                    </h2>
                    <div id="colQ1" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q1 = $response['q1']; if($q1): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-success fw-bold"><?= $q1['percentage'] ?>%</div>
                                    <small class="text-muted">ผลการดำเนินงาน</small>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">นักเรียนที่ได้รับการพัฒนา: <strong><?= number_format($q1['students_developed']) ?></strong> / <?= number_format($q1['total_students']) ?> คน</p>
                                    <p class="mb-1">ภาษาที่ส่งเสริม: <strong><?= implode(', ', json_decode($q1['languages_promoted'] ?? '[]', true) ?: ['-']) ?></strong></p>
                                    <?php if($q1['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><i class="bi bi-journal-text me-1"></i><?= nl2br(htmlspecialchars($q1['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ2">
                            <span class="badge bg-gold text-dark me-2">ข้อ 2</span> การได้รับการนิเทศ
                        </button>
                    </h2>
                    <div id="colQ2" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q2 = $response['q2']; if($q2): ?>
                            <div class="d-flex align-items-center mb-3">
                                <?php if($q2['is_supervised'] == 'yes'): ?>
                                    <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                                    <div><h5 class="mb-0 text-success">ได้รับการนิเทศ</h5><small class="text-muted"><?= $q2['supervision_count'] ?> ครั้ง/ปี</small></div>
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill text-danger fs-3 me-3"></i>
                                    <div><h5 class="mb-0 text-danger">ยังไม่ได้รับการนิเทศ</h5></div>
                                <?php endif; ?>
                            </div>
                            <?php if($q2['is_supervised'] == 'yes'): ?>
                            <p class="mb-1">เครือข่ายที่นิเทศ: <strong><?= htmlspecialchars($q2['network_name'] ?: '-') ?></strong></p>
                            <p class="mb-1">วันที่ล่าสุด: <strong><?= $q2['last_supervision_date'] ? date('d/m/Y', strtotime($q2['last_supervision_date'])) : '-' ?></strong></p>
                            <?php endif; ?>
                            <?php if($q2['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q2['notes'])) ?></div><?php endif; ?>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ3">
                            <span class="badge bg-gold text-dark me-2">ข้อ 3</span> พหุปัญญา
                        </button>
                    </h2>
                    <div id="colQ3" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q3 = $response['q3']; if($q3): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold"><?= $q3['percentage'] ?>%</div>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">นักเรียนที่ได้รับการพัฒนา: <strong><?= number_format($q3['students_developed']) ?></strong> / <?= number_format($q3['total_students']) ?> คน</p>
                                    <p class="mb-1">กิจกรรมที่ใช้: <strong><?= htmlspecialchars($q3['programs_used'] ?: '-') ?></strong></p>
                                    <?php if($q3['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q3['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ4">
                            <span class="badge bg-gold text-dark me-2">ข้อ 4</span> สุขภาวะและโภชนาการ
                        </button>
                    </h2>
                    <div id="colQ4" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q4 = $response['q4']; if($q4): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold"><?= $q4['percentage'] ?>%</div>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">นักเรียนที่ผ่านเกณฑ์: <strong><?= number_format($q4['students_passed'] ?? 0) ?></strong> / <?= number_format($q4['total_students'] ?? 0) ?> คน</p>
                                    <p class="mb-1">เครื่องมือประเมิน: <strong><?= htmlspecialchars($q4['assessment_tools'] ?: '-') ?></strong></p>
                                    <p class="mb-1">ปีที่ประเมิน: <strong><?= htmlspecialchars($q4['assessment_year'] ?: '-') ?></strong></p>
                                    <?php if($q4['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q4['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ5">
                            <span class="badge bg-gold text-dark me-2">ข้อ 5</span> กิจกรรมรักชาติและประวัติศาสตร์
                        </button>
                    </h2>
                    <div id="colQ5" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q5 = $response['q5']; if($q5): ?>
                            <div class="d-flex align-items-center mb-3">
                                <?php if($q5['has_activities'] == 'yes'): ?>
                                    <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                                    <div><h5 class="mb-0 text-success">มีการจัดกิจกรรม</h5><small class="text-muted"><?= $q5['activity_count'] ?? '-' ?> ครั้ง/ปี</small></div>
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill text-danger fs-3 me-3"></i>
                                    <div><h5 class="mb-0 text-danger">ยังไม่มีการจัดกิจกรรม</h5></div>
                                <?php endif; ?>
                            </div>
                            <?php if($q5['has_activities'] == 'yes'): ?>
                            <p class="mb-1">ชื่อกิจกรรม: <strong><?= htmlspecialchars($q5['activity_name'] ?: '-') ?></strong></p>
                            <p class="mb-1">ภาคีเครือข่าย: <strong><?= htmlspecialchars($q5['partner_network'] ?: '-') ?></strong></p>
                            <?php endif; ?>
                            <?php if($q5['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q5['notes'])) ?></div><?php endif; ?>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q6 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ6">
                            <span class="badge bg-gold text-dark me-2">ข้อ 6</span> รางวัลด้านคุณธรรม จริยธรรม
                        </button>
                    </h2>
                    <div id="colQ6" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q6 = $response['q6']; if($q6): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold"><?= $q6['percentage'] ?>%</div>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">นักเรียนที่ได้รับรางวัล: <strong><?= number_format($q6['students_awarded'] ?? 0) ?></strong> / <?= number_format($q6['total_students'] ?? 0) ?> คน</p>

                                    <?php if($q6['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q6['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q7 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ7">
                            <span class="badge bg-gold text-dark me-2">ข้อ 7</span> การแนะแนวและการโค้ช
                        </button>
                    </h2>
                    <div id="colQ7" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q7 = $response['q7']; if($q7): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold"><?= $q7['percentage'] ?>%</div>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">ผู้เรียนที่ได้รับแนะแนว: <strong><?= number_format($q7['students_guided'] ?? 0) ?></strong> / <?= number_format($q7['total_students'] ?? 0) ?> คน</p>
                                    <?php 
                                    $guidance_types = json_decode($q7['guidance_types'] ?? '[]', true) ?: []; 
                                    ?>
                                    <p class="mb-1">กิจกรรมแนะแนว: <strong><?= implode(', ', $guidance_types) ?: '-' ?></strong></p>
                                    <?php if($q7['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q7['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q8 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ8">
                            <span class="badge bg-gold text-dark me-2">ข้อ 8</span> เครือข่าย MOU อาชีวศึกษา
                        </button>
                    </h2>
                    <div id="colQ8" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q8 = $response['q8']; if($q8): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold">
                                        <?php if(($q8['increase_rate'] ?? 0) > 0): ?><i class="bi bi-arrow-up-right text-success fs-5"></i><?php endif; ?>
                                        <?= $q8['increase_rate'] ?? 0 ?>%
                                    </div>
                                    <small class="text-muted">อัตราการเพิ่มขึ้น</small>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">MOU ปีปัจจุบัน: <strong><?= number_format($q8['current_mou_count'] ?? 0) ?></strong> ฉบับ</p>
                                    <p class="mb-1">MOU ปีที่แล้ว: <strong><?= number_format($q8['previous_mou_count'] ?? 0) ?></strong> ฉบับ</p>
                                    <?php 
                                    $partner_types = json_decode($q8['mou_partner_types'] ?? '[]', true) ?: []; 
                                    ?>
                                    <p class="mb-1">ประเภทภาคีเครือข่าย: <strong><?= implode(', ', $partner_types) ?: '-' ?></strong></p>
                                    <?php if($q8['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q8['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q9 -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ9">
                            <span class="badge bg-gold text-dark me-2">ข้อ 9</span> รายได้ระหว่างเรียน
                        </button>
                    </h2>
                    <div id="colQ9" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q9 = $response['q9']; if($q9): ?>
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-6 text-primary fw-bold"><?= $q9['percentage'] ?>%</div>
                                    <small class="text-muted">รวมทุกระดับ</small>
                                    <div class="mt-2 small text-muted"><?= number_format($q9['students_with_income'] ?? 0) ?> / <?= number_format($q9['total_students'] ?? 0) ?> คน</div>
                                </div>
                                <div class="col-md-8">
                                    <?php if($q9['has_mattayom_pak']): ?>
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <span><i class="bi bi-mortarboard text-success me-2"></i>ม.ปลาย</span>
                                        <span>
                                            <strong><?= number_format($q9['mattayom_income'] ?? 0) ?></strong> / <?= number_format($q9['mattayom_total'] ?? 0) ?> คน
                                            <span class="badge bg-success ms-1"><?= $q9['mattayom_total'] > 0 ? number_format($q9['mattayom_income']/$q9['mattayom_total']*100, 2) : '0.00' ?>%</span>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($q9['has_vocational']): ?>
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                        <span><i class="bi bi-tools text-primary me-2"></i>อาชีวศึกษา</span>
                                        <span>
                                            <strong><?= number_format($q9['vocational_income'] ?? 0) ?></strong> / <?= number_format($q9['vocational_total'] ?? 0) ?> คน
                                            <span class="badge bg-primary ms-1"><?= $q9['vocational_total'] > 0 ? number_format($q9['vocational_income']/$q9['vocational_total']*100, 2) : '0.00' ?>%</span>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($q9['has_associate']): ?>
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <span><i class="bi bi-award me-2" style="color:#6f42c1;"></i>อนุปริญญา</span>
                                        <span>
                                            <strong><?= number_format($q9['associate_income'] ?? 0) ?></strong> / <?= number_format($q9['associate_total'] ?? 0) ?> คน
                                            <span class="badge ms-1" style="background:#6f42c1;"><?= $q9['associate_total'] > 0 ? number_format($q9['associate_income']/$q9['associate_total']*100, 2) : '0.00' ?>%</span>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!$q9['has_mattayom_pak'] && !$q9['has_vocational'] && !$q9['has_associate']): ?>
                                    <div class="text-muted fst-italic">ไม่มีระดับชั้นที่ระบุ</div>
                                    <?php endif; ?>
                                    <?php if($q9['income_type']): ?><p class="mb-1 mt-2">รูปแบบรายได้: <strong><?= htmlspecialchars($q9['income_type']) ?></strong></p><?php endif; ?>
                                    <?php if($q9['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q9['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q11 (display: ข้อ 10) - ยุทธศาสตร์ที่ 7: หุ้นส่วนความร่วมมือ -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ11">
                            <span class="badge bg-gold text-dark me-2">ข้อ 10</span> หุ้นส่วนความร่วมมือ (ภาครัฐ เอกชน ประชาสังคม)
                        </button>
                    </h2>
                    <div id="colQ11" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q11 = $response['q11']; if($q11): ?>
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center border-end">
                                    <div class="display-4 fw-bold" style="color:#6f42c1;"><?= number_format($q11['partnership_count']) ?></div>
                                    <small class="text-muted">แห่ง</small>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1">จำนวนหุ้นส่วนความร่วมมือทั้งหมด: <strong><?= number_format($q11['partnership_count']) ?> แห่ง</strong></p>
                                    <?php if($q11['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><i class="bi bi-journal-text me-1"></i><?= nl2br(htmlspecialchars($q11['notes'])) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Q12 (display: ข้อ 11) - ยุทธศาสตร์ที่ 7: นวัตกรรมที่ใช้จริง -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ12">
                            <span class="badge bg-gold text-dark me-2">ข้อ 11</span> จำนวนนวัตกรรมที่ใช้จริง (5 ด้าน)
                        </button>
                    </h2>
                    <div id="colQ12" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q12 = $response['q12']; if($q12): ?>
                            <?php if(($q12['has_innovations'] ?? 'yes') === 'no'): ?>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle-fill text-danger fs-3 me-3"></i>
                                <div><h5 class="mb-0 text-danger">ไม่มีนวัตกรรมที่ใช้จริง</h5></div>
                            </div>
                            <?php else: ?>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-success fs-3 me-3"></i>
                                <div><h5 class="mb-0 text-success">มีนวัตกรรมที่ใช้จริง</h5></div>
                            </div>
                            <?php 
                            $totalInnovations = ($q12['curriculum_count'] + $q12['teaching_count'] + $q12['media_count'] + $q12['assessment_count'] + $q12['management_count'] + ($q12['other_count'] ?? 0));
                            ?>
                            <div class="row mb-3">
                                <div class="col-md-3 text-center border-end">
                                    <div class="display-4 fw-bold" style="color:#6f42c1;"><?= number_format($totalInnovations) ?></div>
                                    <small class="text-muted">รวมทุกด้าน (เรื่อง)</small>
                                </div>
                                <div class="col-md-9">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><i class="bi bi-book me-2 text-primary"></i>ด้านหลักสูตร</span>
                                                <strong class="text-primary"><?= number_format($q12['curriculum_count']) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><i class="bi bi-people me-2 text-success"></i>การจัดการเรียนการสอน</span>
                                                <strong class="text-success"><?= number_format($q12['teaching_count']) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><i class="bi bi-display me-2 text-warning"></i>ด้านสื่อและแหล่งเรียนรู้</span>
                                                <strong class="text-warning"><?= number_format($q12['media_count']) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><i class="bi bi-clipboard-check me-2 text-danger"></i>ด้านการวัดและประเมินผล</span>
                                                <strong class="text-danger"><?= number_format($q12['assessment_count']) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                <span><i class="bi bi-building-gear me-2" style="color:#6f42c1;"></i>ด้านการบริหารจัดการสถานศึกษา</span>
                                                <strong style="color:#6f42c1;"><?= number_format($q12['management_count']) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span><i class="bi bi-plus-circle me-2 text-secondary"></i>ด้านอื่นๆ</span>
                                                <strong class="text-secondary"><?= number_format($q12['other_count'] ?? 0) ?> เรื่อง</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($q12['notes']): ?><div class="alert alert-light p-2 small mb-0"><i class="bi bi-journal-text me-1"></i><?= nl2br(htmlspecialchars($q12['notes'])) ?></div><?php endif; ?>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>

                        </div>
                    </div>
                </div>


                <!-- Q10 (ITA) -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#colQ10">
                            <span class="badge bg-gold text-dark me-2">ข้อ 12</span> การประเมิน ITA
                        </button>
                    </h2>
                    <div id="colQ10" class="accordion-collapse collapse" data-bs-parent="#surveyAccordion">
                        <div class="accordion-body">
                            <?php $q10 = $response['q10']; if($q10): ?>
                            <div class="d-flex align-items-center mb-3">
                                <?php 
                                $itaBadge = strpos($q10['ita_result'], 'A') !== false ? 'bg-success' : 'bg-warning text-dark';
                                ?>
                                <span class="badge <?= $itaBadge ?> fs-4 p-3 me-3"><?= htmlspecialchars($q10['ita_result'] ?: '-') ?></span>
                                <div>
                                    <h5 class="mb-0">คะแนน: <?= $q10['ita_score'] ?: '-' ?></h5>
                                    <small class="text-muted">ปีที่ประเมิน: <?= htmlspecialchars($q10['assessment_year'] ?: '-') ?></small>
                                </div>
                            </div>
                            <p class="mb-1">ประเด็นพัฒนา: <strong><?= htmlspecialchars($q10['improvement_areas'] ?: '-') ?></strong></p>
                            <?php if($q10['notes']): ?><div class="alert alert-light mt-2 p-2 small mb-0"><?= nl2br(htmlspecialchars($q10['notes'])) ?></div><?php endif; ?>
                            <?php else: ?><div class="text-muted fst-italic">ไม่มีข้อมูล</div><?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

