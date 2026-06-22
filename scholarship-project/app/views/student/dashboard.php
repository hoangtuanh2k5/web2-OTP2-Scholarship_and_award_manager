<div class="page-header">
    <h1>Xin chào, <?= e($student['full_name']) ?> 👋</h1>
    <p class="text-muted">Mã SV: <?= e($student['student_code']) ?> | GPA: <strong><?= e($student['gpa']) ?></strong> | Hoạt động: <strong><?= count($activities) ?></strong></p>
</div>

<?php $flash = Session::getFlash('success'); if ($flash): ?>
    <div class="alert alert-success"><?= e($flash) ?></div>
<?php endif; ?>
<?php $flash = Session::getFlash('error'); if ($flash): ?>
    <div class="alert alert-danger"><?= $flash ?></div>
<?php endif; ?>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= count($applications) ?></div>
        <div class="stat-label">Hồ sơ đã nộp</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count(array_filter($applications, fn($a) => $a['status'] === 'awarded')) ?></div>
        <div class="stat-label">Học bổng nhận được</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count($activities) ?></div>
        <div class="stat-label">Hoạt động tham gia</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count($programs) ?></div>
        <div class="stat-label">Học bổng đang mở</div>
    </div>
</div>

<!-- Available Programs -->
<section class="section">
    <h2>Học bổng đang mở đăng ký</h2>
    <?php if (empty($programs)): ?>
        <p class="text-muted">Hiện không có học bổng nào đang mở.</p>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($programs as $prog): ?>
                <?php
                    // Check if already applied
                    $applied = false;
                    foreach ($applications as $app) {
                        if ((int)$app['scholarship_program_id'] === (int)$prog['id']) {
                            $applied = true; break;
                        }
                    }
                ?>
                <div class="program-card">
                    <h3><?= e($prog['title']) ?></h3>
                    <p class="text-muted"><?= e($prog['description'] ?? '') ?></p>
                    <div class="program-meta">
                        <span>💰 <?= formatCurrency((float)$prog['amount_per_student']) ?></span>
                        <span>👥 <?= e($prog['max_number_of_awards']) ?> suất</span>
                        <?php if ($prog['application_deadline']): ?>
                            <span>📅 Hạn: <?= formatDate($prog['application_deadline']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($applied): ?>
                        <span class="badge badge-success">Đã nộp hồ sơ</span>
                    <?php else: ?>
                        <a href="<?= base_url('index.php?url=student/apply/' . $prog['id']) ?>"
                           class="btn btn-primary btn-sm">Nộp hồ sơ</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Recent Applications -->
<?php if (!empty($applications)): ?>
<section class="section">
    <h2>Hồ sơ gần đây</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Chương trình</th>
                <th>Ngày nộp</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($applications, 0, 5) as $app): ?>
            <tr>
                <td><?= e($app['program_title']) ?></td>
                <td><?= formatDatetime($app['submission_date']) ?></td>
                <td><span class="badge <?= applicationStatusClass($app['status']) ?>"><?= applicationStatusLabel($app['status']) ?></span></td>
                <td>
                    <a href="<?= base_url('index.php?url=student/applicationDetail/' . $app['id']) ?>"
                       class="btn btn-sm btn-outline">Chi tiết</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('index.php?url=student/applications') ?>">Xem tất cả hồ sơ →</a>
</section>
<?php endif; ?>
