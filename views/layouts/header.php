<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdvisorHub – AAU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<?php
$currentAction = isset($_GET['action']) ? $_GET['action'] : 'home';
$isAuthPage = in_array($currentAction, ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php if ($isAuthPage): ?>
<!-- ======= AUTH LAYOUT ======= -->
<div class="auth-page">

<?php else: ?>
<!-- ======= DASHBOARD LAYOUT ======= -->
<div class="layout">
    <!-- Top Navbar -->
    <nav class="navbar">

        <!-- Brand -->
        <a href="index.php" class="navbar-brand">
            <div class="navbar-logo-icon"><svg class="icon icon-brand" width="28" height="28" aria-hidden="true"><use xlink:href="/public/img/icons.svg#brand"></use></svg></div>
            <div>
                <div class="navbar-logo-text">AdvisorHub</div>
                <div class="navbar-logo-sub">AAU University System</div>
            </div>
        </a>

        <!-- Nav Links -->
        <div class="navbar-nav">
            <?php if(isset($_SESSION['user_role'])): ?>
                <?php if($_SESSION['user_role'] === 'registrar'): ?>
                    <a href="index.php?action=registrar_dashboard" class="nav-item <?php echo ($currentAction==='registrar_dashboard')?'active':''; ?>">
                        <span class="nav-icon"><svg class="icon" width="18" height="18" aria-hidden="true"><use xlink:href="/public/img/icons.svg#home"></use></svg></span> <span>Dashboard</span>
                    </a>
                    <a href="index.php?action=registrar_dashboard" class="nav-item">
                        <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#users"></use></svg></span> <span>Users</span>
                    </a>
                    <a href="index.php?action=registrar_dashboard" class="nav-item">
                        <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#link"></use></svg></span> <span>Assignments</span>
                    </a>

                <?php elseif($_SESSION['user_role'] === 'advisor'): ?>
                    <a href="index.php?action=advisor_dashboard" class="nav-item <?php echo ($currentAction==='advisor_dashboard')?'active':''; ?>">
                        <span class="nav-icon"><svg class="icon" width="18" height="18" aria-hidden="true"><use xlink:href="/public/img/icons.svg#home"></use></svg></span> <span>Dashboard</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#user"></use></svg></span> <span>My Students</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#message"></use></svg></span> <span>Messages</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg></span> <span>Notifications</span>
                    </a>

                <?php elseif($_SESSION['user_role'] === 'student'): ?>
                    <a href="index.php?action=student_dashboard" class="nav-item <?php echo ($currentAction==='student_dashboard')?'active':''; ?>">
                        <span class="nav-icon"><svg class="icon" width="18" height="18" aria-hidden="true"><use xlink:href="/public/img/icons.svg#home"></use></svg></span> <span>Dashboard</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">👤</span> <span>My Advisor</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">💬</span> <span>Messages</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">📅</span> <span>Appointments</span>
                    </a>
                    <a href="#" class="nav-item">
                        <span class="nav-icon">🔔</span> <span>Notifications</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Right: user info + logout -->
        <div class="navbar-right">
            <?php if(isset($_SESSION['user_id'])): 
                $initials = strtoupper(substr($_SESSION['user_name'], 0, 1));
            ?>
                <div class="navbar-avatar"><?php echo $initials; ?></div>
                <span class="navbar-username"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <span class="topbar-badge"><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                <a href="index.php?action=logout" class="nav-item nav-logout">
                    <span class="nav-icon">🚪</span> <span>Logout</span>
                </a>
            <?php else: ?>
                <a href="index.php?action=login" class="nav-item">
                    <span class="nav-icon">🔑</span> <span>Login</span>
                </a>
            <?php endif; ?>
        </div>

    </nav><!-- /.navbar -->

    <!-- Left Sidebar (Student only) -->
    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-icon">🎓</div>
            <div class="brand-text">
                <span class="brand-title">AdvisorHub</span>
                <span class="brand-subtitle">Student Portal</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="index.php?action=student_dashboard" class="nav-item active">
                <span class="nav-icon"><svg class="icon" width="18" height="18" aria-hidden="true"><use xlink:href="/public/img/icons.svg#home"></use></svg></span>
                Dashboard
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#user"></use></svg></span>
                My Advisor
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#message"></use></svg></span>
                Messages
                <span class="badge">3</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#bell"></use></svg></span>
                Notifications
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></span>
                Appointments
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#user"></use></svg></span>
                My Profile
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#document"></use></svg></span>
                Documents
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#folder"></use></svg></span>
                Resources
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#calendar"></use></svg></span>
                Calendar
            </a>
            <a href="#" class="nav-item" style="margin-top: 12px;">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#help"></use></svg></span>
                Help & Support
            </a>
            <a href="index.php?action=logout" class="nav-item">
                <span class="nav-icon"><svg class="icon" width="18" height="18"><use xlink:href="/public/img/icons.svg#logout"></use></svg></span>
                Logout
            </a>
        </nav>

        <div class="help-card">
            <div class="help-card-icon">👨‍🎓</div>
            <div class="help-card-title">Need Help?</div>
            <div class="help-card-text">Contact your advisor or registrar for any support.</div>
            <button class="btn-support">Get Support</button>
        </div>
    </aside>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-content">

<?php endif; ?>

<!-- Flash Messages -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
