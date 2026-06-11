<div class="login-page">
    <div class="login-bg"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="glass-card login-card animate-slide-up">
                    <div class="text-center mb-4">
                        <div class="login-icon mb-3">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="fw-bold">เข้าสู่ระบบผู้ดูแล</h3>
                        <p class="text-muted small"><?= APP_FULL_NAME ?></p>
                    </div>
                    
                    <form action="<?= BASE_URL ?>admin/authenticate" method="POST" id="loginForm">
                        <?= CSRF::field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label-custom" for="username">
                                <i class="bi bi-person me-1"></i>ชื่อผู้ใช้
                            </label>
                            <input type="text" class="form-control form-control-custom form-control-lg" 
                                   id="username" name="username" placeholder="Username" required autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label-custom" for="password">
                                <i class="bi bi-lock me-1"></i>รหัสผ่าน
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-custom form-control-lg" 
                                       id="password" name="password" placeholder="Password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-gold btn-lg w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ
                        </button>
                        
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>" class="text-muted small text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>กลับหน้าแรก
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword')?.addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = this.querySelector('i');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
});
</script>
