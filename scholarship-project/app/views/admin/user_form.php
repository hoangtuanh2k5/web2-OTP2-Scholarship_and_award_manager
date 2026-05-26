<?php $isEdit = isset($user); ?>
<div class="page-header">
    <h1><?= $isEdit ? 'Sửa người dùng' : 'Thêm người dùng' ?></h1>
    <a href="<?= base_url('index.php?url=admin/users') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <form method="POST"
          action="<?= $isEdit ? base_url('index.php?url=admin/doEditUser/' . $user['id']) : base_url('index.php?url=admin/doCreateUser') ?>"
          id="userForm" novalidate>

        <div class="form-row">
            <div class="form-group">
                <label for="username">Tên đăng nhập *</label>
                <input type="text" id="username" name="username" class="form-control" required
                       value="<?= e($user['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required
                       value="<?= e($user['email'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password"><?= $isEdit ? 'Mật khẩu mới (để trống nếu không đổi)' : 'Mật khẩu *' ?></label>
                <input type="password" id="password" name="password" class="form-control"
                       <?= $isEdit ? '' : 'required' ?>>
            </div>
            <div class="form-group">
                <label for="role">Vai trò *</label>
                <select id="role" name="role" class="form-control">
                    <option value="student" <?= ($user['role'] ?? '') === 'student' ? 'selected' : '' ?>>Sinh viên</option>
                    <option value="admin"   <?= ($user['role'] ?? '') === 'admin'   ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
        </div>

        <?php if (!$isEdit): ?>
        <div class="form-row" id="studentFields">
            <div class="form-group">
                <label for="full_name">Họ và tên</label>
                <input type="text" id="full_name" name="full_name" class="form-control">
            </div>
            <div class="form-group">
                <label for="student_code">Mã sinh viên</label>
                <input type="text" id="student_code" name="student_code" class="form-control">
            </div>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo tài khoản' ?></button>
            <a href="<?= base_url('index.php?url=admin/users') ?>" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</div>

<script>
const roleSelect = document.getElementById('role');
const studentFields = document.getElementById('studentFields');
if (roleSelect && studentFields) {
    roleSelect.addEventListener('change', function() {
        studentFields.style.display = this.value === 'student' ? 'flex' : 'none';
    });
}
</script>