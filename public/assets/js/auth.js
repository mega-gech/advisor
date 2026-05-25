(function () {
    'use strict';

    var AAU_DOMAIN = '@aau.edu.et';

    function $(id) {
        return document.getElementById(id);
    }

    function showFieldError(fieldId, message) {
        var input = $(fieldId);
        var errorEl = $(fieldId + '-error');
        if (!input || !errorEl) return;
        if (message) {
            input.classList.add('is-invalid');
            errorEl.textContent = message;
            errorEl.classList.add('is-visible');
        } else {
            input.classList.remove('is-invalid');
            errorEl.textContent = '';
            errorEl.classList.remove('is-visible');
        }
    }

    function clearFormErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        form.querySelectorAll('.field-error.is-visible').forEach(function (el) {
            el.textContent = '';
            el.classList.remove('is-visible');
        });
    }

    function isAauEmail(email) {
        return email.toLowerCase().endsWith(AAU_DOMAIN) && email.indexOf('@') > 0;
    }

    function passwordStrength(password) {
        if (!password) return { level: '', label: '—', textClass: '' };
        var score = 0;
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;

        if (password.length < 8) {
            return { level: 'weak', label: 'Weak', textClass: 'weak' };
        }
        if (score <= 2) return { level: 'weak', label: 'Weak', textClass: 'weak' };
        if (score <= 3) return { level: 'fair', label: 'Fair', textClass: 'fair' };
        return { level: 'strong', label: 'Strong', textClass: 'strong' };
    }

    function updatePasswordMeter(password) {
        var fill = $('passwordMeterFill');
        var text = $('passwordStrengthText');
        if (!fill && !text) return;

        var result = passwordStrength(password);
        if (fill) {
            fill.className = 'password-meter-fill' + (result.level ? ' ' + result.level : '');
        }
        if (text) {
            text.textContent = result.label;
            text.className = result.textClass;
        }
    }

    function updateEmailVerified(email) {
        var el = $('emailVerified');
        if (!el) return;
        el.hidden = !isAauEmail(email.trim());
    }

    function setSubmitLoading(btn, loading) {
        if (!btn) return;
        btn.disabled = loading;
        var text = btn.querySelector('.btn-text');
        var arrow = btn.querySelector('.btn-arrow');
        var load = btn.querySelector('.btn-loading');
        if (text) text.hidden = loading;
        if (arrow) arrow.hidden = loading;
        if (load) load.hidden = !loading;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = $(btn.getAttribute('data-target'));
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                var open = btn.querySelector('.eye-open');
                var closed = btn.querySelector('.eye-closed');
                if (open) open.hidden = !show;
                if (closed) closed.hidden = show;
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });

        var emailInput = $('email');
        if (emailInput) {
            emailInput.addEventListener('input', function () {
                updateEmailVerified(emailInput.value);
                showFieldError('email', '');
            });
            emailInput.addEventListener('blur', function () {
                updateEmailVerified(emailInput.value);
            });
            updateEmailVerified(emailInput.value);
        }

        var passwordInput = $('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                updatePasswordMeter(passwordInput.value);
            });
            updatePasswordMeter(passwordInput.value);
        }

        var loginForm = $('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                clearFormErrors(loginForm);
                var email = ($('email') || {}).value || '';
                var password = ($('password') || {}).value || '';
                var valid = true;

                if (!email.trim()) {
                    showFieldError('email', 'Email is required.');
                    valid = false;
                } else if (!isAauEmail(email.trim())) {
                    showFieldError('email', 'Use your official @aau.edu.et email address.');
                    valid = false;
                }

                if (!password) {
                    showFieldError('password', 'Password is required.');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    return;
                }

                setSubmitLoading($('loginBtn'), true);
            });
        }

        var registerForm = $('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function (e) {
                clearFormErrors(registerForm);
                var name = ($('name') || {}).value || '';
                var email = ($('email') || {}).value || '';
                var studentNumber = ($('student_number') || {}).value || '';
                var password = ($('password') || {}).value || '';
                var confirm = ($('password_confirm') || {}).value || '';
                var valid = true;

                if (name.trim().length < 2) {
                    showFieldError('name', 'Enter your full name.');
                    valid = false;
                }

                if (!isAauEmail(email.trim())) {
                    showFieldError('email', 'Only @aau.edu.et university emails are accepted.');
                    valid = false;
                }

                if (!studentNumber.trim()) {
                    showFieldError('student_number', 'Student ID is required.');
                    valid = false;
                }

                if (password.length < 8) {
                    showFieldError('password', 'Password must be at least 8 characters.');
                    valid = false;
                }

                if (password !== confirm) {
                    showFieldError('password_confirm', 'Passwords do not match.');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    return;
                }

                setSubmitLoading($('registerBtn'), true);
            });

            ['name', 'email', 'student_number', 'password', 'password_confirm'].forEach(function (id) {
                var el = $(id);
                if (el) {
                    el.addEventListener('input', function () {
                        showFieldError(id, '');
                    });
                }
            });
        }
    });
})();
