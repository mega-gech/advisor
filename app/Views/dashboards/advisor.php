<?php
$userName = $_SESSION['user_name'] ?? 'Advisor';
$firstName = first_name($userName);
$icon = asset('img/icons.svg');
$section = $section ?? 'dashboard';
$portalBadges = $portalBadges ?? ['messages' => 0, 'appointments' => 0, 'notifications' => 0];
$profile = $profile ?? ['name' => $userName, 'email' => '', 'role' => 'advisor'];
$search = $search ?? '';
$broadcasts = $broadcasts ?? [];
$all_appointments = $all_appointments ?? [];

include __DIR__ . '/../layouts/header.php';
?>

<div class="advisor-dashboard portal-dashboard" data-active-section="<?php echo htmlspecialchars($section); ?>">
    <?php
    $roleLabel = 'Academic Advisor';
    $topbarSearch = portal_topbar_search('advisor', $section);
    $notifCount = (int) ($portalBadges['notifications'] ?? 0);
    $searchAction = $topbarSearch['enabled'] ? url('advisor_dashboard') : '';
    $searchSection = $topbarSearch['section'];
    $searchPlaceholder = $topbarSearch['placeholder'];
    $searchAriaLabel = $topbarSearch['aria'];
    $dashboardAction = 'advisor_dashboard';
    $searchValue = $search;
    $notifLink = url('advisor_dashboard', ['section' => 'notifications']);
    include __DIR__ . '/../partials/portal-topbar.php';
    ?>

    <div class="page-content">
        <div class="welcome-section">
            <div>
                <h1 class="welcome-title">Welcome back, <?php echo htmlspecialchars($firstName); ?>! 👋</h1>
                <p class="welcome-subtitle"><?php
                    $subtitles = [
                        'dashboard' => "Here's what's happening with your advisees today.",
                        'students' => 'View and search your assigned students.',
                        'messages' => 'Read and send messages to your advisees.',
                        'appointments' => 'Review and manage appointment requests.',
                        'notifications' => 'Recent items that need your attention.',
                        'profile' => 'Your advisor account details.',
                    ];
                    echo htmlspecialchars($subtitles[$section] ?? $subtitles['dashboard']);
                ?></p>
            </div>
            <div class="date-picker">
                <span class="date-picker-icon"><svg class="icon" width="16" height="16"><use xlink:href="<?php echo $icon; ?>#calendar"></use></svg></span>
                <?php echo date('M d, Y | l'); ?>
            </div>
        </div>

        <!-- Dashboard overview -->
        <div class="registrar-section <?php echo $section === 'dashboard' ? 'is-active' : ''; ?>" id="section-dashboard">
            <div class="metrics-grid">
                <a href="<?php echo url('advisor_dashboard', ['section' => 'students']); ?>" class="metric-card blue">
                    <div class="metric-icon-box blue"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#users"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $student_count; ?></span>
                        <span class="metric-label">Assigned Students</span>
                        <span class="metric-link">View All Students →</span>
                    </div>
                </a>
                <a href="<?php echo url('advisor_dashboard', ['section' => 'messages']); ?>" class="metric-card green">
                    <div class="metric-icon-box green"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#message"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $unread_messages; ?></span>
                        <span class="metric-label">Unread Messages</span>
                        <span class="metric-link">View Messages →</span>
                    </div>
                </a>
                <a href="<?php echo url('advisor_dashboard', ['section' => 'appointments']); ?>" class="metric-card orange">
                    <div class="metric-icon-box orange"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#calendar"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo count($appointments ?? []); ?></span>
                        <span class="metric-label">Upcoming Appointments</span>
                        <span class="metric-link">View appointments →</span>
                    </div>
                </a>
                <a href="<?php echo url('advisor_dashboard', ['section' => 'appointments']); ?>" class="metric-card purple">
                    <div class="metric-icon-box purple"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#folder"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $pending_appointments; ?></span>
                        <span class="metric-label">Pending Requests</span>
                        <span class="metric-link">View Requests →</span>
                    </div>
                </a>
            </div>

            <div class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Advisees</h3>
                        <a href="<?php echo url('advisor_dashboard', ['section' => 'students']); ?>" class="card-action">View all</a>
                    </div>
                    <div class="table-wrapper">
                        <?php if (empty($students)): ?>
                            <div class="empty-state">No students assigned yet.</div>
                        <?php else: ?>
                        <table>
                            <thead><tr><th>Student</th><th>Email</th><th>Student ID</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($students, 0, 4) as $student): ?>
                                <tr>
                                    <td>
                                        <div class="student-profile-cell">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['student_name']); ?>&background=e2e8f0&color=334155" class="student-avatar-img" alt="">
                                            <span class="student-table-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['student_email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_number'] ?? '—'); ?></td>
                                    <td><span class="status-badge active">Active</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Messages</h3>
                        <a href="<?php echo url('advisor_dashboard', ['section' => 'messages']); ?>" class="card-action">View all</a>
                    </div>
                    <?php if (empty($messages)): ?>
                        <div class="empty-state">No messages yet.</div>
                    <?php else: ?>
                        <?php foreach (array_slice($messages, 0, 4) as $msg): ?>
                        <div class="list-item">
                            <div class="list-icon-box purple"><?php echo strtoupper(substr($msg['sender_name'] ?? 'S', 0, 2)); ?></div>
                            <div class="list-content">
                                <div class="list-header">
                                    <span class="list-title"><?php echo htmlspecialchars($msg['title']); ?></span>
                                    <span class="list-time"><?php echo date('M d', strtotime($msg['sent_at'])); ?></span>
                                </div>
                                <div class="list-desc"><?php echo htmlspecialchars(substr($msg['message'], 0, 50)); ?>…</div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Appointments</h3>
                        <a href="<?php echo url('advisor_dashboard', ['section' => 'appointments']); ?>" class="card-action">View all</a>
                    </div>
                    <?php if (empty($appointments)): ?>
                        <div class="empty-state">No upcoming appointments.</div>
                    <?php else: ?>
                        <?php foreach (array_slice($appointments, 0, 3) as $appt): ?>
                        <div class="appointment-card-item">
                            <div class="calendar-badge">
                                <span class="month"><?php echo strtoupper(date('M', strtotime($appt['appointment_date']))); ?></span>
                                <span class="day"><?php echo date('d', strtotime($appt['appointment_date'])); ?></span>
                            </div>
                            <div class="appt-details">
                                <span class="appt-title">Meeting with <?php echo htmlspecialchars($appt['student_name']); ?></span>
                                <div class="appt-meta">
                                    <?php echo date('g:i A', strtotime($appt['appointment_date'])); ?>
                                    | <span class="appt-status <?php echo $appt['status'] === 'approved' ? 'confirmed' : 'pending'; ?>"><?php echo ucfirst($appt['status']); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Announcements</h3></div>
                    <?php if (empty($broadcasts)): ?>
                        <div class="empty-state">No announcements at this time.</div>
                    <?php else: ?>
                        <?php foreach (array_slice($broadcasts, 0, 3) as $ann): ?>
                        <div class="announcement-item">
                            <div class="announcement-left">
                                <div class="list-icon-box blue"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg></div>
                                <div class="announcement-details">
                                    <span class="announcement-title"><?php echo htmlspecialchars($ann['title']); ?></span>
                                    <span class="announcement-desc"><?php echo htmlspecialchars(substr($ann['message'], 0, 60)); ?>…</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Students -->
        <div class="registrar-section <?php echo $section === 'students' ? 'is-active' : ''; ?>" id="section-students">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Advisees</h3>
                    <span class="card-meta"><?php echo count($students); ?> student<?php echo count($students) === 1 ? '' : 's'; ?><?php if ($search !== ''): ?> · filtered by “<?php echo htmlspecialchars($search); ?>”<?php endif; ?></span>
                </div>
                <div class="table-wrapper">
                    <?php if (empty($students)): ?>
                        <div class="empty-state"><?php echo $search !== '' ? 'No students match your search.' : 'No students assigned yet.'; ?></div>
                    <?php else: ?>
                    <table>
                        <thead><tr><th>Student</th><th>Email</th><th>Student ID</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['student_name']); ?>&background=e2e8f0&color=334155" class="student-avatar-img" alt="">
                                        <span class="student-table-name"><?php echo htmlspecialchars($student['student_name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($student['student_email']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_number'] ?? '—'); ?></td>
                                <td><span class="status-badge active">Active</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="registrar-section <?php echo $section === 'messages' ? 'is-active' : ''; ?>" id="section-messages">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Messages</h3>
                    <span class="card-meta"><?php echo count($messages ?? []); ?> total · <?php echo (int) $unread_messages; ?> unread</span>
                </div>
                <?php if (empty($messages)): ?>
                    <div class="empty-state">No messages yet.</div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <div class="list-item">
                        <div class="list-icon-box purple"><?php echo strtoupper(substr($msg['sender_name'] ?? 'S', 0, 2)); ?></div>
                        <div class="list-content">
                            <div class="list-header">
                                <span class="list-title"><?php echo htmlspecialchars($msg['title']); ?></span>
                                <span class="list-time"><?php echo date('M d, Y g:i A', strtotime($msg['sent_at'])); ?></span>
                            </div>
                            <div class="list-body">
                                <div class="list-desc"><?php echo htmlspecialchars($msg['message']); ?></div>
                                <?php if (empty($msg['is_read'])): ?><span class="list-badge">New</span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($students)): ?>
                <form action="<?php echo url('advisor_send_message'); ?>" method="POST" class="inline-form-grid card-form">
                    <h4 class="form-mini-title">Send message to a student</h4>
                    <select name="student_id" required>
                        <option value="">Select student</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?php echo (int) $s['student_id']; ?>"><?php echo htmlspecialchars($s['student_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="title" placeholder="Subject" required>
                    <textarea name="message" rows="3" placeholder="Write your message…" required></textarea>
                    <button type="submit" class="btn btn-primary" style="width:auto;">Send message</button>
                </form>
                <?php else: ?>
                <div class="empty-state compact">Assign students before you can send messages.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Appointments -->
        <div class="registrar-section <?php echo $section === 'appointments' ? 'is-active' : ''; ?>" id="section-appointments">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Appointments</h3>
                    <span class="card-meta"><?php echo count($all_appointments); ?> total · <?php echo (int) $pending_appointments; ?> pending</span>
                </div>
                <?php if (empty($all_appointments)): ?>
                    <div class="empty-state">No appointments yet.</div>
                <?php else: ?>
                    <?php foreach ($all_appointments as $appt): ?>
                    <div class="appointment-card-item">
                        <div class="calendar-badge">
                            <span class="month"><?php echo strtoupper(date('M', strtotime($appt['appointment_date']))); ?></span>
                            <span class="day"><?php echo date('d', strtotime($appt['appointment_date'])); ?></span>
                        </div>
                        <div class="appt-details">
                            <span class="appt-title">Meeting with <?php echo htmlspecialchars($appt['student_name']); ?></span>
                            <div class="appt-meta">
                                <?php echo date('M d, Y g:i A', strtotime($appt['appointment_date'])); ?>
                                | <span class="appt-status <?php echo $appt['status'] === 'approved' ? 'confirmed' : ($appt['status'] === 'rejected' ? 'rejected' : 'pending'); ?>"><?php echo ucfirst($appt['status']); ?></span>
                            </div>
                            <?php if (!empty($appt['notes'])): ?>
                            <div class="appt-notes"><?php echo htmlspecialchars($appt['notes']); ?></div>
                            <?php endif; ?>
                            <?php if ($appt['status'] === 'pending'): ?>
                            <div class="appt-actions">
                                <form method="POST" action="<?php echo url('advisor_update_appointment'); ?>">
                                    <input type="hidden" name="appointment_id" value="<?php echo (int) $appt['id']; ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn-text-success">Approve</button>
                                </form>
                                <form method="POST" action="<?php echo url('advisor_update_appointment'); ?>">
                                    <input type="hidden" name="appointment_id" value="<?php echo (int) $appt['id']; ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn-text-danger">Decline</button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notifications -->
        <div class="registrar-section <?php echo $section === 'notifications' ? 'is-active' : ''; ?>" id="section-notifications">
            <?php include __DIR__ . '/../partials/portal-notifications.php'; ?>
        </div>

        <!-- Profile -->
        <div class="registrar-section <?php echo $section === 'profile' ? 'is-active' : ''; ?>" id="section-profile">
            <?php include __DIR__ . '/../partials/portal-profile.php'; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
