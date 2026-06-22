<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship & Award Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css?v=<?= time() ?>">
    <style>
        /* Inline fallback */
        body { font-family: 'Lato', 'Segoe UI', sans-serif; margin: 0; background: #fdf6f8; color: #4a2c35; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="<?= BASE_URL ?>">🌸 Scholarship Manager</a>
    </div>
    <div class="navbar-menu">
        <?php if (Session::isLoggedIn()): ?>
            <?php if (Session::isAdmin()): ?>
                <a href="<?= base_url('index.php?url=admin/dashboard') ?>">Dashboard</a>
                <a href="<?= base_url('index.php?url=admin/users') ?>">Người dùng</a>
                <a href="<?= base_url('index.php?url=admin/programs') ?>">Học bổng</a>
                <a href="<?= base_url('index.php?url=admin/applications') ?>">Hồ sơ</a>
                <a href="<?= base_url('index.php?url=admin/rankings') ?>">Xếp hạng</a>
                <a href="<?= base_url('index.php?url=admin/disbursements') ?>">Chi trả</a>
                <a href="<?= base_url('index.php?url=admin/certificates') ?>">Chứng nhận</a>
            <?php else: ?>
                <a href="<?= base_url('index.php?url=student/dashboard') ?>">Dashboard</a>
                <a href="<?= base_url('index.php?url=student/applications') ?>">Hồ sơ của tôi</a>
                <a href="<?= base_url('index.php?url=student/activities') ?>">Hoạt động</a>
            <?php endif; ?>
            <span class="navbar-user">👤 <?= e(Session::get('username')) ?></span>
            <a href="<?= base_url('index.php?url=auth/logout') ?>" class="btn-logout">Đăng xuất</a>
        <?php else: ?>
            <a href="<?= base_url('index.php?url=auth/login') ?>">Đăng nhập</a>
            <a href="<?= base_url('index.php?url=auth/register') ?>">Đăng ký</a>
            <a href="<?= base_url('index.php?url=admin/verify') ?>">Xác minh chứng nhận</a>
        <?php endif; ?>
    </div>
</nav>

<main class="container">
