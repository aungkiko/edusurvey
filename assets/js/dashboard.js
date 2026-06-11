/**
 * ===================================================
 * EduSurvey - Dashboard Scripts
 * ===================================================
 */

// Initialize Chart.js defaults
if (typeof Chart !== 'undefined') {
    Chart.defaults.font.family = "'Inter', 'Noto Sans Thai', sans-serif";
    Chart.defaults.color = '#718096';
}

/**
 * Animated Counters for Dashboard Stats
 */
function initCounters() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // ยิ่งน้อยยิ่งเร็ว

    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        
        const updateCount = () => {
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 15);
            } else {
                counter.innerText = target;
            }
        };

        // Reset text and start
        counter.innerText = '0';
        updateCount();
    });
}

/**
 * Initialize Dashboard Charts
 * @param {Array} allYearData  - ข้อมูลทุกปี
 * @param {Array} districtData - ข้อมูลอำเภอ (กรองตามปีแล้ว)
 * @param {number} selectedYear - ปีที่เลือก
 */
function initDashboardCharts(allYearData, districtData, selectedYear) {
    if (typeof Chart === 'undefined') return;

    // --- 1. Yearly Chart: แสดงเฉพาะปีที่เลือก หรือ แสดงทุกปีถ้า selectedYear = 0 ---
    const ctxYear = document.getElementById('yearlyChart');
    if (ctxYear && allYearData.length > 0) {
        // ถ้ารวมทุกปี (0) ให้เอาข้อมูลทั้งหมด, ถ้าดูเฉพาะปีให้กรอง
        const displayData = selectedYear === 0 
            ? allYearData 
            : allYearData.filter(item => parseInt(item.budget_year) === selectedYear);

        const labels = displayData.map(item => 'พ.ศ. ' + item.budget_year);
        const data   = displayData.map(item => parseInt(item.total));

        // ใช้สีเขียวตามธีม
        const bgColors = displayData.map(() => 'rgba(27, 94, 32, 0.8)');
        const borderColors = displayData.map(() => '#1B5E20');

        new Chart(ctxYear, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนแบบสอบถาม',
                    data: data,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: selectedYear === 0 ? 6 : 10,
                    hoverBackgroundColor: selectedYear === 0 ? '#2E7D32' : '#FFD54F',
                    barThickness: selectedYear === 0 ? undefined : 80,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: (items) => 'พ.ศ. ' + displayData[items[0].dataIndex].budget_year,
                            label: (ctx) => ' จำนวน ' + ctx.parsed.y + ' แห่ง'
                        }
                    },
                    // แสดงตัวเลขบนแท่ง
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        grace: '15%'
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                animation: {
                    onComplete: function() {
                        // วาดตัวเลขบนแท่ง
                        const chart = this;
                        const ctx2 = chart.ctx;
                        ctx2.save();
                        ctx2.font = selectedYear === 0 ? 'bold 14px "Inter", "Noto Sans Thai", sans-serif' : 'bold 20px "Inter", "Noto Sans Thai", sans-serif';
                        ctx2.fillStyle = '#333';
                        ctx2.textAlign = 'center';
                        ctx2.textBaseline = 'bottom';
                        chart.data.datasets.forEach((dataset, i) => {
                            chart.getDatasetMeta(i).data.forEach((bar, index) => {
                                ctx2.fillText(dataset.data[index] + ' แห่ง', bar.x, bar.y - 6);
                            });
                        });
                        ctx2.restore();
                    }
                }
            }
        });
    }

    // --- 2. District Distribution Chart (Doughnut) ---
    const ctxDistrict = document.getElementById('districtChart');
    if (ctxDistrict && districtData.length > 0) {
        const labels = districtData.map(item => item.district_input);
        const data = districtData.map(item => item.total);

        // MOE Color Palette colors
        const colors = [
            '#1B5E20', '#2E7D32', '#43A047', '#66BB6A', '#81C784', 
            '#F9A825', '#FBC02D', '#FDD835', '#FFEE58', '#FFF59D',
            '#0A3D0C', '#E65100'
        ];

        new Chart(ctxDistrict, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, data.length),
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }
}
