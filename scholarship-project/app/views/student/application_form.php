<div class="page-header">
    <h1>Nộp hồ sơ học bổng</h1>
    <a href="<?= base_url('index.php?url=student/dashboard') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<!-- Program Info -->
<div class="info-card">
    <h2><?= e($program['title']) ?></h2>
    <p><?= e($program['description'] ?? '') ?></p>
    <div class="program-meta">
        <span>💰 Giá trị: <strong><?= formatCurrency((float)$program['amount_per_student']) ?></strong></span>
        <span>👥 Số suất: <strong><?= e($program['max_number_of_awards']) ?></strong></span>
        <?php if ($program['application_deadline']): ?>
            <span>📅 Hạn nộp: <strong><?= formatDate($program['application_deadline']) ?></strong></span>
        <?php endif; ?>
    </div>
</div>

<!-- Eligibility Check -->
<div class="eligibility-box <?= $eligibility['passed'] ? 'eligibility-pass' : 'eligibility-fail' ?>">
    <h3><?= $eligibility['passed'] ? '✅ Bạn đủ điều kiện' : '⚠️ Cảnh báo điều kiện' ?></h3>
    <?php if (!$eligibility['passed']): ?>
        <ul>
            <?php foreach ($eligibility['failures'] as $f): ?>
                <li><?= e($f) ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="text-muted">Bạn vẫn có thể nộp hồ sơ, nhưng hồ sơ sẽ được đánh dấu <em>không đủ điều kiện</em>.</p>
    <?php else: ?>
        <p>Hồ sơ của bạn đáp ứng tất cả điều kiện của chương trình này.</p>
    <?php endif; ?>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- Application Form -->
<div class="form-card">
    <form method="POST" action="<?= base_url('index.php?url=student/doApply') ?>">
        <input type="hidden" name="scholarship_program_id" value="<?= e($program['id']) ?>">

        <div class="form-group">
            <label for="note">Thư ngỏ / Ghi chú (tùy chọn)</label>
            <textarea id="note" name="note" class="form-control" rows="5"
                      placeholder="Giới thiệu bản thân, lý do xin học bổng..."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Nộp hồ sơ</button>
            <a href="<?= base_url('index.php?url=student/dashboard') ?>" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</div>
