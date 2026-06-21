<div class="page-header">
    <h1>Hồ sơ của tôi</h1>
    <a href="<?= base_url('index.php?url=student/dashboard') ?>" class="btn btn-outline">← Về Dashboard</a>
</div>

<?php $flash = Session::getFlash('success'); if ($flash): ?>
    <div class="alert alert-success"><?= e($flash) ?></div>
<?php endif; ?>
<?php $flash = Session::getFlash('error'); if ($flash): ?>
    <div class="alert alert-danger"><?= $flash ?></div>
<?php endif; ?>

<?php if (empty($applications)): ?>
    <div class="empty-state">
        <p>Bạn chưa nộp hồ sơ nào.</p>
        <a href="<?= base_url('index.php?url=student/dashboard') ?>" class="btn btn-primary">Xem học bổng đang mở</a>
    </div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Chương trình học bổng</th>
                <th>Giá trị</th>
                <th>Ngày nộp</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $i => $app): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($app['program_title']) ?></td>
                <td><?= formatCurrency((float)$app['amount_per_student']) ?></td>
                <td><?= formatDatetime($app['submission_date']) ?></td>
                <td>
                    <span class="badge <?= applicationStatusClass($app['status']) ?>">
                        <?= applicationStatusLabel($app['status']) ?>
                    </span>
                </td>
                <td class="action-cell">
                    <a href="<?= base_url('index.php?url=student/applicationDetail/' . $app['id']) ?>"
                       class="btn btn-sm btn-outline">Chi tiết</a>
                    <?php if (in_array($app['status'], ['draft','submitted','eligible','ineligible'])): ?>
                        <a href="<?= base_url('index.php?url=student/cancelApplication/' . $app['id']) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Bạn có chắc muốn hủy hồ sơ này?')">Hủy</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
