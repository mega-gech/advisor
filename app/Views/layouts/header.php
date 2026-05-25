<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        $pageTitles = [
            'login' => 'Sign in',
            'register' => 'Create account',
        ];
        $actionKey = $_GET['action'] ?? 'home';
        echo htmlspecialchars(($pageTitles[$actionKey] ?? 'AdvisorHub') . ' – AAU Academic Advising');
    ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <?php if (in_array($_GET['action'] ?? '', ['login', 'register'], true)): ?>
    <link rel="stylesheet" href="<?php echo asset('css/auth.css'); ?>">
    <?php endif; ?>
</head>
<body<?php echo in_array($_GET['action'] ?? '', ['login', 'register'], true) ? ' class="auth-body"' : ''; ?>>

<?php
$currentAction = $_GET['action'] ?? 'home';
$isAuthPage = in_array($currentAction, ['login', 'register'], true);
$role = $_SESSION['user_role'] ?? '';
$isPortal = !$isAuthPage && in_array($role, ['student', 'advisor', 'registrar'], true);
$icon = asset('img/icons.svg');
$portalBadges = $portalBadges ?? ['messages' => 0, 'notifications' => 0, 'appointments' => 0, 'approvals' => 0];
$activeSection = $isAuthPage ? '' : portal_section();
?>

<?php if ($isAuthPage): ?>
<div class="auth-page">

<?php else: ?>
<div class="layout <?php echo htmlspecialchars($role); ?>-layout<?php echo $isPortal ? ' portal-layout' : ''; ?>">

<?php if (!$isPortal): ?>
<nav class="navbar">
    <a href="index.php" class="navbar-brand">
        <div class="navbar-logo-icon">🎓</div>
        <div>
            <div class="navbar-logo-text">AdvisorHub</div>
            <div class="navbar-logo-sub">AAU University System</div>
        </div>
    </a>
    <div class="navbar-right">
        <a href="index.php?action=logout" class="nav-item nav-logout">Logout</a>
    </div>
</nav>
<?php endif; ?>

<?php if ($isPortal):
    $portalTitles = ['student' => 'Student Portal', 'advisor' => 'Advisor Portal', 'registrar' => 'Registrar Portal'];
    $portalTitle = $portalTitles[$role] ?? 'Portal';
    $dashAction = $role . '_dashboard';
?>
<aside class="sidebar portal-sidebar">
    <div class="sidebar-header">
        <div class="brand-icon">🎓</div>
        <div class="brand-text">
            <span class="brand-title">AdvisorHub</span>
            <span class="brand-subtitle"><?php echo $portalTitle; ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($role === 'student'): ?>
            <a href="<?php echo url('student_dashboard'); ?>" class="nav-item <?php echo portal_nav_active('dashboard'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#home"></use></svg></span> Dashboard
            </a>
            <a href="<?php echo url('student_dashboard', ['section' => 'advisor']); ?>" class="nav-item <?php echo portal_nav_active('advisor'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#user"></use></svg></span> My Advisor
            </a>
            <a href="<?php echo url('student_dashboard', ['section' => 'messages']); ?>" class="nav-item <?php echo portal_nav_active('messages'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#message"></use></svg></span> Messages
                <?php if (($portalBadges['messages'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['messages']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('student_dashboard', ['section' => 'appointments']); ?>" class="nav-item <?php echo portal_nav_active('appointments'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#calendar"></use></svg></span> Appointments
            </a>
            <a href="<?php echo url('student_dashboard', ['section' => 'notifications']); ?>" class="nav-item <?php echo portal_nav_active('notifications'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg></span> Notifications
                <?php if (($portalBadges['notifications'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['notifications']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('student_dashboard', ['section' => 'profile']); ?>" class="nav-item <?php echo portal_nav_active('profile'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#user"></use></svg></span> My Profile
            </a>
            <a href="<?php echo url('logout'); ?>" class="nav-item nav-spaced"><span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#logout"></use></svg></span> Logout</a>

        <?php elseif ($role === 'advisor'): ?>
            <a href="<?php echo url('advisor_dashboard'); ?>" class="nav-item <?php echo portal_nav_active('dashboard'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#home"></use></svg></span> Dashboard
            </a>
            <a href="<?php echo url('advisor_dashboard', ['section' => 'students']); ?>" class="nav-item <?php echo portal_nav_active('students'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#users"></use></svg></span> My Students
            </a>
            <a href="<?php echo url('advisor_dashboard', ['section' => 'messages']); ?>" class="nav-item <?php echo portal_nav_active('messages'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#message"></use></svg></span> Messages
                <?php if (($portalBadges['messages'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['messages']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('advisor_dashboard', ['section' => 'appointments']); ?>" class="nav-item <?php echo portal_nav_active('appointments'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#calendar"></use></svg></span> Appointments
                <?php if (($portalBadges['appointments'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['appointments']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('advisor_dashboard', ['section' => 'notifications']); ?>" class="nav-item <?php echo portal_nav_active('notifications'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg></span> Notifications
                <?php if (($portalBadges['notifications'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['notifications']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('advisor_dashboard', ['section' => 'profile']); ?>" class="nav-item <?php echo portal_nav_active('profile'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#user"></use></svg></span> My Profile
            </a>
            <a href="<?php echo url('logout'); ?>" class="nav-item"><span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#logout"></use></svg></span> Logout</a>

        <?php else: /* registrar */
            $regSection = ($rs = trim($_GET['section'] ?? '')) !== '' ? $rs : 'dashboard';
            $regNavActive = static function (string $key) use ($regSection): string {
                return $regSection === $key ? 'active' : '';
            };
        ?>
            <a href="<?php echo url('registrar_dashboard'); ?>" class="nav-item <?php echo $regNavActive('dashboard'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#home"></use></svg></span> Dashboard
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'assignments']); ?>" class="nav-item <?php echo $regNavActive('assignments'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#link"></use></svg></span> Advisor Assignments
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'approvals']); ?>" class="nav-item <?php echo $regNavActive('approvals'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#document"></use></svg></span> Approvals
                <?php if (($portalBadges['approvals'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['approvals']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'reports']); ?>" class="nav-item <?php echo $regNavActive('reports'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#chart"></use></svg></span> Reports
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'users']); ?>" class="nav-item <?php echo $regNavActive('users'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#users"></use></svg></span> System Users
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'settings']); ?>" class="nav-item <?php echo $regNavActive('settings'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#key"></use></svg></span> Settings
            </a>
            <a href="<?php echo url('registrar_dashboard', ['section' => 'notifications']); ?>" class="nav-item <?php echo $regNavActive('notifications'); ?>">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg></span> Notifications
                <?php if (($portalBadges['notifications'] ?? 0) > 0): ?><span class="badge"><?php echo (int) $portalBadges['notifications']; ?></span><?php endif; ?>
            </a>
            <a href="<?php echo url('logout'); ?>" class="nav-item"><span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#logout"></use></svg></span> Logout</a>
        <?php endif; ?>
    </nav>
</aside>
<?php endif; ?>

<div class="main-content">
<?php if (!$isPortal): ?><div class="page-content"><?php endif; ?>

<?php if (!$isAuthPage): ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success portal-alert">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger portal-alert">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php endif; ?>

<?php endif; ?>
