<?php
$currentYearCount = $totalThisYear;
$totalSchoolsYear = $yearStats['total_schools_year'] ?? 0;
?>

<!-- Year Selector Bar -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="fw-medium text-muted mb-0 me-1"><i class="bi bi-calendar3 me-1"></i>แสดงข้อมูลปี:</label>
        <select name="year" class="form-select form-select-sm form-control-custom w-auto" onchange="this.form.submit()" style="min-width:150px;">
            <option value="0" <?= $selectedYear == 0 ? 'selected' : '' ?>>รวมทุกปี (<?= $stats['total_responses'] ?> แห่ง)</option>
            <?php foreach (array_reverse($years) as $y): ?>
            <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                พ.ศ. <?= $y ?><?= ($stats['by_year_map'][$y] ?? 0) > 0 ? ' (' . ($stats['by_year_map'][$y]) . ' แห่ง)' : '' ?>
            </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<!-- Dashboard Stats Cards (กรองตามปีที่เลือก) -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card gradient-green animate-slide-up">
            <div class="stat-icon"><i class="bi bi-clipboard-data"></i></div>
            <div class="stat-content">
                <span class="stat-number counter" data-target="<?= $currentYearCount ?>">0</span>
                <span class="stat-label"><?= $selectedYear > 0 ? "แบบสอบถามปี $selectedYear" : "แบบสอบถามรวม" ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card gradient-gold animate-slide-up" style="animation-delay:0.1s">
            <div class="stat-icon"><i class="bi bi-clipboard-data-fill"></i></div>
            <div class="stat-content">
                <span class="stat-number counter" data-target="<?= $stats['total_responses'] ?? 0 ?>">0</span>
                <span class="stat-label">แบบสอบถามทั้งหมดในระบบ</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card gradient-blue animate-slide-up" style="animation-delay:0.2s">
            <div class="stat-icon"><i class="bi bi-building"></i></div>
            <div class="stat-content">
                <span class="stat-number counter" data-target="<?= $totalSchoolsYear ?>">0</span>
                <span class="stat-label"><?= $selectedYear > 0 ? "สถานศึกษาปี $selectedYear" : "สถานศึกษารวม (ไม่ซ้ำ)" ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card gradient-purple animate-slide-up" style="animation-delay:0.3s">
            <div class="stat-icon"><i class="bi bi-list-check"></i></div>
            <div class="stat-content">
                <span class="stat-number">12</span>
                <span class="stat-label">ตัวชี้วัด</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row (กรองตามปีที่เลือก) -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2"></i>จำนวนแบบสอบถามเปรียบเทียบรายปี</h5>
                <span class="badge bg-success"><?= $selectedYear > 0 ? "ปี $selectedYear ที่เลือก" : "รวมทุกปี" ?></span>
            </div>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card h-100">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-pie-chart me-2"></i>สัดส่วนตามอำเภอ
                <small class="text-muted fw-normal fs-6"><?= $selectedYear > 0 ? "ปี $selectedYear" : "รวมทุกปี" ?></small>
            </h5>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="districtChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Indicators Summary for selected year -->
<?php if ($currentYearCount > 0): ?>
<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-graph-up me-2"></i>สรุปตัวชี้วัด
            <span class="badge bg-success ms-1"><?= $selectedYear > 0 ? "ปี พ.ศ. $selectedYear" : "รวมทุกปี" ?></span>
        </h5>
        <?php if ($selectedYear > 0): ?>
        <a href="<?= BASE_URL ?>admin/reports/yearly?year=<?= $selectedYear ?>" class="btn btn-sm btn-outline-success">
            <i class="bi bi-arrow-right me-1"></i>รายงานเต็ม
        </a>
        <?php else: ?>
        <a href="<?= BASE_URL ?>admin/reports/compare" class="btn btn-sm btn-outline-success">
            <i class="bi bi-arrow-right me-1"></i>เปรียบเทียบ
        </a>
        <?php endif; ?>
    </div>
    <div class="row g-3">
        <!-- Q1 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary" style="font-size:0.65rem;">ยุทธ์ 1</span>
                        <span class="badge bg-light text-dark border">ข้อ 1</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละผู้เรียนได้รับการพัฒนาทักษะภาษา อย่างน้อย 3 ภาษา</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-success" style="line-height:1;"><?= number_format($yearlySummary['q1'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่ได้รับการพัฒนารวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q1'][0]['total_developed'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q1'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q2 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary" style="font-size:0.65rem;">ยุทธ์ 1</span>
                        <span class="badge bg-light text-dark border">ข้อ 2</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละของสถานศึกษาได้รับการนิเทศ</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <?php 
                            $q2 = $yearlySummary['q2'][0] ?? [];
                            $pct2 = ($q2['total_count'] > 0) ? ($q2['supervised_count'] / $q2['total_count'] * 100) : 0;
                            ?>
                            <div class="fs-2 fw-bold text-primary" style="line-height:1;"><?= number_format($pct2, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">สถานศึกษาที่ได้รับการนิเทศ:</span>
                            <strong class="text-dark fs-6"><?= number_format($q2['supervised_count'] ?? 0) ?></strong> / <?= number_format($q2['total_count'] ?? 0) ?> แห่ง<br>
                            เฉลี่ย <?= number_format($q2['avg_supervision_count'] ?? 0, 1) ?> ครั้ง/ปี
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q3 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary" style="font-size:0.65rem;">ยุทธ์ 3</span>
                        <span class="badge bg-light text-dark border">ข้อ 3</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละผู้เรียนได้รับการพัฒนาตามความถนัดด้านพหุปัญญา</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-primary" style="line-height:1;"><?= number_format($yearlySummary['q3'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่ได้รับการพัฒนารวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q3'][0]['total_developed'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q3'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q4 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info text-dark" style="font-size:0.65rem;">ยุทธ์ 4</span>
                        <span class="badge bg-light text-dark border">ข้อ 4</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละผู้เรียนที่ผ่านการประเมินสุขภาวะที่ดี</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-danger" style="line-height:1;"><?= number_format($yearlySummary['q4'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่ผ่านประเมินรวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q4'][0]['total_passed'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q4'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q5 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-warning text-dark" style="font-size:0.65rem;">ยุทธ์ 5</span>
                        <span class="badge bg-light text-dark border">ข้อ 5</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละสถานศึกษาที่มีส่วนร่วมกับภาคีเครือข่ายส่งเสริมรักชาติฯ</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <?php 
                            $q5 = $yearlySummary['q5'][0] ?? [];
                            $pct5 = ($q5['total_count'] > 0) ? ($q5['activity_count'] / $q5['total_count'] * 100) : 0;
                            ?>
                            <div class="fs-2 fw-bold text-danger" style="line-height:1;"><?= number_format($pct5, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">สถานศึกษาที่จัดกิจกรรมร่วม:</span>
                            <strong class="text-dark fs-6"><?= number_format($q5['activity_count'] ?? 0) ?></strong> / <?= number_format($q5['total_count'] ?? 0) ?> แห่ง<br>
                            เฉลี่ย <?= number_format($q5['avg_activities'] ?? 0, 1) ?> กิจกรรม/ปี
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q6 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-warning text-dark" style="font-size:0.65rem;">ยุทธ์ 5</span>
                        <span class="badge bg-light text-dark border">ข้อ 6</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละนักเรียนที่ได้รับรางวัลด้านคุณธรรม จริยธรรม</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-warning" style="line-height:1;"><?= number_format($yearlySummary['q6'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่ได้รับรางวัลรวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q6'][0]['total_awarded'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q6'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q7 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>
                        <span class="badge bg-light text-dark border">ข้อ 7</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละผู้เรียนที่ได้รับการแนะแนว</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-info" style="line-height:1;"><?= number_format($yearlySummary['q7'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่ได้รับการแนะแนวรวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q7'][0]['total_guided'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q7'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q8 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>
                        <span class="badge bg-light text-dark border">ข้อ 8</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">อัตราการเพิ่มขึ้นของเครือข่ายความร่วมมือ (MOU)</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-warning" style="line-height:1;"><?= number_format($yearlySummary['q8'][0]['avg_rate'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">จำนวน MOU รวมทั้งหมด:</span>
                            ปีปัจจุบัน <strong class="text-dark"><?= number_format($yearlySummary['q8'][0]['total_current'] ?? 0) ?></strong> ฉบับ<br>
                            ปีที่แล้ว <?= number_format($yearlySummary['q8'][0]['total_previous'] ?? 0) ?> ฉบับ
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q9 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>
                        <span class="badge bg-light text-dark border">ข้อ 9</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละผู้เรียนที่มีรายได้ระหว่างเรียน</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold text-success" style="line-height:1;"><?= number_format($yearlySummary['q9'][0]['avg_percentage'] ?? 0, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">นักเรียนที่มีรายได้รวม:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q9'][0]['total_income'] ?? 0) ?></strong> / <?= number_format($yearlySummary['q9'][0]['total_all'] ?? 0) ?> คน
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q10 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-dark" style="font-size:0.65rem;">ยุทธ์ 8</span>
                        <span class="badge bg-light text-dark border">ข้อ 10</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">ร้อยละของหน่วยงานที่มีผลประเมิน ITA ระดับ AA</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <?php
                            $itaData = $yearlySummary['q10'] ?? [];
                            $aaCount = 0; $totalIta = 0;
                            foreach($itaData as $ita) {
                                $totalIta += $ita['count'];
                                if(strpos($ita['ita_result'], 'AA') !== false) $aaCount += $ita['count'];
                            }
                            $pct10 = ($totalIta > 0) ? ($aaCount / $totalIta * 100) : 0;
                            ?>
                            <div class="fs-2 fw-bold text-primary" style="line-height:1;"><?= number_format($pct10, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">หน่วยงานที่ได้ระดับ AA ขึ้นไป:</span>
                            <strong class="text-dark fs-6"><?= $aaCount ?></strong> / <?= $totalIta ?> แห่ง
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q11 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge" style="background:#6f42c1;font-size:0.65rem;">ยุทธ์ 7</span>
                        <span class="badge bg-light text-dark border">ข้อ 11</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">จำนวนหุ้นส่วนความร่วมมือ (ภาครัฐ-เอกชน-ประชาสังคม)</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <div class="fs-2 fw-bold" style="color:#6f42c1; line-height:1;"><?= number_format($yearlySummary['q11'][0]['avg_count'] ?? 0, 1) ?></div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">รวมหุ้นส่วนความร่วมมือทุกสถานศึกษา:</span>
                            <strong class="text-dark fs-6"><?= number_format($yearlySummary['q11'][0]['total_count'] ?? 0) ?></strong> แห่ง
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Q12 -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge" style="background:#6f42c1;font-size:0.65rem;">ยุทธ์ 7</span>
                        <span class="badge bg-light text-dark border">ข้อ 12</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-3 flex-grow-1">จำนวนนวัตกรรมที่ใช้จริง (5 ด้าน)</h6>
                    <div class="d-flex align-items-end justify-content-between mt-auto border-top pt-3">
                        <div>
                            <?php
                            $q12 = $yearlySummary['q12'][0] ?? [];
                            $pct12 = ($q12['total_count'] > 0) ? ($q12['with_innovations'] / $q12['total_count'] * 100) : 0;
                            ?>
                            <div class="fs-2 fw-bold" style="color:#6f42c1; line-height:1;"><?= number_format($pct12, 2) ?>%</div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 0.75rem;">
                            <span class="d-block mb-1">สถานศึกษาที่มีนวัตกรรม:</span>
                            <strong class="text-dark fs-6"><?= number_format($q12['with_innovations'] ?? 0) ?></strong> / <?= number_format($q12['total_count'] ?? 0) ?> แห่ง<br>
                            นวัตกรรมรวม: <strong class="text-dark"><?= number_format($q12['total_innovations'] ?? 0) ?></strong> เรื่อง
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="glass-card mb-4 text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
    <p class="text-muted mb-3">ยังไม่มีข้อมูลแบบสอบถามสำหรับ <?= $selectedYear > 0 ? "ปี พ.ศ. $selectedYear" : "ทั้งหมดในระบบ" ?></p>
    <?php
    $latestYear = !empty($stats['by_year_map']) ? max(array_keys($stats['by_year_map'])) : YEAR_START;
    ?>
    <a href="<?= BASE_URL ?>admin/dashboard?year=<?= $latestYear ?>" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i>ไปปีที่มีข้อมูล (<?= $latestYear ?>)
    </a>
</div>
<?php endif; ?>

<!-- Recent Submissions + Affiliations (กรองตามปีที่เลือก) -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2"></i>แบบสอบถามล่าสุด
                    <small class="text-muted fw-normal fs-6"><?= $selectedYear > 0 ? "ปี $selectedYear" : "ทั้งหมด" ?></small>
                </h5>
                <a href="<?= BASE_URL ?>admin/surveys<?= $selectedYear > 0 ? "?year=$selectedYear" : "" ?>" class="btn btn-sm btn-outline-success">ดูทั้งหมด</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>โรงเรียน</th>
                            <th>สังกัด</th>
                            <th>อำเภอ</th>
                            <th>สถานะ</th>
                            <th>วันที่</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($yearStats['recent'])): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อมูลสำหรับปี <?= $selectedYear ?></td></tr>
                        <?php else: ?>
                        <?php foreach ($yearStats['recent'] as $r): ?>
                        <tr>
                            <td class="fw-medium"><?= htmlspecialchars($r['school_name_input']) ?></td>
                            <td><small><?= htmlspecialchars($r['affiliation_input']) ?></small></td>
                            <td><?= htmlspecialchars($r['district_input']) ?></td>
                            <td>
                                <?php
                                $statusBadge = match($r['status']) {
                                    'submitted' => 'bg-primary',
                                    'approved'  => 'bg-success',
                                    'rejected'  => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                                $statusLabel = match($r['status']) {
                                    'submitted' => 'ส่งแล้ว',
                                    'approved'  => 'อนุมัติ',
                                    'rejected'  => 'ปฏิเสธ',
                                    default     => 'ฉบับร่าง',
                                };
                                ?>
                                <span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span>
                            </td>
                            <td><small class="text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-bar-chart me-2"></i>ตามสังกัด
                <small class="text-muted fw-normal fs-6"><?= $selectedYear > 0 ? "ปี $selectedYear" : "รวมทุกปี" ?></small>
            </h5>
            <?php if (empty($yearStats['by_affiliation'])): ?>
            <p class="text-muted text-center py-4">ยังไม่มีข้อมูล</p>
            <?php else: ?>
            <?php foreach ($yearStats['by_affiliation'] as $aff): ?>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="fw-medium"><?= htmlspecialchars($aff['affiliation_input']) ?></small>
                    <small class="text-muted"><?= $aff['total'] ?> แห่ง</small>
                </div>
                <div class="progress" style="height:8px;">
                    <?php $pct = ($currentYearCount > 0) ? ($aff['total'] / $currentYearCount * 100) : 0; ?>
                    <div class="progress-bar progress-bar-custom" style="width:<?= $pct ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/dashboard.js?v=<?= time() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ส่งข้อมูลทุกปีสำหรับกราฟแนวโน้ม แต่ไฮไลท์ปีที่เลือก
    const allYearData  = <?= json_encode($stats['by_year'] ?? []) ?>;
    const districtData = <?= json_encode($yearStats['by_district'] ?? []) ?>;
    const selectedYear = <?= $selectedYear ?>;

    if (typeof initDashboardCharts === 'function') {
        initDashboardCharts(allYearData, districtData, selectedYear);
    }
    if (typeof initCounters === 'function') {
        initCounters();
    }
});
</script>
