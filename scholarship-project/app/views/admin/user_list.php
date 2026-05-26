<div class="page-header">
    <h1>Quản lý người dùng</h1>
    <a href="<?= base_url('index.php?url=admin/createUser') ?>" class="btn btn-primary">+ Thêm người dùng</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Vai trò</th><th>Ngày tạo</th><th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= e($u['id']) ?></td>
            <td><?= e($u['username']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><span class="badge <?= $u['role'] === 'admin' ? 'badge-warning' : 'badge-info' ?>"><?= e($u['role']) ?></span></td>
            <td><?= formatDatetime($u['created_at']) ?></td>
            <td class="action-cell">
                <a href="<?= base_url('index.php?url=admin/editUser/' . $u['id']) ?>" class="btn btn-sm btn-outline">Sửa</a>
                <?php if ((int)$u['id'] !== Session::userId()): ?>
                <a href="<?= base_url('index.php?url=admin/deleteUser/' . $u['id']) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Xóa người dùng này?')">Xóa</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>