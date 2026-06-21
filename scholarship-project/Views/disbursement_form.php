<?php $isEdit = isset($disbursement); ?>
<div class="page-header">
    <h1><?= $isEdit ? 'Sửa bản ghi chi trả' : 'Tạo bản ghi chi trả' ?></h1>
    <a href="<?= base_url('index.php?url=admin/disbursements') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <form method="POST"
          action="<?= $isEdit ? base_url('index.php?url=admin/doEditDisbursement/' . $disbursement['id']) : base_url('index.php?url=admin/doCreateDisbursement') ?>">

        <?php if (!$isEdit): ?>
        <div class="form-group">
            <label for="ranking_result_id">Kết quả xếp hạng (sinh viên được cấp) *</label>
            <select id="ranking_result_id" name="ranking_result_id" class="form-control" required>
                <option value="">-- Chọn --</option>
                <?php foreach ($rankings as $r): ?>
                    <?php if ($r['status'] === 'award_granted'): ?>
                    <option value="<?= e($r['id']) ?>">
                        <?= e($r['full_name']) ?> – <?= e($r['program_title']) ?> (Điểm: <?= e($r['total_score']) ?>)
                    </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <?php else: ?>
            <p><strong>Sinh viên:</strong> <?= e($disbursement['full_name']) ?> – <?= e($disbursement['program_title']) ?></p>
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label for="amount_paid">Số tiền chi trả (VNĐ) *</label>
                <input type="number" id="amount_paid" name="amount_paid" class="form-control"
                       min="0" step="1000" required value="<?= e($disbursement['amount_paid'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="payment_date">Ngày chi trả</label>
                <input type="date" id="payment_date" name="payment_date" class="form-control"
                       value="<?= e($disbursement['payment_date'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending"    <?= ($disbursement['status'] ?? 'pending') === 'pending'    ? 'selected' : '' ?>>Chờ xử lý</option>
                    <option value="processing" <?= ($disbursement['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                    <option value="paid"       <?= ($disbursement['status'] ?? '') === 'paid'       ? 'selected' : '' ?>>Đã chi trả</option>
                    <option value="cancelled"  <?= ($disbursement['status'] ?? '') === 'cancelled'  ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
            <div class="form-group">
                <label for="payment_method">Phương thức thanh toán</label>
                <input type="text" id="payment_method" name="payment_method" class="form-control"
                       placeholder="VD: Chuyển khoản ngân hàng"
                       value="<?= e($disbursement['payment_method'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="note">Ghi chú</label>
            <textarea id="note" name="note" class="form-control" rows="2"><?= e($disbursement['note'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo bản ghi' ?></button>
            <a href="<?= base_url('index.php?url=admin/disbursements') ?>" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</div>
