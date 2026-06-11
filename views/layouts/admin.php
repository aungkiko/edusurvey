<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> | <?= APP_NAME ?> Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dark-mode.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/animations.css">
</head>
<body class="admin-body">
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display:none;">
        <div class="loading-spinner">
            <div class="spinner-grow text-success" role="status"></div>
            <p class="mt-3 text-white">กำลังโหลด...</p>
        </div>
    </div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="<?= BASE_URL ?>admin/dashboard" class="sidebar-brand d-flex align-items-center text-decoration-none">
                    <img src="https://www.ptnpeo.go.th/wp-content/uploads/2021/11/logo120w.png" alt="Logo" class="me-2 img-fluid" style="max-height: 40px; width: auto; object-fit: contain;">
                    <div class="sidebar-brand-text">
                        <span style="font-size: 0.95rem; line-height: 1.2; display: block; white-space: normal;">สำนักงานศึกษาธิการจังหวัดปัตตานี</span>
                    </div>
                </a>
                <button class="btn-close-sidebar d-lg-none" id="closeSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">เมนูหลัก</span>
                    
                    <a href="<?= BASE_URL ?>admin/dashboard" class="nav-link-custom <?= ($title ?? '') === 'Dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>admin/surveys" class="nav-link-custom <?= strpos($title ?? '', 'แบบสอบถาม') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clipboard-data"></i>
                        <span>จัดการข้อมูล</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>admin/questions" class="nav-link-custom <?= strpos($title ?? '', 'คำถาม') !== false ? 'active' : '' ?>">
                        <i class="bi bi-patch-question"></i>
                        <span>จัดการคำถาม</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">รายงาน</span>
                    
                    <a href="<?= BASE_URL ?>admin/reports/yearly" class="nav-link-custom <?= strpos($title ?? '', 'รายปี') !== false ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>สรุปรายปี</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>admin/reports/compare" class="nav-link-custom <?= strpos($title ?? '', 'เปรียบเทียบ') !== false ? 'active' : '' ?>">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>เปรียบเทียบ</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <span class="nav-section-title">ระบบ</span>
                    
                    <a href="<?= BASE_URL ?>admin/settings" class="nav-link-custom <?= strpos($title ?? '', 'ตั้งค่า') !== false ? 'active' : '' ?>">
                        <i class="bi bi-gear"></i>
                        <span>ตั้งค่าระบบ</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>" class="nav-link-custom" target="_blank">
                        <i class="bi bi-globe"></i>
                        <span>ดูหน้าเว็บ</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>admin/logout" class="nav-link-custom text-danger" onclick="return confirm('ต้องการออกจากระบบ?')">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>ออกจากระบบ</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-icon d-lg-none me-2" id="toggleSidebar">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <h5 class="mb-0 topbar-title"><?= htmlspecialchars($title ?? '') ?></h5>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-icon" id="darkModeToggle" title="เปลี่ยนธีม">
                        <i class="bi bi-sun-fill" id="themeIcon"></i>
                    </button>
                    
                    <div class="dropdown">
                        <button class="btn btn-user dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="d-none d-md-inline"><?= htmlspecialchars(Auth::user()['full_name'] ?? 'Admin') ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text"><small class="text-muted"><?= Auth::user()['username'] ?? '' ?></small></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>admin/logout"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="admin-content">
                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    
    <!-- Sidebar Toggle -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('toggleSidebar');
        const close = document.getElementById('closeSidebar');
        
        if (toggle) toggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        if (close) close.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        if (overlay) overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    });
    </script>
    
    <?php
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
