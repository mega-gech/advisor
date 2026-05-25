<?php

/**
 * Legacy entry point – redirects to the public web root.
 * Point your virtual host document root to /public for production.
 */

$query = $_SERVER['QUERY_STRING'] ?? '';
$target = 'public/index.php' . ($query !== '' ? '?' . $query : '');
header('Location: ' . $target, true, 302);
exit;
