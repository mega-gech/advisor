<?php
/** @var string $icon */
/** @var string $firstName */
/** @var string $userName */
/** @var string $roleLabel */
/** @var string $searchPlaceholder */
/** @var int $notifCount */
$notifCount = $notifCount ?? 0;
$searchAction = $searchAction ?? '';
$searchValue = $searchValue ?? '';
$searchSection = $searchSection ?? 'users';
$dashboardAction = $dashboardAction ?? 'registrar_dashboard';
$notifLink = $notifLink ?? '';
?>
<header class="topbar portal-topbar">
    <div class="topbar-left">
        <button type="button" class="menu-toggle" aria-label="Toggle menu">
            <svg class="icon" width="20" height="20"><use xlink:href="<?php echo $icon; ?>#menu"></use></svg>
        </button>
    </div>
    <div class="topbar-center">
        <?php if ($searchAction !== ''): ?>
        <form action="<?php echo htmlspecialchars($searchAction); ?>" method="GET" class="search-container search-form">
            <input type="hidden" name="action" value="<?php echo htmlspecialchars($dashboardAction); ?>">
            <input type="hidden" name="section" value="<?php echo htmlspecialchars($searchSection); ?>">
            <span class="search-icon">
                <svg class="icon" width="16" height="16"><use xlink:href="<?php echo $icon; ?>#search"></use></svg>
            </span>
            <input type="search" name="search" placeholder="<?php echo htmlspecialchars($searchPlaceholder); ?>" value="<?php echo htmlspecialchars($searchValue); ?>" aria-label="Search users">
            <button type="submit" class="search-submit-btn" aria-label="Search">Go</button>
        </form>
        <?php else: ?>
        <div class="search-container">
            <span class="search-icon">
                <svg class="icon" width="16" height="16"><use xlink:href="<?php echo $icon; ?>#search"></use></svg>
            </span>
            <input type="search" placeholder="<?php echo htmlspecialchars($searchPlaceholder); ?>" aria-label="Search">
        </div>
        <?php endif; ?>
    </div>
    <div class="topbar-right">
        <?php if ($notifLink !== ''): ?>
        <a href="<?php echo htmlspecialchars($notifLink); ?>" class="notification-bell" title="View notifications">
            <svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg>
            <?php if ($notifCount > 0): ?><span class="badge"><?php echo (int) $notifCount; ?></span><?php endif; ?>
        </a>
        <?php else: ?>
        <div class="notification-bell" title="Notifications">
            <svg class="icon" width="18" height="18"><use xlink:href="<?php echo $icon; ?>#bell"></use></svg>
            <?php if ($notifCount > 0): ?><span class="badge"><?php echo (int) $notifCount; ?></span><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=0b3c95&color=ffffff" alt="" class="avatar">
            <div class="user-info">
                <span class="user-name">Hi, <?php echo htmlspecialchars($firstName); ?></span>
                <span class="user-role"><?php echo htmlspecialchars($roleLabel); ?></span>
            </div>
            <span class="dropdown-icon">⌄</span>
        </div>
    </div>
</header>
