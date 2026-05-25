<?php
$userName = $_SESSION['user_name'] ?? 'Registrar';
$firstName = first_name($userName);
$icon = asset('img/icons.svg');
$m = $metrics ?? [];
$portalBadges = $portalBadges ?? ['approvals' => 0, 'notifications' => 0];
$totalUsers = max(1, (int) ($m['total_users'] ?? 1));
$studentPct = round(((int) ($m['total_students'] ?? 0) / $totalUsers) * 100, 1);
$advisorPct = round(((int) ($m['total_advisors'] ?? 0) / $totalUsers) * 100, 1);
$registrarPct = round(((int) ($m['total_registrars'] ?? 1) / $totalUsers) * 100, 1);
$section = $section ?? 'dashboard';
$notifications = $notifications ?? [];
$profile = $registrar_profile ?? ['name' => $userName, 'email' => ''];

include __DIR__ . '/../layouts/header.php';
?>

<div class="registrar-dashboard portal-dashboard" data-active-section="<?php echo htmlspecialchars($section); ?>">
    <?php
    $roleLabel = 'Registrar';
    $topbarSearch = portal_topbar_search('registrar', $section);
    $notifCount = (int) ($portalBadges['notifications'] ?? 0);
    $searchAction = $topbarSearch['enabled'] ? url('registrar_dashboard') : '';
    $searchSection = $topbarSearch['section'];
    $searchPlaceholder = $topbarSearch['placeholder'];
    $searchAriaLabel = $topbarSearch['aria'];
    $searchValue = $search ?? '';
    $dashboardAction = 'registrar_dashboard';
    $notifLink = url('registrar_dashboard', ['section' => 'notifications']);
    include __DIR__ . '/../partials/portal-topbar.php';
    ?>

    <div class="page-content">
        <div class="welcome-section">
            <div>
                <h1 class="welcome-title">Welcome back, <?php echo htmlspecialchars($firstName); ?>! 👋</h1>
                <p class="welcome-subtitle"><?php
                    $subtitles = [
                        'dashboard' => "Here's an overview of system activities.",
                        'assignments' => 'Assign students to academic advisors.',
                        'approvals' => 'Review and approve pending student registrations.',
                        'reports' => 'System statistics and enrollment summary.',
                        'users' => 'Search, filter, and manage all user accounts.',
                        'settings' => 'Send broadcasts to advisors and view your account details.',
                        'notifications' => 'Recent items that need your attention.',
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
            <div class="metrics-grid metrics-grid-3">
                <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="metric-card stat-orange">
                    <div class="metric-icon-box orange"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#document"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $m['pending_approvals']; ?></span>
                        <span class="metric-label">Pending Approvals</span>
                        <span class="metric-link">Review →</span>
                    </div>
                </a>
                <a href="<?php echo url('registrar_dashboard', ['section' => 'assignments']); ?>" class="metric-card stat-green">
                    <div class="metric-icon-box green"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#link"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) ($m['total_assignments'] ?? 0); ?></span>
                        <span class="metric-label">Active Assignments</span>
                        <span class="metric-link">Manage →</span>
                    </div>
                </a>
                <a href="<?php echo url('registrar_dashboard', ['section' => 'users']); ?>" class="metric-card stat-purple">
                    <div class="metric-icon-box purple"><svg class="icon" width="22" height="22"><use xlink:href="<?php echo $icon; ?>#users"></use></svg></div>
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $totalUsers; ?></span>
                        <span class="metric-label">System Users</span>
                        <span class="metric-link">View all →</span>
                    </div>
                </a>
            </div>

            <div class="registrar-grid-main">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pending Approvals</h3>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="card-action">View all</a>
                    </div>
                    <div class="table-wrapper">
                        <table>
                            <thead><tr><th>Student</th><th>Email</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php if (empty($pending_students)): ?>
                                <tr><td colspan="3" class="empty-cell">No pending approvals.</td></tr>
                                <?php else: ?>
                                <?php foreach (array_slice($pending_students, 0, 3) as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td class="action-cell">
                                        <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="btn-text-success">Review</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card chart-card">
                    <div class="card-header"><h3 class="card-title">System Overview</h3></div>
                    <div class="donut-wrap">
                        <div class="donut-chart" style="--p1: <?php echo $studentPct; ?>%; --p2: <?php echo $studentPct + $advisorPct; ?>%;">
                            <div class="donut-center">
                                <strong><?php echo (int) $totalUsers; ?></strong>
                                <span>Total Users</span>
                            </div>
                        </div>
                        <ul class="donut-legend">
                            <li><span class="dot purple"></span> Students <strong><?php echo (int) $m['total_students']; ?></strong></li>
                            <li><span class="dot green"></span> Advisors <strong><?php echo (int) $m['total_advisors']; ?></strong></li>
                            <li><span class="dot cyan"></span> Registrars <strong><?php echo (int) ($m['total_registrars'] ?? 1); ?></strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card quick-actions-card">
                    <div class="card-header"><h3 class="card-title">Quick Actions</h3></div>
                    <div class="quick-actions-grid">
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'dashboard']); ?>#create-advisor" class="qa-tile qa-purple"><span class="qa-icon">+</span><span>Add Advisor</span></a>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'assignments']); ?>" class="qa-tile qa-green"><span class="qa-icon">🔗</span><span>Assign Advisors</span></a>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="qa-tile qa-orange"><span class="qa-icon">✓</span><span>Review Approvals</span></a>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'reports']); ?>" class="qa-tile qa-teal"><span class="qa-icon">📊</span><span>View Reports</span></a>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'users']); ?>" class="qa-tile qa-slate"><span class="qa-icon">👥</span><span>Manage Users</span></a>
                        <a href="<?php echo url('registrar_dashboard', ['section' => 'notifications']); ?>" class="qa-tile qa-blue"><span class="qa-icon">🔔</span><span>Notifications</span></a>
                    </div>
                </div>
            </div>

            <div class="card" id="create-advisor">
                <div class="card-header"><h3 class="card-title">Create Advisor Account</h3></div>
                <form action="<?php echo url('create_advisor'); ?>" method="POST" class="inline-form-grid card-form horizontal-form">
                    <input type="text" name="name" placeholder="Full name" required>
                    <input type="email" name="email" placeholder="email@aau.edu.et" required>
                    <input type="password" name="password" placeholder="Temporary password (min. 8)" required minlength="8">
                    <button type="submit" class="btn btn-primary" style="width:auto;">Create Advisor →</button>
                </form>
            </div>
        </div>

        <!-- Approvals -->
        <div class="registrar-section <?php echo $section === 'approvals' ? 'is-active' : ''; ?>" id="section-approvals">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Student Approvals</h3>
                    <span class="card-meta"><?php echo count($pending_students); ?> pending</span>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr><th>Student</th><th>Email</th><th>Student ID</th><th>Registered</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pending_students)): ?>
                            <tr><td colspan="5" class="empty-cell">No pending approvals.</td></tr>
                            <?php else: ?>
                            <?php foreach ($pending_students as $student): ?>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['name']); ?>&background=e2e8f0&color=334155" class="student-avatar-img" alt="">
                                        <span class="student-table-name"><?php echo htmlspecialchars($student['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_number'] ?? '—'); ?></td>
                                <td><?php echo !empty($student['created_at']) ? date('M d, Y', strtotime($student['created_at'])) : '—'; ?></td>
                                <td class="action-cell">
                                    <form action="<?php echo url('approve_student'); ?>" method="POST" class="inline-form">
                                        <input type="hidden" name="student_id" value="<?php echo (int) $student['id']; ?>">
                                        <button type="submit" class="btn-text-success">Approve</button>
                                    </form>
                                    <form action="<?php echo url('reject_student'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Reject this student?');">
                                        <input type="hidden" name="student_id" value="<?php echo (int) $student['id']; ?>">
                                        <button type="submit" class="btn-text-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Assignments -->
        <div class="registrar-section <?php echo $section === 'assignments' ? 'is-active' : ''; ?>" id="section-assignments">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Advisor Assignments</h3>
                    <span class="card-meta"><?php echo count($assignments); ?> total</span>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Student</th><th>Advisor</th><th>Date Assigned</th></tr></thead>
                        <tbody>
                            <?php if (empty($assignments)): ?>
                            <tr><td colspan="3" class="empty-cell">No assignments yet.</td></tr>
                            <?php else: ?>
                            <?php foreach ($assignments as $a): ?>
                            <tr>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($a['student_name']); ?>&background=e2e8f0&color=334155" class="student-avatar-img" alt="">
                                        <span class="student-table-name"><?php echo htmlspecialchars($a['student_name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($a['advisor_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($a['assigned_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <form action="<?php echo url('assign_student'); ?>" method="POST" class="inline-form-grid card-form">
                    <h4 class="form-mini-title">Assign student to advisor</h4>
                    <select name="student_id" required>
                        <option value="">Select student</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?php echo (int) $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="advisor_id" required>
                        <option value="">Select advisor</option>
                        <?php foreach ($advisors as $a): ?>
                        <option value="<?php echo (int) $a['id']; ?>"><?php echo htmlspecialchars($a['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary" style="width:auto;">Assign →</button>
                </form>
            </div>
        </div>

        <!-- System users -->
        <div class="registrar-section <?php echo $section === 'users' ? 'is-active' : ''; ?>" id="section-users">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Manage Users</h3></div>
                <form action="<?php echo url('registrar_dashboard'); ?>" method="GET" class="filter-bar">
                    <input type="hidden" name="action" value="registrar_dashboard">
                    <input type="hidden" name="section" value="users">
                    <input type="text" name="search" placeholder="Search name or email…" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="role">
                        <option value="">All Roles</option>
                        <option value="student" <?php echo $role_filter === 'student' ? 'selected' : ''; ?>>Students</option>
                        <option value="advisor" <?php echo $role_filter === 'advisor' ? 'selected' : ''; ?>>Advisors</option>
                        <option value="registrar" <?php echo $role_filter === 'registrar' ? 'selected' : ''; ?>>Registrars</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Apply filters</button>
                </form>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Name / Email</th><th>Role / Status</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                            <tr><td colspan="3" class="empty-cell">No users found.</td></tr>
                            <?php else: ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="td-name"><?php echo htmlspecialchars($user['name']); ?></div>
                                    <div class="td-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                </td>
                                <td>
                                    <span class="role-badge <?php echo htmlspecialchars($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></span>
                                    <span class="status-muted">(<?php echo htmlspecialchars($user['status']); ?>)</span>
                                </td>
                                <td>
                                    <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>
                                    <form action="<?php echo url('delete_user'); ?>" method="POST" class="inline-form" onsubmit="return confirm('Delete this user permanently?');">
                                        <input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                        <button type="submit" class="btn btn-danger" style="padding:6px 12px;font-size:.8rem;">Delete</button>
                                    </form>
                                    <?php else: ?>
                                    <span class="status-muted">Current account</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reports -->
        <div class="registrar-section <?php echo $section === 'reports' ? 'is-active' : ''; ?>" id="section-reports">
            <div class="metrics-grid metrics-grid-4">
                <div class="metric-card stat-purple">
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $m['total_students']; ?></span>
                        <span class="metric-label">Total Students</span>
                    </div>
                </div>
                <div class="metric-card stat-green">
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) ($m['approved_students'] ?? 0); ?></span>
                        <span class="metric-label">Approved Students</span>
                    </div>
                </div>
                <div class="metric-card stat-orange">
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $m['pending_approvals']; ?></span>
                        <span class="metric-label">Pending Approval</span>
                    </div>
                </div>
                <div class="metric-card stat-blue">
                    <div class="metric-info">
                        <span class="metric-value"><?php echo (int) $m['total_advisors']; ?></span>
                        <span class="metric-label">Total Advisors</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Enrollment &amp; Assignment Report</h3>
                    <button type="button" class="btn btn-secondary" onclick="window.print()" style="width:auto;">Print report</button>
                </div>
                <div class="table-wrapper">
                    <table class="report-table">
                        <tbody>
                            <tr><th>Total system users</th><td><?php echo (int) $totalUsers; ?></td></tr>
                            <tr><th>Students (all statuses)</th><td><?php echo (int) $m['total_students']; ?></td></tr>
                            <tr><th>Approved students</th><td><?php echo (int) ($m['approved_students'] ?? 0); ?></td></tr>
                            <tr><th>Pending approvals</th><td><?php echo (int) $m['pending_approvals']; ?></td></tr>
                            <tr><th>Rejected students</th><td><?php echo (int) ($m['rejected_students'] ?? 0); ?></td></tr>
                            <tr><th>Advisors</th><td><?php echo (int) $m['total_advisors']; ?></td></tr>
                            <tr><th>Registrars</th><td><?php echo (int) ($m['total_registrars'] ?? 1); ?></td></tr>
                            <tr><th>Active advisor assignments</th><td><?php echo (int) ($m['total_assignments'] ?? 0); ?></td></tr>
                            <tr><th>Report generated</th><td><?php echo date('M d, Y H:i'); ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="registrar-section <?php echo $section === 'settings' ? 'is-active' : ''; ?>" id="section-settings">
            <div class="card settings-card">
                <div class="card-header"><h3 class="card-title">Registrar Settings</h3></div>
                <dl class="settings-list">
                    <div class="settings-row">
                        <dt>Full name</dt>
                        <dd><?php echo htmlspecialchars($profile['name']); ?></dd>
                    </div>
                    <div class="settings-row">
                        <dt>Email</dt>
                        <dd><?php echo htmlspecialchars($profile['email']); ?></dd>
                    </div>
                    <div class="settings-row">
                        <dt>Role</dt>
                        <dd><span class="role-badge registrar">registrar</span></dd>
                    </div>
                    <div class="settings-row">
                        <dt>Institution</dt>
                        <dd>Addis Ababa University — AdvisorHub</dd>
                    </div>
                </dl>
                <div class="settings-actions">
                    <a href="<?php echo url('registrar_dashboard', ['section' => 'users']); ?>" class="btn btn-primary" style="width:auto;">Manage users</a>
                    <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="btn btn-secondary" style="width:auto;">Pending approvals</a>
                </div>
            </div>
            <div class="card" style="margin-top:20px;">
                <div class="card-header"><h3 class="card-title">Broadcast to advisors</h3></div>
                <p class="card-meta" style="padding:0 20px 12px;">Send an announcement visible to all advisors on their dashboard.</p>
                <form action="<?php echo url('send_broadcast'); ?>" method="POST" class="inline-form-grid card-form">
                    <input type="text" name="title" placeholder="Announcement title" required>
                    <textarea name="message" rows="3" placeholder="Message for all advisors" required></textarea>
                    <button type="submit" class="btn btn-primary" style="width:auto;">Send broadcast →</button>
                </form>
            </div>
        </div>

        <!-- Notifications -->
        <div class="registrar-section <?php echo $section === 'notifications' ? 'is-active' : ''; ?>" id="section-notifications">
            <?php include __DIR__ . '/../partials/portal-notifications.php'; ?>
        </div>
    </div>
</div>

<?php if ($section === 'dashboard'): ?>
<script>
document.querySelector('a[href*="#create-advisor"]')?.addEventListener('click', function () {
    setTimeout(function () {
        document.getElementById('create-advisor')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
