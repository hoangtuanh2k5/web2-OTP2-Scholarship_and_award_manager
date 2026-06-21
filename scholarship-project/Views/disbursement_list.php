<div class="page-header">
    <h1>Quản lý chi trả học bổng</h1>
    <a href="<?= base_url('index.php?url=admin/createDisbursement') ?>" class="btn btn-primary">+ Tạo bản ghi chi trả</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sinh viên</th>
            <th>Mã SV</th>
            <th>Chương trình</th>
            <th>Số tiền</th>
            <th>Ngày chi</th>
            <th>Phương thức</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($disbursements as $d): ?>
        <tr>
            <td><?= e($d['id']) ?></td>
            <td><?= e($d['full_name']) ?></td>
            <td><?= e($d['student_code']) ?></td>
            <td><?= e($d['program_title']) ?></td>
            <td><?= formatCurrency((float)$d['amount_paid']) ?></td>
            <td><?= formatDate($d['payment_date']) ?></td>
            <td><?= e($d['payment_method'] ?? '—') ?></td>
            <td>
                <span class="badge <?= $d['status'] === 'paid' ? 'badge-success' : ($d['status'] === 'cancelled' ? 'badge-danger' : 'badge-warning') ?>">
                    <?= disbursementStatusLabel($d['status']) ?>
                </span>
            </td>
            <td class="action-cell">
                <a href="<?= base_url('index.php?url=admin/editDisbursement/' . $d['id']) ?>" class="btn btn-sm btn-outline">Sửa</a>
                <a href="<?= base_url('index.php?url=admin/deleteDisbursement/' . $d['id']) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Xóa bản ghi này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
