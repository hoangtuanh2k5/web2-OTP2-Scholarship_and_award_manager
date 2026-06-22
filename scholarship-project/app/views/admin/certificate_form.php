<div class="page-header">
    <h1>Sửa chứng nhận</h1>
    <a href="<?= base_url('index.php?url=admin/certificates') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <form method="POST" action="<?= base_url('index.php?url=admin/doEditCertificate/' . $certificate['id']) ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="certificate_code">Mã chứng nhận *</label>
                <input type="text" id="certificate_code" name="certificate_code" class="form-control" required
                       value="<?= e($certificate['certificate_code']) ?>">
            </div>
            <div class="form-group">
                <label for="issued_date">Ngày cấp *</label>
                <input type="date" id="issued_date" name="issued_date" class="form-control" required
                       value="<?= e($certificate['issued_date']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="pdf_url">URL file PDF (tùy chọn)</label>
            <input type="text" id="pdf_url" name="pdf_url" class="form-control"
                   value="<?= e($certificate['pdf_url'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select id="status" name="status" class="form-control">
                <option value="issued"  <?= $certificate['status'] === 'issued'  ? 'selected' : '' ?>>Hợp lệ</option>
                <option value="revoked" <?= $certificate['status'] === 'revoked' ? 'selected' : '' ?>>Thu hồi</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?= base_url('index.php?url=admin/certificates') ?>" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</div>
