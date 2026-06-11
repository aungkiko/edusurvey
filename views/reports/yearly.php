<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2"></i>สรุปผลการดำเนินงานรายปี</h4>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="year" class="form-select form-select-sm form-control-custom w-auto" onchange="this.form.submit()">
                <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>>ปี พ.ศ. <?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <a href="<?= BASE_URL ?>admin/reports/export?year=<?= $year ?>" class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="glass-card text-center">
            <h6 class="text-muted mb-2">จำนวนสถานศึกษาที่ส่งข้อมูล</h6>
            <h2 class="fw-bold text-success mb-0"><?= number_format($totalResponses) ?></h2>
            <small class="text-muted">แห่ง</small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="glass-card">
            <p class="mb-0 text-muted">
                <i class="bi bi-info-circle me-1"></i> รายงานนี้สรุปผลจากสถานศึกษาทั้งหมดที่ส่งข้อมูลในปีงบประมาณ <?= $year ?> โดยแสดงค่าเฉลี่ยร้อยละและผลรวมจากทุกแห่ง
            </p>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="8%" class="text-center">ข้อที่</th>
                    <th width="42%">ตัวชี้วัด</th>
                    <th width="15%" class="text-center">ค่าเฉลี่ย (%)</th>
                    <th width="35%">รายละเอียดรวม</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalResponses == 0): ?>
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        ยังไม่มีข้อมูลสำหรับปี พ.ศ. <?= $year ?>
                    </td>
                </tr>
                <?php else: ?>
                
                <!-- Q1 -->
                <tr>
                    <td class="text-center">1</td>
                    <td><span class="badge me-1 bg-primary" style="font-size:0.65rem;">ยุทธ์ 1</span>ร้อยละผู้เรียนได้รับการพัฒนาทักษะภาษา อย่างน้อย 3 ภาษา</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q1'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่ได้รับการพัฒนารวม:</small>
                        <strong><?= number_format($summary['q1'][0]['total_developed'] ?? 0) ?></strong> / <?= number_format($summary['q1'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q2 -->
                <tr>
                    <td class="text-center">2</td>
                    <td><span class="badge me-1 bg-primary" style="font-size:0.65rem;">ยุทธ์ 1</span>ร้อยละของสถานศึกษาได้รับการนิเทศ</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?php 
                        $q2 = $summary['q2'][0] ?? [];
                        $pct2 = ($q2['total_count'] > 0) ? ($q2['supervised_count'] / $q2['total_count'] * 100) : 0;
                        echo number_format($pct2, 2) . '%';
                        ?>
                    </td>
                    <td>
                        <small class="text-muted d-block">สถานศึกษาที่ได้รับการนิเทศ:</small>
                        <strong><?= number_format($q2['supervised_count'] ?? 0) ?></strong> / <?= number_format($q2['total_count'] ?? 0) ?> แห่ง
                        <br><small class="text-muted">เฉลี่ย <?= number_format($q2['avg_supervision_count'] ?? 0, 1) ?> ครั้ง/ปี</small>
                    </td>
                </tr>

                <!-- Q3 -->
                <tr>
                    <td class="text-center">3</td>
                    <td><span class="badge me-1 bg-secondary" style="font-size:0.65rem;">ยุทธ์ 3</span>ร้อยละผู้เรียนได้รับการพัฒนาตามความถนัดด้านพหุปัญญา</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q3'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่ได้รับการพัฒนารวม:</small>
                        <strong><?= number_format($summary['q3'][0]['total_developed'] ?? 0) ?></strong> / <?= number_format($summary['q3'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q4 -->
                <tr>
                    <td class="text-center">4</td>
                    <td><span class="badge me-1 bg-info text-dark" style="font-size:0.65rem;">ยุทธ์ 4</span>ร้อยละผู้เรียนที่ผ่านการประเมินสุขภาวะที่ดี</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q4'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่ผ่านประเมินรวม:</small>
                        <strong><?= number_format($summary['q4'][0]['total_passed'] ?? 0) ?></strong> / <?= number_format($summary['q4'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q5 -->
                <tr>
                    <td class="text-center">5</td>
                    <td><span class="badge me-1 bg-warning text-dark" style="font-size:0.65rem;">ยุทธ์ 5</span>ร้อยละสถานศึกษาที่มีส่วนร่วมกับภาคีเครือข่ายส่งเสริมรักชาติ ศาสน์ กษัตริย์</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?php 
                        $q5 = $summary['q5'][0] ?? [];
                        $pct5 = ($q5['total_count'] > 0) ? ($q5['activity_count'] / $q5['total_count'] * 100) : 0;
                        echo number_format($pct5, 2) . '%';
                        ?>
                    </td>
                    <td>
                        <small class="text-muted d-block">สถานศึกษาที่จัดกิจกรรมร่วม:</small>
                        <strong><?= number_format($q5['activity_count'] ?? 0) ?></strong> / <?= number_format($q5['total_count'] ?? 0) ?> แห่ง
                        <br><small class="text-muted">เฉลี่ย <?= number_format($q5['avg_activities'] ?? 0, 1) ?> กิจกรรม/ปี</small>
                    </td>
                </tr>

                <!-- Q6 -->
                <tr>
                    <td class="text-center">6</td>
                    <td><span class="badge me-1 bg-warning text-dark" style="font-size:0.65rem;">ยุทธ์ 5</span>ร้อยละนักเรียนที่ได้รับรางวัลด้านคุณธรรม จริยธรรม</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q6'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่ได้รับรางวัลรวม:</small>
                        <strong><?= number_format($summary['q6'][0]['total_awarded'] ?? 0) ?></strong> / <?= number_format($summary['q6'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q7 -->
                <tr>
                    <td class="text-center">7</td>
                    <td><span class="badge me-1 bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>ร้อยละผู้เรียนที่ได้รับการแนะแนว</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q7'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่ได้รับการแนะแนวรวม:</small>
                        <strong><?= number_format($summary['q7'][0]['total_guided'] ?? 0) ?></strong> / <?= number_format($summary['q7'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q8 -->
                <tr>
                    <td class="text-center">8</td>
                    <td><span class="badge me-1 bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>อัตราการเพิ่มขึ้นของเครือข่ายความร่วมมือ (MOU)</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q8'][0]['avg_rate'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">จำนวน MOU รวมทั้งหมด:</small>
                        ปีปัจจุบัน <strong><?= number_format($summary['q8'][0]['total_current'] ?? 0) ?></strong> ฉบับ / ปีที่แล้ว <?= number_format($summary['q8'][0]['total_previous'] ?? 0) ?> ฉบับ
                    </td>
                </tr>

                <!-- Q9 -->
                <tr>
                    <td class="text-center">9</td>
                    <td><span class="badge me-1 bg-success" style="font-size:0.65rem;">ยุทธ์ 6</span>ร้อยละผู้เรียนที่มีรายได้ระหว่างเรียน</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?= number_format($summary['q9'][0]['avg_percentage'] ?? 0, 2) ?>%
                    </td>
                    <td>
                        <small class="text-muted d-block">นักเรียนที่มีรายได้รวม:</small>
                        <strong><?= number_format($summary['q9'][0]['total_income'] ?? 0) ?></strong> / <?= number_format($summary['q9'][0]['total_all'] ?? 0) ?> คน
                    </td>
                </tr>

                <!-- Q11 (ยุทธ์ 7) -->
                <tr>
                    <td class="text-center">10</td>
                    <td>
                        <span class="badge me-1" style="background:#6f42c1;font-size:0.65rem;">ยุทธ์ 7</span>
                        จำนวนหุ้นส่วนความร่วมมือ (ภาครัฐ-เอกชน-ประชาสังคม)
                    </td>
                    <td class="text-center fw-bold fs-5" style="color:#6f42c1;">
                        <?= number_format($summary['q11'][0]['avg_count'] ?? 0, 1) ?> แห่ง
                    </td>
                    <td>
                        <small class="text-muted d-block">รวมหุ้นส่วนความร่วมมือทุกสถานศึกษา:</small>
                        <strong><?= number_format($summary['q11'][0]['total_count'] ?? 0) ?></strong> แห่ง
                    </td>
                </tr>

                <!-- Q12 (ยุทธ์ 7) -->
                <tr>
                    <td class="text-center">11</td>
                    <td>
                        <span class="badge me-1" style="background:#6f42c1;font-size:0.65rem;">ยุทธ์ 7</span>
                        จำนวนนวัตกรรมที่ใช้จริง (5 ด้าน)
                    </td>
                    <td class="text-center fw-bold fs-5" style="color:#6f42c1;">
                        <?php
                        $q12 = $summary['q12'][0] ?? [];
                        $pct12 = ($q12['total_count'] > 0) ? ($q12['with_innovations'] / $q12['total_count'] * 100) : 0;
                        echo number_format($pct12, 2) . '%';
                        ?>
                    </td>
                    <td>
                        <small class="text-muted d-block">สถานศึกษาที่มีนวัตกรรม:</small>
                        <strong><?= number_format($q12['with_innovations'] ?? 0) ?></strong> / <?= number_format($q12['total_count'] ?? 0) ?> แห่ง
                        <br><small class="text-muted">นวัตกรรมรวม: <strong><?= number_format($q12['total_innovations'] ?? 0) ?></strong> เรื่อง</small>
                    </td>
                </tr>

                <!-- Q10 (ยุทธ์ 8) -->
                <tr>
                    <td class="text-center">12</td>
                    <td><span class="badge me-1 bg-dark" style="font-size:0.65rem;">ยุทธ์ 8</span>ร้อยละของหน่วยงานที่มีผลประเมิน ITA ระดับ AA</td>
                    <td class="text-center fw-bold text-primary fs-5">
                        <?php
                        $itaData = $summary['q10'] ?? [];
                        $aaCount = 0; $totalIta = 0;
                        foreach($itaData as $ita) {
                            $totalIta += $ita['count'];
                            if(strpos($ita['ita_result'], 'AA') !== false) $aaCount += $ita['count'];
                        }
                        $pct10 = ($totalIta > 0) ? ($aaCount / $totalIta * 100) : 0;
                        echo number_format($pct10, 2) . '%';
                        ?>
                    </td>
                    <td>
                        <small class="text-muted d-block">หน่วยงานที่ได้ระดับ AA ขึ้นไป:</small>
                        <strong><?= $aaCount ?></strong> / <?= $totalIta ?> แห่ง
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
