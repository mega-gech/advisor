<?php
$isAuthPage = in_array($currentAction ?? '', ['login', 'register']) || !isset($_SESSION['user_id']);
?>

<?php if ($isAuthPage): ?>
</div><!-- /.auth-page -->
<?php else: ?>
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->
</div><!-- /.layout -->
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var toggle = document.querySelector('.menu-toggle');
    var layout = document.querySelector('.layout');
    if(toggle && layout){
        toggle.addEventListener('click', function(){
            layout.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>

</body>
</html>
