/**
 * ===================================================
 * EduSurvey - Survey Form Wizard & Calculations
 * ===================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('surveyForm');
    if (!form) return;

    // --- Wizard Setup ---
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('.step-indicator');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    
    let currentStep = 0;
    const totalSteps = steps.length;

    function updateWizard() {
        // Hide all steps
        steps.forEach(step => step.classList.remove('active'));
        // Show current step
        steps[currentStep].classList.add('active');

        // Update indicators
        indicators.forEach((ind, index) => {
            if (index <= currentStep) {
                ind.classList.add('active');
            } else {
                ind.classList.remove('active');
            }
        });

        // Update progress bar
        const progress = ((currentStep) / (totalSteps - 1)) * 100;
        progressBar.style.width = progress + '%';

        // Buttons
        prevBtn.style.display = currentStep > 0 ? 'block' : 'none';
        
        if (currentStep === totalSteps - 1) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }

        // Scroll to top of form
        document.querySelector('.survey-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Validate step
    function validateStep() {
        const currentInputs = steps[currentStep].querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        currentInputs.forEach(input => {
            // Skip validation for hidden inputs (e.g. inside hidden containers)
            if (input.offsetParent === null && input.type !== 'hidden') {
                input.classList.remove('is-invalid');
                return;
            }
            
            if (input.type === 'radio' || input.type === 'checkbox') {
                // Radio/Checkbox group validation
                const name = input.name;
                const checked = steps[currentStep].querySelector(`input[name="${name}"]:checked`);
                if (!checked) {
                    isValid = false;
                    // Find container to add invalid class if needed
                }
            } else if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Custom Q12 validation
        const q12HasInnovations = steps[currentStep].querySelector('#q12_has_innovations');
        let q12Failed = false;
        if (q12HasInnovations && q12HasInnovations.offsetParent !== null && q12HasInnovations.value === 'yes') {
            const q12Inputs = steps[currentStep].querySelectorAll('.q12-detail-input');
            let sum = 0;
            q12Inputs.forEach(inp => {
                sum += parseFloat(inp.value) || 0;
            });
            if (sum === 0) {
                isValid = false;
                q12Failed = true;
                if (typeof showToast === 'function') {
                    showToast('กรุณาระบุจำนวนนวัตกรรมอย่างน้อย 1 เรื่อง', 'warning');
                } else {
                    alert('กรุณาระบุจำนวนนวัตกรรมอย่างน้อย 1 เรื่อง');
                }
                q12Inputs.forEach(inp => inp.classList.add('is-invalid'));
            } else {
                q12Inputs.forEach(inp => inp.classList.remove('is-invalid'));
            }
        }
        
        // Actually, just let standard toast happen if required inputs failed:
        if (!isValid) {
            if (typeof showToast === 'function' && !q12Failed) {
                showToast('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน (*)', 'warning');
            }
        }

        return isValid;
    }

    // Input validation clear on typing
    form.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) this.classList.remove('is-invalid');
        });
    });

    // Prevent mouse wheel scrolling from changing number inputs
    form.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('wheel', function(e) {
            e.preventDefault();
        }, { passive: false });
    });

    // Prevent Form Submission on Enter key and trigger Next step instead
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (currentStep < totalSteps - 1) {
                if (nextBtn) nextBtn.click();
            } else {
                if (submitBtn) submitBtn.click();
            }
        }
    });

    // Next/Prev Events
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (validateStep()) {
                currentStep++;
                updateWizard();
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentStep--;
            updateWizard();
        });
    }

    // Form Submit Confirmation
    if (submitBtn) {
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (validateStep()) {
                Swal.fire({
                    title: 'ยืนยันการส่งข้อมูล?',
                    text: "คุณตรวจสอบความถูกต้องของข้อมูลครบถ้วนแล้วใช่หรือไม่",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1B5E20',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, ส่งข้อมูลเลย!',
                    cancelButtonText: 'กลับไปตรวจสอบใหม่'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        form.submit();
                    }
                });
            }
        });
    }

    // Init Wizard
    updateWizard();

    // --- Auto Calculations ---
    const calcInputs = document.querySelectorAll('.calc-input');
    
    calcInputs.forEach(input => {
        input.addEventListener('input', function() {
            const qKey = this.dataset.calc; // e.g., 'q1'
            const numInput = document.querySelector(`input[data-calc="${qKey}"][data-role="numerator"]`);
            const denInput = document.querySelector(`input[data-calc="${qKey}"][data-role="denominator"]`);
            const resInput = document.getElementById(`calc-${qKey}`);
            
            if (numInput && denInput && resInput) {
                const num = parseFloat(numInput.value) || 0;
                const den = parseFloat(denInput.value) || 0;
                
                
                const finalDen = parseFloat(denInput.value) || 0;
                
                if (finalDen > 0) {
                    // ถ้าเป็นข้อ 8 เป็นอัตราการเพิ่มขึ้น ((ปีปัจจุบัน - ปีที่แล้ว) / ปีที่แล้ว)
                    if (qKey === 'q8') {
                        const increase = ((num - finalDen) / finalDen) * 100;
                        resInput.value = increase.toFixed(2) + '%';
                        if (increase >= 0) {
                            resInput.classList.add('text-success');
                            resInput.classList.remove('text-danger');
                        } else {
                            resInput.classList.add('text-danger');
                            resInput.classList.remove('text-success');
                        }
                    } else {
                        // ร้อยละปกติ (ส่วน/ทั้งหมด)
                        const pct = (num / finalDen) * 100;
                        resInput.value = (pct > 100 ? 100 : pct).toFixed(2) + '%';
                        resInput.classList.add('text-primary');
                    }
                } else {
                    if (qKey === 'q8' && num > 0) {
                        resInput.value = '100.00%'; // เพิ่มจาก 0 เป็นมีค่า
                    } else {
                        resInput.value = '0.00%';
                    }
                    resInput.classList.remove('text-success', 'text-danger', 'text-primary');
                }
            }
        });
    });
});
