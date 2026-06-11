<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow me-2"></i>เปรียบเทียบข้อมูลแต่ละปี</h4>
    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>พิมพ์
    </button>
</div>

<!-- Overview Chart -->
<div class="glass-card mb-4 print-no-shadow">
    <h5 class="fw-bold mb-3">แนวโน้มจำนวนสถานศึกษาที่ส่งข้อมูลรายปี</h5>
    <div style="height:300px;">
        <canvas id="responsesTrendChart"></canvas>
    </div>
</div>

<div class="glass-card print-no-shadow">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th width="40%" class="text-start">ตัวชี้วัด (ค่าเฉลี่ยร้อยละ)</th>
                    <?php foreach ($years as $year): ?>
                    <th>ปี <?= $year ?><br><small class="text-muted fw-normal">(<?= number_format($comparison[$year]['total_responses']) ?> แห่ง)</small></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Q1 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-primary" style="font-size:0.6rem;">ยุทธ์ 1</span>1. ผู้เรียนพัฒนาทักษะ 3 ภาษา</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q1'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q2 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-primary" style="font-size:0.6rem;">ยุทธ์ 1</span>2. สถานศึกษาได้รับการนิเทศ</td>
                    <?php foreach ($years as $year): 
                        $q2 = $comparison[$year]['summary']['q2'][0] ?? [];
                        $pct2 = ($q2['total_count'] > 0) ? ($q2['supervised_count'] / $q2['total_count'] * 100) : 0;
                    ?>
                    <td><?= number_format($pct2, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q3 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-secondary" style="font-size:0.6rem;">ยุทธ์ 3</span>3. ผู้เรียนพัฒนาพหุปัญญา</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q3'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q4 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-info text-dark" style="font-size:0.6rem;">ยุทธ์ 4</span>4. ผ่านประเมินสุขภาวะที่ดี</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q4'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q5 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-warning text-dark" style="font-size:0.6rem;">ยุทธ์ 5</span>5. ร่วมเครือข่ายส่งเสริมรักชาติฯ</td>
                    <?php foreach ($years as $year): 
                        $q5 = $comparison[$year]['summary']['q5'][0] ?? [];
                        $pct5 = ($q5['total_count'] > 0) ? ($q5['activity_count'] / $q5['total_count'] * 100) : 0;
                    ?>
                    <td><?= number_format($pct5, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q6 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-warning text-dark" style="font-size:0.6rem;">ยุทธ์ 5</span>6. นักเรียนได้รับรางวัลคุณธรรม</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q6'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q7 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-success" style="font-size:0.6rem;">ยุทธ์ 6</span>7. ผู้เรียนได้รับการแนะแนว</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q7'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q8 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-success" style="font-size:0.6rem;">ยุทธ์ 6</span>8. อัตราเพิ่มขึ้น MOU</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q8'][0]['avg_rate'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <!-- Q9 -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-success" style="font-size:0.6rem;">ยุทธ์ 6</span>9. ผู้เรียนมีรายได้ระหว่างเรียน</td>
                    <?php foreach ($years as $year): ?>
                    <td><?= number_format($comparison[$year]['summary']['q9'][0]['avg_percentage'] ?? 0, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                
                <!-- Q11 (ยุทธ์ 7) -->
                <tr>
                    <td class="text-start">
                        <span class="badge me-1" style="background:#6f42c1;font-size:0.6rem;">ยุทธ์ 7</span>
                        10. หุ้นส่วนความร่วมมือ (เฉลี่ย แห่ง)
                    </td>
                    <?php foreach ($years as $year): ?>
                    <td style="color:#6f42c1;"><?= number_format($comparison[$year]['summary']['q11'][0]['avg_count'] ?? 0, 1) ?> แห่ง</td>
                    <?php endforeach; ?>
                </tr>

                <!-- Q12 (ยุทธ์ 7) -->
                <tr>
                    <td class="text-start">
                        <span class="badge me-1" style="background:#6f42c1;font-size:0.6rem;">ยุทธ์ 7</span>
                        11. สถานศึกษาที่มีนวัตกรรม (%)
                    </td>
                    <?php foreach ($years as $year): 
                        $q12 = $comparison[$year]['summary']['q12'][0] ?? [];
                        $pct12 = ($q12['total_count'] > 0) ? ($q12['with_innovations'] / $q12['total_count'] * 100) : 0;
                    ?>
                    <td style="color:#6f42c1;"><?= number_format($pct12, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>

                <!-- Q10 (ยุทธ์ 8) -->
                <tr>
                    <td class="text-start"><span class="badge me-1 bg-dark" style="font-size:0.6rem;">ยุทธ์ 8</span>12. หน่วยงานมี ITA ระดับ AA</td>
                    <?php foreach ($years as $year): 
                        $itaData = $comparison[$year]['summary']['q10'] ?? [];
                        $aaCount = 0; $totalIta = 0;
                        foreach($itaData as $ita) {
                            $totalIta += $ita['count'];
                            if(strpos($ita['ita_result'], 'AA') !== false) $aaCount += $ita['count'];
                        }
                        $pct10 = ($totalIta > 0) ? ($aaCount / $totalIta * 100) : 0;
                    ?>
                    <td><?= number_format($pct10, 2) ?>%</td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // เตรียมข้อมูลสำหรับ Chart
    const years = <?= json_encode($years) ?>;
    const responseCounts = <?= json_encode(array_map(function($y) use ($comparison) { return $comparison[$y]['total_responses']; }, $years)) ?>;
    
    // สร้าง Chart
    const ctx = document.getElementById('responsesTrendChart').getContext('2d');
    
    // Gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(27, 94, 32, 0.5)'); // Green
    gradient.addColorStop(1, 'rgba(27, 94, 32, 0.0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: years.map(y => 'ปี ' + y),
            datasets: [{
                label: 'จำนวนสถานศึกษาที่ส่งข้อมูล',
                data: responseCounts,
                borderColor: '#1B5E20',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#F9A825',
                pointBorderColor: '#fff',
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });
});
</script>
