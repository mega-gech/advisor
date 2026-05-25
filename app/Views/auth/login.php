<?php
$rememberedEmail = $_COOKIE['advisorhub_email'] ?? '';
include __DIR__ . '/../layouts/auth-header.php';
?>
<div class="auth-v2">
    <?php $variant = 'login'; include __DIR__ . '/../partials/auth-brand.php'; ?>

    <div class="auth-v2-main">
        <?php include __DIR__ . '/../partials/auth-lang-select.php'; ?>

        <div class="auth-v2-card-wrap">
            <div class="auth-v2-card">
                <h2 class="auth-v2-card-title">Login to your account</h2>
                <p class="auth-v2-card-subtitle">Enter your university email and password to continue.</p>

                <?php include __DIR__ . '/../partials/auth-alerts.php'; ?>

                <form action="<?php echo url('login'); ?>" method="POST" class="auth-v2-form" id="loginForm" novalidate>
                    <div class="auth-v2-field">
                        <label for="email">University Email</label>
                        <div class="auth-v2-input-wrap">
                            <span class="auth-v2-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z" opacity="0"/><path d="M4 8l8 5 8-5"/><rect x="4" y="6" width="16" height="12" rx="2"/></svg>
                            </span>
                            <input type="email" name="email" id="email" placeholder="name@aau.edu.et" value="<?php echo htmlspecialchars($rememberedEmail); ?>" autocomplete="username" required>
                        </div>
                        <span class="field-error" id="email-error" role="alert"></span>
                    </div>

                    <div class="auth-v2-field">
                        <label for="password">Password</label>
                        <div class="auth-v2-input-wrap">
                            <span class="auth-v2-input-icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/></svg>
                            </span>
                            <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="auth-v2-eye toggle-password" data-target="password" aria-label="Show password">
                                <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" hidden><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22"/></svg>
                            </button>
                        </div>
                        <div class="auth-v2-field-row">
                            <label class="auth-v2-remember">
                                <input type="checkbox" name="remember" value="1" <?php echo $rememberedEmail ? 'checked' : ''; ?>>
                                Remember me
                            </label>
                            <a href="mailto:registrar@aau.edu.et?subject=Password%20Reset%20Request" class="auth-v2-forgot">Forgot Password?</a>
                        </div>
                        <span class="field-error" id="password-error" role="alert"></span>
                    </div>

                    <button type="submit" class="auth-v2-btn-primary" id="loginBtn">
                        <span class="btn-text">Login</span>
                        <span class="btn-arrow" aria-hidden="true">→</span>
                        <span class="btn-loading" hidden>Signing in…</span>
                    </button>
                </form>

                <p class="auth-v2-card-footer">
                    Don't have an account? <a href="<?php echo url('register'); ?>">Contact Registrar</a>
                </p>
            </div>
        </div>

        <?php $authFooterVariant = 'login'; include __DIR__ . '/../partials/auth-page-footer.php'; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/auth-footer.php'; ?>