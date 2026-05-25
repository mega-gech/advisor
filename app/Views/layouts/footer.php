<?php
$isAuthPage = in_array($currentAction ?? '', ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php
$isAuthPage = in_array($currentAction ?? '', ['login', 'register']) || !isset($_SESSION['user_id']);
$isPortal = !$isAuthPage && in_array($_SESSION['user_role'] ?? '', ['student', 'advisor', 'registrar'], true);
if (!isset($icon) && function_exists('asset')) {
    $icon = asset('img/icons.svg');
}
?>
<?php if ($isAuthPage): ?>
</div><!-- /.auth-page -->
<?php else: ?>
    <?php if ($isPortal): ?>
        <?php include __DIR__ . '/../partials/portal-footer.php'; ?>
    <?php else: ?>
        </div><!-- /.page-content -->
    <?php endif; ?>
</div><!-- /.main-content -->
</div><!-- /.layout -->
<?php endif; ?>

<?php if (!empty($isPortal)): ?>
<script src="<?php echo asset('js/portal.js'); ?>"></script>
<?php endif; ?>

</body>
</html>
