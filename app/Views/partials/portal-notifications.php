<?php
/** @var array $notifications */
$notifications = $notifications ?? [];
$dashAction = ($_SESSION['user_role'] ?? 'student') . '_dashboard';
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notifications</h3>
        <span class="card-meta"><?php echo count($notifications); ?> items</span>
    </div>
    <?php if (empty($notifications)): ?>
        <div class="empty-state">You're all caught up — no new notifications.</div>
    <?php else: ?>
    <div class="notification-list">
        <?php foreach ($notifications as $item): ?>
        <?php $targetSection = $item['section'] ?? $item['link_section'] ?? 'dashboard'; ?>
        <a href="<?php echo url($dashAction, ['section' => $targetSection]); ?>" class="notification-item notification-<?php echo htmlspecialchars($item['type']); ?>">
            <span class="notification-dot" aria-hidden="true"></span>
            <div class="notification-body">
                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                <p><?php echo htmlspecialchars($item['body']); ?></p>
                <time><?php echo date('M d, Y g:i A', strtotime($item['time'])); ?></time>
            </div>
            <span class="notification-arrow">→</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
