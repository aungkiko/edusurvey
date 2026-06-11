<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ระบบแบบสอบถามตัวชี้วัดแผนพัฒนาการศึกษาจังหวัดปัตตานี พ.ศ. 2569-2573">
    <meta name="author" content="สำนักงานศึกษาธิการจังหวัดปัตตานี">
    <title><?= htmlspecialchars($title ?? 'หน้าแรก') ?> | <?= APP_NAME ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dark-mode.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/animations.css">
</head>
<body>
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display:none;">
        <div class="loading-spinner">
            <div class="spinner-grow text-success" role="status">
                <span class="visually-hidden">กำลังโหลด...</span>
            </div>
            <p class="mt-3 text-white">กำลังโหลด...</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
                <img src="https://www.ptnpeo.go.th/wp-content/uploads/2021/11/logo120w.png" alt="Logo" class="me-2 img-fluid" style="max-height: 45px; width: auto; object-fit: contain;">
                <div>
                    <span class="brand-title d-none d-sm-block" style="font-size: 1.1rem; line-height: 1.2;">สำนักงานศึกษาธิการจังหวัดปัตตานี</span>
                    <span class="brand-title d-sm-none" style="font-size: 1rem;">ศธจ.ปัตตานี</span>
                </div>
            </a>
            
            <div class="d-flex align-items-center gap-2">
                <!-- Dark Mode Toggle -->
                <button class="btn btn-icon" id="darkModeToggle" title="เปลี่ยนธีม">
                    <i class="bi bi-sun-fill" id="themeIcon"></i>
                </button>
                
                <?php if (Auth::check()): ?>
                    <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-gold btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>admin/login" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i>เข้าสู่ระบบ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999;" id="toastContainer"></div>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="bi bi-mortarboard-fill me-1"></i>
                        <?= APP_FULL_NAME ?>
                    </p>
                    <small class="text-muted">สำนักงานศึกษาธิการจังหวัดปัตตานี</small>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <small class="text-muted">
                        &copy; <?= date('Y') + 543 ?> | เวอร์ชัน <?= APP_VERSION ?>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    
    <?php
    // แสดง Flash Messages ผ่าน JS
    $flash = Session::getFlash();
    if ($flash):
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
        });
    </script>
    <?php endif; ?>
</body>
</html>
