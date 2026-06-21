<div class="page-header">
    <h1>Admin Dashboard</h1>
    <p class="text-muted">Tổng quan hệ thống quản lý học bổng</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= e($stats['users']) ?></div>
        <div class="stat-label">Tài khoản</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= e($stats['students']) ?></div>
        <div class="stat-label">Sinh viên</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= e($stats['programs']) ?></div>
        <div class="stat-label">Chương trình học bổng</div>
    </div>
    <div class="stat-card stat-card--highlight">
        <div class="stat-number"><?= e($stats['applications']) ?></div>
        <div class="stat-label">Hồ sơ ứng tuyển</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= e($stats['pending_apps']) ?></div>
        <div class="stat-label">Hồ sơ chờ xét</div>
    </div>
    <div class="stat-card stat-card--success">
        <div class="stat-number"><?= e($stats['awarded']) ?></div>
        <div class="stat-label">Học bổng đã cấp</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= e($stats['certificates']) ?></div>
        <div class="stat-label">Chứng nhận</div>
    </div>
    <div class="stat-card stat-card--money">
        <div class="stat-number"><?= formatCurrency($stats['total_paid']) ?></div>
        <div class="stat-label">Tổng đã chi trả</div>
    </div>
</div>

<div class="quick-links">
    <h3>Truy cập nhanh</h3>
    <div class="link-grid">
        <a href="<?= base_url('index.php?url=admin/applications') ?>" class="quick-link">
            📋 Xét duyệt hồ sơ
        </a>
        <a href="<?= base_url('index.php?url=admin/rankings') ?>" class="quick-link">
            🏆 Xếp hạng
        </a>
        <a href="<?= base_url('index.php?url=admin/disbursements') ?>" class="quick-link">
            💸 Chi trả học bổng
        </a>
        <a href="<?= base_url('index.php?url=admin/certificates') ?>" class="quick-link">
            📜 Cấp chứng nhận
        </a>
        <a href="<?= base_url('index.php?url=admin/programs') ?>" class="quick-link">
            🎓 Quản lý chương trình
        </a>
        <a href="<?= base_url('index.php?url=admin/users') ?>" class="quick-link">
            👥 Quản lý người dùng
        </a>
    </div>
</div>
