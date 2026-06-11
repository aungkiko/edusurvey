<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    <i class="bi <?= $question ? 'bi-pencil' : 'bi-plus-circle' ?> me-2"></i>
                    <?= $question ? 'แก้ไขคำถาม' : 'เพิ่มคำถามใหม่' ?>
                </h5>
                <a href="<?= BASE_URL ?>admin/questions" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>กลับ
                </a>
            </div>

            <form action="<?= BASE_URL ?>admin/questions/<?= $question ? 'update' : 'store' ?>" method="POST">
                <?= CSRF::field() ?>
                <?php if ($question): ?>
                <input type="hidden" name="id" value="<?= $question['id'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label-custom">หมายเลขข้อ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-custom" name="question_number" 
                               value="<?= $question ? $question['question_number'] : $nextNumber ?>" required min="1">
                    </div>

                    <div class="col-md-9">
                        <label class="form-label-custom">ยุทธศาสตร์ <span class="text-danger">*</span></label>
                        <select class="form-select form-control-custom" name="strategy_number" required>
                            <option value="">-- เลือกยุทธศาสตร์ --</option>
                            <?php foreach ($strategies as $num => $name): ?>
                            <option value="<?= $num ?>" <?= ($question['strategy_number'] ?? '') == $num ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">เนื้อหาคำถาม <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom" name="question_text" rows="3" required><?= htmlspecialchars($question['question_text'] ?? '') ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">รูปแบบคำถาม <span class="text-danger">*</span></label>
                        <select class="form-select form-control-custom" name="question_type" required>
                            <option value="percentage" <?= ($question['question_type'] ?? '') === 'percentage' ? 'selected' : '' ?>>คำนวณร้อยละ</option>
                            <option value="yes_no" <?= ($question['question_type'] ?? '') === 'yes_no' ? 'selected' : '' ?>>มี/ไม่มี (Yes/No)</option>
                            <option value="rating" <?= ($question['question_type'] ?? '') === 'rating' ? 'selected' : '' ?>>ระดับคะแนน/เกรด</option>
                            <option value="text" <?= ($question['question_type'] ?? '') === 'text' ? 'selected' : '' ?>>ข้อความอิสระ</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">ลำดับการแสดงผล</label>
                        <input type="number" class="form-control form-control-custom" name="sort_order" 
                               value="<?= $question['sort_order'] ?? 0 ?>">
                    </div>

                    <?php if ($question): ?>
                    <div class="col-md-3">
                        <label class="form-label-custom">สถานะ</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= $question['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label">เปิดใช้งาน</label>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-gold btn-lg px-4">
                        <i class="bi bi-save me-1"></i>บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
        
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle me-2"></i> <strong>หมายเหตุ:</strong> การเพิ่มหรือแก้ไขโครงสร้างคำถามนี้ จะมีผลกับการแสดงผลหน้าหัวข้อเท่านั้น รูปแบบฟิลด์การกรอกข้อมูลในหน้าหลักได้ถูกออกแบบไว้แบบเฉพาะเจาะจง (Hardcoded) เพื่อรองรับรูปแบบที่ซับซ้อนตามไฟล์ PDF
        </div>
    </div>
</div>
