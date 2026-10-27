/**
 * Harsha Pest Control - Main JavaScript Application
 * Mobile menu toggle, client-side phone validation, AJAX enquiry submission.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Menu Toggle
    const navToggle = document.getElementById('navToggle');
    const primaryNav = document.getElementById('primaryNav');

    if (navToggle && primaryNav) {
        navToggle.addEventListener('click', function () {
            const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', !isExpanded);
            primaryNav.classList.toggle('nav-open');
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!primaryNav.contains(e.target) && !navToggle.contains(e.target) && primaryNav.classList.contains('nav-open')) {
                primaryNav.classList.remove('nav-open');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // 2. Enquiry Form Submission Handlers
    setupEnquiryForm('enquiryForm', 'submitBtn', 'formStatus');
    setupEnquiryForm('contactPageForm', 'c_submitBtn', 'c_formStatus');

    function setupEnquiryForm(formId, btnId, statusId) {
        const form = document.getElementById(formId);
        const btn = document.getElementById(btnId);
        const statusBox = document.getElementById(statusId);

        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Client Validation
            const nameInput = form.querySelector('input[name="customer_name"]');
            const phoneInput = form.querySelector('input[name="customer_phone"]');
            const consentInput = form.querySelector('input[name="consent"]');

            if (!nameInput || nameInput.value.trim().length < 2) {
                showStatus(statusBox, 'Please enter your name.', 'error');
                nameInput && nameInput.focus();
                return;
            }

            // Clean phone digits
            const rawPhone = phoneInput ? phoneInput.value.trim() : '';
            let cleanPhone = rawPhone.replace(/\D/g, '');
            if (cleanPhone.length === 12 && cleanPhone.startsWith('91')) {
                cleanPhone = cleanPhone.substring(2);
            }

            const phonePattern = /^[6-9]\d{9}$/;
            if (!phonePattern.test(cleanPhone)) {
                showStatus(statusBox, 'Please enter a valid 10-digit Indian mobile number (e.g., 63052 35360).', 'error');
                phoneInput && phoneInput.focus();
                return;
            }

            if (consentInput && !consentInput.checked) {
                showStatus(statusBox, 'Please check the consent box to allow us to call you back.', 'error');
                return;
            }

            // Disable button during submission
            if (btn) {
                btn.disabled = true;
                btn.classList.add('loading');
                btn.setAttribute('data-original-text', btn.innerHTML);
                btn.innerHTML = '<span>Submitting Request...</span>';
            }

            showStatus(statusBox, 'Saving your inspection request...', 'info');

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showStatus(statusBox, data.message || 'Thank you! Your inspection request has been saved.', 'success');
                    form.reset();
                } else {
                    showStatus(statusBox, data.message || 'Could not submit your request. Please try calling directly.', 'error');
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                // Fallback to native form POST if AJAX fails
                showStatus(statusBox, 'Sending directly...', 'info');
                form.submit();
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    const orig = btn.getAttribute('data-original-text');
                    if (orig) btn.innerHTML = orig;
                }
            });
        });
    }

    function showStatus(box, text, type) {
        if (!box) return;
        box.className = 'form-status alert alert-' + type;
        box.innerText = text;
        box.style.display = 'block';
    }
});
