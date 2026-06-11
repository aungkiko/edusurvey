<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-patch-question me-2"></i>จัดการคำถาม</h4>
    <a href="<?= BASE_URL ?>admin/questions/create" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i>เพิ่มคำถามใหม่
    </a>
</div>

<div class="row g-4">
    <?php if (empty($questions)): ?>
    <div class="col-12 text-center py-5">
        <div class="glass-card">
            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">ยังไม่มีข้อคำถามในระบบ</h5>
            <a href="<?= BASE_URL ?>admin/questions/create" class="btn btn-gold mt-3">เพิ่มคำถามข้อแรก</a>
        </div>
    </div>
    <?php else: ?>
    
    <?php foreach ($questions as $stratNum => $group): ?>
    <div class="col-12">
        <div class="glass-card mb-2 border-start border-4 border-success">
            <h5 class="fw-bold text-success mb-0"><?= htmlspecialchars($group['strategy_name']) ?></h5>
        </div>
        
        <?php foreach ($group['questions'] as $q): ?>
        <div class="glass-card p-3 mb-2 animate-fade-in ms-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge bg-gold text-dark me-2 fs-6">ข้อ <?= $q['question_number'] ?></span>
                    <span class="fw-medium"><?= htmlspecialchars($q['question_text']) ?></span>
                    <div class="text-muted small mt-2">
                        <span class="me-3"><i class="bi bi-tag me-1"></i>ประเภท: <?= $q['question_type'] ?></span>
                        <span><i class="bi bi-sort-numeric-down me-1"></i>ลำดับ: <?= $q['sort_order'] ?></span>
                    </div>
                </div>
                
                <div class="btn-group btn-group-sm">
                    <a href="<?= BASE_URL ?>admin/questions/edit?id=<?= $q['id'] ?>" class="btn btn-outline-warning" title="แก้ไข">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>admin/questions/delete" class="d-inline" onsubmit="return confirmDelete(event)">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="id" value="<?= $q['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger" title="ลบ">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    
    <?php endif; ?>
</div>

<script>
function confirmDelete(e) {
    e.preventDefault();
    Swal.fire({
        title: 'ยืนยันการลบคำถาม?',
        text: 'หากลบจะไม่แสดงในฟอร์มแบบสอบถามอีก (ข้อมูลการตอบเดิมยังคงอยู่)',
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
