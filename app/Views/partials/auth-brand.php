<?php
/** @var string $variant 'login'|'register' */
$variant = $variant ?? 'login';
?>
<aside class="auth-v2-hero auth-v2-hero--<?php echo htmlspecialchars($variant); ?>" aria-label="AdvisorHub introduction">
    <div class="auth-v2-hero-overlay"></div>
    <div class="auth-v2-hero-content">
        <a href="<?php echo url('home'); ?>" class="auth-v2-logo">
            <span class="auth-v2-logo-icon" aria-hidden="true">🎓</span>
            <span class="auth-v2-logo-text">Advisor<span class="hub-accent">Hub</span></span>
        </a>
        <p class="auth-v2-tagline">Connect. Communicate. Succeed.</p>

        <?php if ($variant === 'register'): ?>
            <h1 class="auth-v2-headline">Join <span class="hub-accent">AdvisorHub</span> and simplify your academic journey</h1>
            <p class="auth-v2-lead">Create your student account using your university email to get started.</p>
            <ul class="auth-v2-features">
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                    <div>
                        <strong>Secure &amp; Trusted</strong>
                        <span>Your data is protected with top-level security and privacy.</span>
                    </div>
                </li>
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span>
                    <div>
                        <strong>Connect Easily</strong>
                        <span>Communicate with your advisor anytime, anywhere.</span>
                    </div>
                </li>
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
                    <div>
                        <strong>Stay Organized</strong>
                        <span>Manage appointments, deadlines and important updates.</span>
                    </div>
                </li>
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 106 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg></span>
                    <div>
                        <strong>Never Miss Updates</strong>
                        <span>Receive timely notifications and announcements.</span>
                    </div>
                </li>
            </ul>
        <?php else: ?>
            <h1 class="auth-v2-headline">Welcome to <span class="hub-accent">AdvisorHub</span></h1>
            <span class="auth-v2-headline-accent" aria-hidden="true"></span>
            <p class="auth-v2-lead">Your academic journey, guided by the right advisor.</p>
            <ul class="auth-v2-features">
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></span>
                    <div>
                        <strong>Connect with your advisor</strong>
                        <span>Communicate and get guidance whenever you need.</span>
                    </div>
                </li>
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
                    <div>
                        <strong>Manage appointments</strong>
                        <span>Book meetings and stay on top of your schedule.</span>
                    </div>
                </li>
                <li>
                    <span class="auth-v2-feature-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 106 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg></span>
                    <div>
                        <strong>Stay updated</strong>
                        <span>Receive important notifications and announcements.</span>
                    </div>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</aside>
