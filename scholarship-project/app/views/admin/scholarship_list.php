<div class="page-header">
    <h1>Chương trình học bổng</h1>
    <a href="<?= base_url('index.php?url=admin/createProgram') ?>" class="btn btn-primary">+ Thêm chương trình</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Tên chương trình</th>
            <th>Giá trị</th>
            <th>Số suất</th>
            <th>Học kỳ</th>
            <th>Hạn nộp</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($programs as $prog): ?>
        <tr>
            <td><?= e($prog['title']) ?></td>
            <td><?= formatCurrency((float)$prog['amount_per_student']) ?></td>
            <td><?= e($prog['max_number_of_awards']) ?></td>
            <td><?= e($prog['semester_name'] ?? '—') ?></td>
            <td><?= formatDate($prog['application_deadline']) ?></td>
            <td>
                <span class="badge <?= $prog['status'] === 'active' ? 'badge-success' : 'badge-secondary' ?>">
                    <?= $prog['status'] === 'active' ? 'Đang mở' : 'Đã đóng' ?>
                </span>
            </td>
            <td class="action-cell">
                <a href="<?= base_url('index.php?url=admin/editProgram/' . $prog['id']) ?>" class="btn btn-sm btn-outline">Sửa</a>
                <a href="<?= base_url('index.php?url=admin/eligibilityRules/' . $prog['id']) ?>" class="btn btn-sm btn-outline">Điều kiện</a>
                <a href="<?= base_url('index.php?url=admin/scoringCriteria/' . $prog['id']) ?>" class="btn btn-sm btn-outline">Tiêu chí</a>
                <a href="<?= base_url('index.php?url=admin/rankingByProgram/' . $prog['id']) ?>" class="btn btn-sm btn-outline">Xếp hạng</a>
                <a href="<?= base_url('index.php?url=admin/deleteProgram/' . $prog['id']) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Xóa chương trình này? Tất cả hồ sơ liên quan sẽ bị xóa.')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>