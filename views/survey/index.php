<!-- Filters -->
<div class="glass-card mb-4">
    <form method="GET" action="<?= BASE_URL ?>admin/surveys" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label-custom small">ค้นหา</label>
            <input type="text" class="form-control form-control-custom" name="search" value="<?= htmlspecialchars($filters['search']) ?>" placeholder="ชื่อโรงเรียน...">
        </div>
        <div class="col-md-2">
            <label class="form-label-custom small">ปี</label>
            <select class="form-select form-control-custom" name="year">
                <option value="">ทุกปี</option>
                <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>" <?= $filters['year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-custom small">อำเภอ</label>
            <select class="form-select form-control-custom" name="district">
                <option value="">ทุกอำเภอ</option>
                <?php foreach ($districts as $d): ?>
                <option value="<?= htmlspecialchars($d) ?>" <?= $filters['district'] === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-custom small">สถานะ</label>
            <select class="form-select form-control-custom" name="status">
                <option value="">ทุกสถานะ</option>
                <option value="submitted" <?= $filters['status'] === 'submitted' ? 'selected' : '' ?>>ส่งแล้ว</option>
                <option value="approved" <?= $filters['status'] === 'approved' ? 'selected' : '' ?>>อนุมัติ</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-gold"><i class="bi bi-search me-1"></i>ค้นหา</button>
            <a href="<?= BASE_URL ?>admin/surveys" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>ล้าง</a>
            <a href="<?= BASE_URL ?>admin/surveys/export?<?= http_build_query(array_filter($filters)) ?>" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i>Export
            </a>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-clipboard-data me-2"></i>รายการแบบสอบถาม
            <span class="badge bg-success ms-2"><?= $total ?> รายการ</span>
        </h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle" <?= !empty($responses) ? 'id="surveyTable"' : '' ?>>
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>โรงเรียน</th>
                    <th>สังกัด</th>
                    <th>อำเภอ</th>
                    <th>ปี</th>
                    <th>สถานะ</th>
                    <th>วันที่ส่ง</th>
                    <th width="120">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($responses)): ?>
                <tr><td colspan="8" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>ไม่พบข้อมูล
                </td></tr>
                <?php else: ?>
                <?php foreach ($responses as $i => $r): ?>
                <tr class="animate-fade-in" style="animation-delay:<?= $i * 0.05 ?>s">
                    <td class="text-muted"><?= (($currentPage - 1) * PER_PAGE) + $i + 1 ?></td>
                    <td class="fw-medium"><?= htmlspecialchars($r['school_name_input']) ?></td>
                    <td><small><?= htmlspecialchars($r['affiliation_input']) ?></small></td>
                    <td><?= htmlspecialchars($r['district_input']) ?></td>
                    <td><span class="badge bg-success"><?= $r['budget_year'] ?></span></td>
                    <td>
                        <?php
                        $statusBadge = match($r['status']) {
                            'submitted' => 'bg-primary', 'approved' => 'bg-success',
                            'rejected' => 'bg-danger', default => 'bg-secondary',
                        };
                        $statusLabel = match($r['status']) {
                            'submitted' => 'ส่งแล้ว', 'approved' => 'อนุมัติ',
                            'rejected' => 'ปฏิเสธ', default => 'ฉบับร่าง',
                        };
                        ?>
                        <span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span>
                    </td>
                    <td><small class="text-muted"><?= $r['submitted_at'] ? date('d/m/Y H:i', strtotime($r['submitted_at'])) : '-' ?></small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="<?= BASE_URL ?>admin/surveys/view?id=<?= $r['id'] ?>" class="btn btn-outline-primary" title="ดู">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= BASE_URL ?>admin/surveys/edit?id=<?= $r['id'] ?>" class="btn btn-outline-warning" title="แก้ไข">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>admin/surveys/delete" class="d-inline" onsubmit="return confirmDelete(event)">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination pagination-custom justify-content-center">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>&<?= http_build_query(array_filter($filters)) ?>">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
            <?php for ($p = 1; $p <= $pages; $p++): ?>
            <li class="page-item <?= $p == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&<?= http_build_query(array_filter($filters)) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $currentPage + 1 ?>&<?= http_build_query(array_filter($filters)) ?>">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
function confirmDelete(e) {
    e.preventDefault();
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: 'ข้อมูลที่ลบจะไม่สามารถกู้คืนได้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) e.target.submit();
    });
    return false;
}
</script>
