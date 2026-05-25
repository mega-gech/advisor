<?php if (!empty($_SESSION['success'])): ?>
    <div class="auth-alert auth-alert-success" role="status">
        <span class="auth-alert-icon" aria-hidden="true">✓</span>
        <span><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
    </div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="auth-alert auth-alert-error" role="alert">
        <span class="auth-alert-icon" aria-hidden="true">!</span>
        <span><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
    </div>
<?php endif; ?>
