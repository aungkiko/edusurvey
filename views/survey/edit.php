<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>แก้ไขข้อมูลแบบสอบถาม</h5>
                <a href="<?= BASE_URL ?>admin/surveys" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>กลับ
                </a>
            </div>

            <form action="<?= BASE_URL ?>admin/surveys/update" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="id" value="<?= $response['id'] ?>">

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label-custom">ชื่อสถานศึกษา</label>
                        <input type="text" class="form-control form-control-custom" name="school_name" value="<?= htmlspecialchars($response['school_name_input']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">สังกัด</label>
                        <select class="form-select form-control-custom" name="affiliation" required>
                            <?php foreach ($affiliations as $aff): ?>
                            <option value="<?= htmlspecialchars($aff) ?>" <?= $response['affiliation_input'] === $aff ? 'selected' : '' ?>>
                                <?= htmlspecialchars($aff) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">อำเภอ</label>
                        <select class="form-select form-control-custom" name="district" required>
                            <?php foreach ($districts as $dist): ?>
                            <option value="<?= htmlspecialchars($dist) ?>" <?= $response['district_input'] === $dist ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dist) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">ปีงบประมาณ</label>
                        <input type="text" class="form-control form-control-custom bg-light" value="<?= $response['budget_year'] ?>" readonly>
                        <small class="text-muted">ไม่สามารถแก้ไขปีงบประมาณได้</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">ชื่อผู้ตอบ</label>
                        <input type="text" class="form-control form-control-custom" name="respondent_name" value="<?= htmlspecialchars($response['respondent_name'] ?? '') ?>">
                    </div>

                    <div class="col-md-12 border-top pt-3 mt-4">
                        <label class="form-label-custom">สถานะแบบสอบถาม</label>
                        <div class="d-flex gap-4 mt-2">
                            <label class="radio-custom">
                                <input type="radio" name="status" value="submitted" <?= $response['status'] === 'submitted' ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                <span class="badge bg-primary">ส่งแล้ว (รอตรวจสอบ)</span>
                            </label>
                            <label class="radio-custom">
                                <input type="radio" name="status" value="approved" <?= $response['status'] === 'approved' ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                <span class="badge bg-success">อนุมัติ (ข้อมูลถูกต้อง)</span>
                            </label>
                            <label class="radio-custom">
                                <input type="radio" name="status" value="rejected" <?= $response['status'] === 'rejected' ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                <span class="badge bg-danger">ปฏิเสธ (ต้องแก้ไข)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-gold btn-lg px-4">
                        <i class="bi bi-save me-1"></i>บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
