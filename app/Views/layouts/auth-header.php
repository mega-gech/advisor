<?php
$authPage = $_GET['action'] ?? 'login';
$pageTitles = ['login' => 'Sign in', 'register' => 'Create account'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(($pageTitles[$authPage] ?? 'AdvisorHub') . ' – AAU Academic Advising'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_version('css/auth.css'); ?>">
</head>
<body class="auth-body">
<div class="auth-page">
