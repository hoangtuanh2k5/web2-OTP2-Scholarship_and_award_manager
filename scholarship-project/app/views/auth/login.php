<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Đăng nhập</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php $success = Session::getFlash('success'); if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('index.php?url=auth/doLogin') ?>" novalidate>
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-control"
                       required autocomplete="username" placeholder="Nhập tên đăng nhập">
                <span class="field-error" id="err-username"></span>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-control"
                       required autocomplete="current-password" placeholder="Nhập mật khẩu">
                <span class="field-error" id="err-password"></span>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
        </form>

        <p class="auth-link">Chưa có tài khoản?
            <a href="<?= base_url('index.php?url=auth/register') ?>">Đăng ký ngay</a>
        </p>
        <p class="auth-link">
            <a href="<?= base_url('index.php?url=admin/verify') ?>">Xác minh chứng nhận học bổng</a>
        </p>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    username.classList.remove('is-invalid');
    password.classList.remove('is-invalid');

    if (!username.value.trim()) {
        document.getElementById('err-username').textContent = 'Vui lòng nhập tên đăng nhập.';
        username.classList.add('is-invalid');
        valid = false;
    }
    if (!password.value) {
        document.getElementById('err-password').textContent = 'Vui lòng nhập mật khẩu.';
        password.classList.add('is-invalid');
        valid = false;
    }
    if (!valid) e.preventDefault();
});
</script>
