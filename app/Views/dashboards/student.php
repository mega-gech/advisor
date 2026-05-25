<?php
$userName = $_SESSION['user_name'] ?? 'Student';
$firstName = first_name($userName);
$icon = asset('img/icons.svg');
$section = $section ?? portal_section();
$portalBadges = $portalBadges ?? ['messages' => 0, 'notifications' => 0];
$messages = $messages ?? [];
$all_appointments = $all_appointments ?? [];
$notifications = $notifications ?? [];
$profile = $profile ?? ['name' => $userName, 'email' => '', 'role' => 'student'];
$search = $search ?? '';

include __DIR__ . '/../layouts/header.php';
?>

<div class="student-dashboard portal-dashboard" data-active-section="<?php echo htmlspecialchars($section); ?>">
    <?php
    $roleLabel = 'Student';
    $topbarSearch = portal_topbar_search('student', $section);
    $notifCount = (int) ($portalBadges['notifications'] ?? 0);
    $searchAction = $topbarSearch['enabled'] ? url('student_dashboard') : '';
    $searchSection = $topbarSearch['section'];
    $searchPlaceholder = $topbarSearch['placeholder'];
    $searchAriaLabel = $topbarSearch['aria'];
    $searchValue = $search;
    $dashboardAction = 'student_dashboard';
    $notifLink = url('student_dashboard', ['section' => 'notifications']);
    include __DIR__ . '/../partials/portal-topbar.php';
    ?>

    <div class="page-content">
        <div class="welcome-section">
            <div>
                <h1 class="welcome-title">Welcome back, <?php echo htmlspecialchars($firstName); ?></h1>
                <p class="welcome-subtitle"><?php
                    $subtitles = [
                        'dashboard' => "Here's what's happening with your academic journey.",
                        'advisor' => 'Your assigned academic advisor and contact details.',
                        'messages' => 'View and send messages to your advisor.',
                        'appointments' => 'Schedule and track your advising appointments.',
                        'notifications' => 'Recent items that need your attention.',
                        'profile' => 'Your student account information.',
                    ];
                    echo htmlspecialchars($subtitles[$section] ?? $subtitles['dashboard']);
                ?></p>
            </div>

        </div>

        <!-- Dashboard overview -->
        <div class="portal-section <?php echo $section === 'dashboard' ? 'is-active' : ''; ?>" id="section-dashboard">
            <div class="metrics-grid metrics-grid-3">
                <a href="<?php echo url('student_dashboard', ['section' => 'messages']); ?>" class="metric-card">
                    <div class="metric-icon-box blue"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#message"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value"><?php echo (int) $unread_messages; ?></div>
                        <div class="metric-label">Unread Messages</div>
                        <span class="metric-link">View Messages →</span>
                    </div>
                </a>
                <a href="<?php echo url('student_dashboard', ['section' => 'notifications']); ?>" class="metric-card">
                    <div class="metric-icon-box green"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value"><?php echo (int) ($portalBadges['notifications'] ?? 0); ?></div>
                        <div class="metric-label">Notifications</div>
                        <span class="metric-link">View All →</span>
                    </div>
                </a>
                <a href="<?php echo url('student_dashboard', ['section' => 'appointments']); ?>" class="metric-card">
                    <div class="metric-icon-box orange"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#calendar"></use></svg></div>
                    <div class="metric-info">
                        <div class="metric-value"><?php echo (int) $upcoming_appointments; ?></div>
                        <div class="metric-label">Upcoming Appointments</div>
                        <span class="metric-link">View Appointments →</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- My Advisor -->
        <div class="portal-section <?php echo $section === 'advisor' ? 'is-active' : ''; ?>" id="section-advisor">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Advisor</h3>
                </div>
                <?php if ($advisor): ?>
                <div class="advisor-profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($advisor['advisor_name']); ?>&background=1e293b&color=ffffff" alt="" class="advisor-avatar">
                    <div class="advisor-details">
                        <div class="advisor-name"><?php echo htmlspecialchars($advisor['advisor_name']); ?></div>
                        <div class="advisor-dept">Academic Advisor</div>
                        <div class="advisor-contact">
                            <span class="advisor-contact-icon"><svg class="icon" width="16" height="16"><use xlink:href="<?php echo $icon; ?>#mail"></use></svg></span>
                            <?php echo htmlspecialchars($advisor['advisor_email']); ?>
                        </div>
                        <a href="<?php echo url('student_dashboard', ['section' => 'messages']); ?>" class="btn-outline">Send Message</a>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-state">You have not been assigned an advisor yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Messages -->
        <div class="portal-section <?php echo $section === 'messages' ? 'is-active' : ''; ?>" id="section-messages">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Messages</h3>
                    <span class="card-meta"><?php echo count($messages); ?> total</span>
                </div>
                <?php if (empty($messages)): ?>
                    <div class="empty-state">No messages yet.</div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <div class="list-item">
                        <div class="list-icon-box"><?php echo strtoupper(substr($msg['sender_name'] ?? 'S', 0, 2)); ?></div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title"><?php echo htmlspecialchars($msg['title'] ?? ($msg['sender_name'] ?? 'System')); ?></span>
                                <span class="list-time"><?php echo date('M d', strtotime($msg['sent_at'])); ?></span>
                            </div>
                            <div class="list-body">
                                <div class="list-desc"><?php echo htmlspecialchars(substr($msg['message'], 0, 120)); ?><?php echo strlen($msg['message']) > 120 ? '…' : ''; ?></div>
                                <?php if (empty($msg['is_read'])): ?><span class="list-badge">1</span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($advisor): ?>
                <div class="dashboard-section compact-form">
                    <form action="<?php echo url('student_send_message'); ?>" method="POST" class="inline-form-grid">
                        <input type="text" name="title" placeholder="Subject" required>
                        <textarea name="message" rows="2" placeholder="Write a message…" required></textarea>
                        <button type="submit" class="btn btn-primary" style="width:auto;">Send message</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Appointments -->
        <div class="portal-section <?php echo $section === 'appointments' ? 'is-active' : ''; ?>" id="section-appointments">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Appointments</h3>
                    <span class="card-meta"><?php echo count($all_appointments); ?> total</span>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr><th>Date &amp; Time</th><th>Advisor</th><th>Status</th><th>Notes</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_appointments)): ?>
                            <tr><td colspan="4" class="empty-cell">No appointments yet.</td></tr>
                            <?php else: ?>
                            <?php foreach ($all_appointments as $appt): ?>
                            <tr>
                                <td><?php echo date('M d, Y g:i A', strtotime($appt['appointment_date'])); ?></td>
                                <td><?php echo htmlspecialchars($appt['advisor_name']); ?></td>
                                <td><span class="status-badge <?php echo $appt['status'] === 'approved' ? 'active' : ''; ?>"><?php echo ucfirst($appt['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($appt['notes'] ?? '—'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($advisor): ?>
                <div class="dashboard-section compact-form">
                    <form action="<?php echo url('student_request_appointment'); ?>" method="POST" class="inline-form-grid">
                        <input type="datetime-local" name="appointment_date" required>
                        <textarea name="notes" rows="2" placeholder="Notes (optional)"></textarea>
                        <button type="submit" class="btn btn-primary" style="width:auto;">Request appointment</button>
                    </form>
                </div>
                <?php else: ?>
                <div class="empty-state">Assign an advisor before requesting appointments.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notifications -->
        <div class="portal-section <?php echo $section === 'notifications' ? 'is-active' : ''; ?>" id="section-notifications">
            <?php include __DIR__ . '/../partials/portal-notifications.php'; ?>
        </div>

        <!-- Profile -->
        <div class="portal-section <?php echo $section === 'profile' ? 'is-active' : ''; ?>" id="section-profile">
            <?php include __DIR__ . '/../partials/portal-profile.php'; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
