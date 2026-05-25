document.addEventListener('DOMContentLoaded', function () {
    var layout = document.querySelector('.portal-layout');
    if (!layout) return;

    var toggle = document.querySelector('.menu-toggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            layout.classList.toggle('sidebar-collapsed');
        });
    }
});
