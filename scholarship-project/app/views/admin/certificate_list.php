<div class="page-header">
    <h1>Chứng nhận học bổng</h1>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Mã chứng nhận</th>
            <th>Sinh viên</th>
            <th>Mã SV</th>
            <th>Chương trình</th>
            <th>Ngày cấp</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($certificates as $cert): ?>
        <tr>
            <td><code><?= e($cert['certificate_code']) ?></code></td>
            <td><?= e($cert['full_name']) ?></td>
            <td><?= e($cert['student_code']) ?></td>
            <td><?= e($cert['program_title']) ?></td>
            <td><?= formatDate($cert['issued_date']) ?></td>
            <td>
                <span class="badge <?= $cert['status'] === 'issued' ? 'badge-success' : 'badge-danger' ?>">
                    <?= $cert['status'] === 'issued' ? 'Hợp lệ' : 'Đã thu hồi' ?>
                </span>
            </td>
            <td class="action-cell">
                <a href="<?= base_url('index.php?url=admin/editCertificate/' . $cert['id']) ?>" class="btn btn-sm btn-outline">Sửa</a>
                <a href="<?= base_url('index.php?url=admin/verify') ?>?code=<?= urlencode($cert['certificate_code']) ?>"
                   class="btn btn-sm btn-outline" target="_blank">Xác minh</a>
                <a href="<?= base_url('index.php?url=admin/deleteCertificate/' . $cert['id']) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Xóa chứng nhận này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="section">
    <h3>Cấp chứng nhận mới</h3>
    <p>Để cấp chứng nhận, vào trang <a href="<?= base_url('index.php?url=admin/rankings') ?>">Xếp hạng</a>,
       chọn chương trình, cập nhật trạng thái thành "Cấp học bổng", sau đó dùng nút bên dưới.</p>
</div>
