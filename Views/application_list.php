<div class="page-header">
    <h1>Danh sách hồ sơ ứng tuyển</h1>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<!-- Filter -->
<div class="filter-bar">
    <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên, mã SV, chương trình...">
</div>

<table class="table" id="appTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sinh viên</th>
            <th>Mã SV</th>
            <th>Chương trình</th>
            <th>Ngày nộp</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= e($app['id']) ?></td>
            <td><?= e($app['full_name']) ?></td>
            <td><?= e($app['student_code']) ?></td>
            <td><?= e($app['program_title']) ?></td>
            <td><?= formatDatetime($app['submission_date']) ?></td>
            <td>
                <span class="badge <?= applicationStatusClass($app['status']) ?>">
                    <?= applicationStatusLabel($app['status']) ?>
                </span>
            </td>
            <td class="action-cell">
                <a href="<?= base_url('index.php?url=admin/applicationDetail/' . $app['id']) ?>"
                   class="btn btn-sm btn-outline">Chi tiết</a>
                <a href="<?= base_url('index.php?url=admin/scores/' . $app['id']) ?>"
                   class="btn btn-sm btn-primary">Chấm điểm</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#appTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
