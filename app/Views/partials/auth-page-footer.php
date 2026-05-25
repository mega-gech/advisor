<footer class="auth-v2-page-footer">
    <div class="auth-v2-footer-left">
        <svg class="auth-v2-footer-shield" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <?php if (($authFooterVariant ?? 'login') === 'register'): ?>
            <div>
                <strong>Official University Platform</strong>
                <span>AdvisorHub is your trusted academic support system.</span>
            </div>
        <?php else: ?>
            <div>
                <strong>Secure &amp; Reliable Platform</strong>
                <span>Your data is safe with us.</span>
            </div>
        <?php endif; ?>
    </div>
    <p class="auth-v2-footer-copy">&copy; <?php echo date('Y'); ?> AdvisorHub. All rights reserved.</p>
</footer>
