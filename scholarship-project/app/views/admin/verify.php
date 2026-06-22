<div class="auth-wrapper">
    <div class="auth-card auth-card--wide">
        <h2>🔍 Xác minh chứng nhận học bổng</h2>
        <p class="text-muted">Nhập mã chứng nhận để kiểm tra tính hợp lệ.</p>

        <form method="GET" action="<?= base_url('index.php?url=admin/verify') ?>">
            <div class="form-row">
                <div class="form-group" style="flex:1">
                    <input type="text" name="code" class="form-control"
                           placeholder="VD: CERT-2025-ABC123"
                           value="<?= e($_GET['code'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Xác minh</button>
            </div>
        </form>

        <?php if (!empty($_GET['code'])): ?>
            <?php if ($certificate): ?>
                <div class="verify-result verify-result--valid">
                    <h3>✅ Chứng nhận hợp lệ</h3>
                    <table class="detail-table">
                        <tr><th>Mã chứng nhận</th><td><code><?= e($certificate['certificate_code']) ?></code></td></tr>
                        <tr><th>Sinh viên</th><td><?= e($certificate['full_name']) ?></td></tr>
                        <tr><th>Mã sinh viên</th><td><?= e($certificate['student_code']) ?></td></tr>
                        <tr><th>Khoa</th><td><?= e($certificate['faculty'] ?? '—') ?></td></tr>
                        <tr><th>Chương trình học bổng</th><td><?= e($certificate['program_title']) ?></td></tr>
                        <tr><th>Giá trị</th><td><?= formatCurrency((float)$certificate['amount_per_student']) ?></td></tr>
                        <tr><th>Ngày cấp</th><td><?= formatDate($certificate['issued_date']) ?></td></tr>
                        <tr><th>Trạng thái chứng nhận</th>
                            <td><?= $certificate['status'] === 'issued' ? '✅ Hợp lệ' : '❌ Đã thu hồi' ?></td>
                        </tr>
                        <?php if ($certificate['disbursement_status']): ?>
                        <tr><th>Trạng thái chi trả</th>
                            <td><?= disbursementStatusLabel($certificate['disbursement_status']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            <?php else: ?>
                <div class="verify-result verify-result--invalid">
                    <h3>❌ Không tìm thấy chứng nhận</h3>
                    <p>Mã "<strong><?= e($_GET['code']) ?></strong>" không tồn tại trong hệ thống.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <p class="auth-link">
            <a href="<?= base_url('index.php?url=auth/login') ?>">← Về trang đăng nhập</a>
        </p>
    </div>
</div>
