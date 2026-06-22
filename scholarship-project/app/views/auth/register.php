<div class="auth-wrapper">
    <div class="auth-card auth-card--wide">
        <h2>Đăng ký tài khoản sinh viên</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('index.php?url=auth/doRegister') ?>" id="registerForm" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Tên đăng nhập *</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                    <span class="field-error" id="err-username"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <span class="field-error" id="err-email"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mật khẩu *</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <span class="field-error" id="err-password"></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    <span class="field-error" id="err-confirm"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Họ và tên *</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" required>
                    <span class="field-error" id="err-fullname"></span>
                </div>
                <div class="form-group">
                    <label for="student_code">Mã sinh viên *</label>
                    <input type="text" id="student_code" name="student_code" class="form-control" required>
                    <span class="field-error" id="err-code"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="faculty">Khoa / Ngành</label>
                <input type="text" id="faculty" name="faculty" class="form-control" placeholder="VD: Công nghệ thông tin">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
        </form>

        <p class="auth-link">Đã có tài khoản?
            <a href="<?= base_url('index.php?url=auth/login') ?>">Đăng nhập</a>
        </p>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    const fields = {
        username: 'Tên đăng nhập không được để trống.',
        email:    'Email không được để trống.',
        password: 'Mật khẩu không được để trống.',
        full_name:    'Họ tên không được để trống.',
        student_code: 'Mã sinh viên không được để trống.',
    };

    for (const [id, msg] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (!el.value.trim()) {
            document.getElementById('err-' + id.replace('_', '')).textContent = msg;
            el.classList.add('is-invalid');
            valid = false;
        }
    }

    const pw  = document.getElementById('password').value;
    const cpw = document.getElementById('confirm_password').value;
    if (pw && pw.length < 6) {
        document.getElementById('err-password').textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';
        document.getElementById('password').classList.add('is-invalid');
        valid = false;
    }
    if (pw && cpw && pw !== cpw) {
        document.getElementById('err-confirm').textContent = 'Mật khẩu xác nhận không khớp.';
        document.getElementById('confirm_password').classList.add('is-invalid');
        valid = false;
    }

    if (!valid) e.preventDefault();
});
</script>
