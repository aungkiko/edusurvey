/**
 * ===================================================
 * EduSurvey - Main Application Script
 * ===================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Dark Mode Toggle
    initTheme();

    // 2. DataTables Initialization
    initDataTables();

    // 3. Initialize Tooltips & Popovers
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

/**
 * จัดการ Theme (Dark/Light)
 */
function initTheme() {
    const themeToggleBtn = document.getElementById('darkModeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;
    
    if (!themeToggleBtn) return;

    // ตรวจสอบค่าเก่าที่บันทึกไว้ หรือใช้ค่า default ของ OS
    let currentTheme = localStorage.getItem('theme');
    if (!currentTheme) {
        currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    setTheme(currentTheme);

    themeToggleBtn.addEventListener('click', () => {
        const newTheme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    });

    function setTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        if (theme === 'dark') {
            themeIcon.classList.remove('bi-sun-fill');
            themeIcon.classList.add('bi-moon-stars-fill');
        } else {
            themeIcon.classList.remove('bi-moon-stars-fill');
            themeIcon.classList.add('bi-sun-fill');
        }
    }
}

/**
 * แสดง Toast Notification
 */
window.showToast = function(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) return;

    const icon = type === 'success' ? 'bi-check-circle-fill text-success' : 
                 type === 'error' ? 'bi-x-circle-fill text-danger' : 
                 type === 'warning' ? 'bi-exclamation-triangle-fill text-warning' : 
                 'bi-info-circle-fill text-info';

    const toastId = 'toast-' + Date.now();
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center bg-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center fw-medium">
                    <i class="bi ${icon} fs-5 me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
    toast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
};

/**
 * แสดง Loading Overlay
 */
window.showLoading = function() {
    const loader = document.getElementById('loading-overlay');
    if (loader) loader.style.display = 'flex';
};

window.hideLoading = function() {
    const loader = document.getElementById('loading-overlay');
    if (loader) loader.style.display = 'none';
};

/**
 * Initialize DataTables
 */
function initDataTables() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        const table = $('#surveyTable');
        if (table.length) {
            table.DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/th.json'
                },
                paging: false, // ปิด paging ของ Datatables เพราะเราเขียน PHP pagination เองแล้ว
                info: false,
                searching: false, // ปิด search ช่องเล็ก เพราะเรามี form search ใหญ่
                order: [],
                columnDefs: [
                    { orderable: false, targets: -1 } // ปิด sort คอลัมน์จัดการ
                ]
            });
        }
    }
}
