<?php
/** @var array $profile */
$profile = $profile ?? ['name' => '', 'email' => '', 'role' => ''];
?>
<div class="card settings-card">
    <div class="card-header"><h3 class="card-title">My Profile</h3></div>
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
            <dd><span class="role-badge <?php echo htmlspecialchars($profile['role']); ?>"><?php echo htmlspecialchars($profile['role']); ?></span></dd>
        </div>
        <div class="settings-row">
            <dt>Institution</dt>
            <dd>Addis Ababa University — AdvisorHub</dd>
        </div>
    </dl>
</div>
