<?php
$old = $_SESSION['register_old'] ?? [];
unset($_SESSION['register_old']);
include __DIR__ . '/../layouts/auth-header.php';
?>
<div class="auth-v2">
    <?php $variant = 'register'; include __DIR__ . '/../partials/auth-brand.php'; ?>

    <div class="auth-v2-main">
        <?php include __DIR__ . '/../partials/auth-lang-select.php'; ?>

        <div class="auth-v2-card-wrap auth-v2-card-wrap--wide">
            <div class="auth-v2-card">
                <h2 class="auth-v2-card-title">Create your student account</h2>
                <p class="auth-v2-card-subtitle">Use your official university email to register.</p>

                <?php include __DIR__ . '/../partials/auth-alerts.php'; ?>

                <form action="<?php echo url('register'); ?>" method="POST" class="auth-v2-form" id="registerForm" novalidate>
                    <div class="auth-v2-field">
                        <label for="name">Full Name</label>
                        <div class="auth-v2-input-wrap">
                            <span class="auth-v2-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 20v-1a6 6 0 0112 0v1"/></svg>
                            </span>
                            <input type="text" name="name" id="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" autocomplete="name" required minlength="2">
                        </div>
                        <span class="field-error" id="name-error" role="alert"></span>
                    </div>

                    <div class="auth-v2-field-row-2">
                        <div class="auth-v2-field">
                            <label for="student_number">Student ID</label>
                            <div class="auth-v2-input-wrap">
                                <span class="auth-v2-input-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/></svg>
                                </span>
                                <input type="text" name="student_number" id="student_number" placeholder="Enter your student ID" value="<?php echo htmlspecialchars($old['student_number'] ?? ''); ?>" required>
                            </div>
                            <span class="field-error" id="student_number-error" role="alert"></span>
                        </div>
                        <div class="auth-v2-field">
                            <label for="email">University Email</label>
                            <div class="auth-v2-input-wrap">
                                <span class="auth-v2-input-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8l8 5 8-5"/><rect x="4" y="6" width="16" height="12" rx="2"/></svg>
                                </span>
                                <input type="email" name="email" id="email" placeholder="name@aau.edu.et" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" autocomplete="email" required>
                            </div>
                            <p class="auth-v2-email-verified" id="emailVerified" hidden>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                University email verified
                            </p>
                            <span class="field-error" id="email-error" role="alert"></span>
                        </div>
                    </div>

                    <div class="auth-v2-field">
                        <label for="password">Password</label>
                        <div class="auth-v2-input-wrap">
                            <span class="auth-v2-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/></svg>
                            </span>
                            <input type="password" name="password" id="password" placeholder="Create a password" autocomplete="new-password" required minlength="8">
                            <button type="button" class="auth-v2-eye toggle-password" data-target="password" aria-label="Show password">
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" hidden><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                            </button>
                        </div>
                        <p class="auth-v2-strength" id="password-strength-label">Password strength: <span id="passwordStrengthText">—</span></p>
                        <div class="password-meter-bar"><div class="password-meter-fill" id="passwordMeterFill"></div></div>
                        <span class="field-error" id="password-error" role="alert"></span>
                    </div>

                    <div class="auth-v2-field">
                        <label for="password_confirm">Confirm Password</label>
                        <div class="auth-v2-input-wrap">
                            <span class="auth-v2-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/></svg>
                            </span>
                            <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirm your password" autocomplete="new-password" required minlength="8">
                            <button type="button" class="auth-v2-eye toggle-password" data-target="password_confirm" aria-label="Show password">
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" hidden><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                            </button>
                        </div>
                        <span class="field-error" id="password_confirm-error" role="alert"></span>
                    </div>

                    <div class="auth-v2-info-box" role="note">
                        <span class="auth-v2-info-icon" aria-hidden="true">i</span>
                        <p><strong>Important:</strong> Only official university email addresses are allowed. Pending approval from the registrar.</p>
                    </div>

                    <button type="submit" class="auth-v2-btn-primary" id="registerBtn">
                        <span class="btn-text">Register</span>
                        <span class="btn-arrow" aria-hidden="true">→</span>
                        <span class="btn-loading" hidden>Creating account…</span>
                    </button>
                </form>

                <p class="auth-v2-card-footer">
                    Already have an account? <a href="<?php echo url('login'); ?>">Login here</a>
                </p>
            </div>
        </div>

        <?php $authFooterVariant = 'register'; include __DIR__ . '/../partials/auth-page-footer.php'; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/auth-footer.php'; ?>