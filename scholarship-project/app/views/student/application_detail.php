<div class="page-header">
    <h1>Chi tiết hồ sơ #<?= e($application['id']) ?></h1>
    <a href="<?= base_url('index.php?url=student/applications') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<div class="detail-grid">
    <!-- Application Info -->
    <div class="detail-card">
        <h3>Thông tin hồ sơ</h3>
        <table class="detail-table">
            <tr><th>Chương trình</th><td><?= e($application['program_title']) ?></td></tr>
            <tr><th>Giá trị học bổng</th><td><?= formatCurrency((float)$application['amount_per_student']) ?></td></tr>
            <tr><th>Ngày nộp</th><td><?= formatDatetime($application['submission_date']) ?></td></tr>
            <tr><th>Trạng thái</th>
                <td><span class="badge <?= applicationStatusClass($application['status']) ?>">
                    <?= applicationStatusLabel($application['status']) ?>
                </span></td>
            </tr>
            <?php if ($application['note']): ?>
            <tr><th>Ghi chú</th><td><?= e($application['note']) ?></td></tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Ranking -->
    <?php if ($ranking): ?>
    <div class="detail-card">
        <h3>Kết quả xếp hạng</h3>
        <table class="detail-table">
            <tr><th>Tổng điểm</th><td><strong><?= e($ranking['total_score']) ?></strong> / 10</td></tr>
            <tr><th>Xếp hạng</th><td>#<?= e($ranking['rank_in_program']) ?></td></tr>
            <tr><th>Trạng thái</th><td><?= rankingStatusLabel($ranking['status']) ?></td></tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Disbursement -->
    <?php if ($disbursement): ?>
    <div class="detail-card">
        <h3>Thông tin chi trả</h3>
        <table class="detail-table">
            <tr><th>Số tiền</th><td><?= formatCurrency((float)$disbursement['amount_paid']) ?></td></tr>
            <tr><th>Trạng thái</th><td><?= disbursementStatusLabel($disbursement['status']) ?></td></tr>
            <tr><th>Ngày chi trả</th><td><?= formatDate($disbursement['payment_date']) ?></td></tr>
            <tr><th>Phương thức</th><td><?= e($disbursement['payment_method'] ?? '—') ?></td></tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Certificate -->
    <?php if ($certificate): ?>
    <div class="detail-card">
        <h3>Chứng nhận học bổng</h3>
        <table class="detail-table">
            <tr><th>Mã chứng nhận</th><td><code><?= e($certificate['certificate_code']) ?></code></td></tr>
            <tr><th>Ngày cấp</th><td><?= formatDate($certificate['issued_date']) ?></td></tr>
            <tr><th>Trạng thái</th><td><?= $certificate['status'] === 'issued' ? '✅ Hợp lệ' : '❌ Đã thu hồi' ?></td></tr>
        </table>
        <a href="<?= base_url('index.php?url=admin/verify') ?>?code=<?= urlencode($certificate['certificate_code']) ?>"
           class="btn btn-outline btn-sm" target="_blank">Xác minh chứng nhận</a>
    </div>
    <?php endif; ?>
</div>
