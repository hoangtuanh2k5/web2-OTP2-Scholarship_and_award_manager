<div class="page-header">
    <h1>Chi tiết hồ sơ #<?= e($application['id']) ?></h1>
    <a href="<?= base_url('index.php?url=admin/applications') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<div class="detail-grid">
    <div class="detail-card">
        <h3>Thông tin sinh viên</h3>
        <table class="detail-table">
            <tr><th>Họ tên</th><td><?= e($application['full_name']) ?></td></tr>
            <tr><th>Mã SV</th><td><?= e($application['student_code']) ?></td></tr>
            <tr><th>GPA</th><td><?= e($application['gpa']) ?></td></tr>
            <tr><th>Điểm F</th><td><?= $application['has_f_grade'] ? '⚠️ Có' : '✅ Không' ?></td></tr>
            <tr><th>Hoàn cảnh</th><td><?= e($application['income_class']) ?></td></tr>
        </table>
    </div>

    <div class="detail-card">
        <h3>Thông tin hồ sơ</h3>
        <table class="detail-table">
            <tr><th>Chương trình</th><td><?= e($application['program_title']) ?></td></tr>
            <tr><th>Giá trị</th><td><?= formatCurrency((float)$application['amount_per_student']) ?></td></tr>
            <tr><th>Ngày nộp</th><td><?= formatDatetime($application['submission_date']) ?></td></tr>
            <tr><th>Trạng thái</th>
                <td><span class="badge <?= applicationStatusClass($application['status']) ?>">
                    <?= applicationStatusLabel($application['status']) ?>
                </span></td>
            </tr>
        </table>

        <!-- Update Status -->
        <form method="POST" action="<?= base_url('index.php?url=admin/updateApplicationStatus/' . $application['id']) ?>"
              style="margin-top:1rem">
            <div class="form-row">
                <select name="status" class="form-control">
                    <?php foreach (['draft','submitted','eligible','ineligible','under_review','rejected','awarded'] as $s): ?>
                        <option value="<?= $s ?>" <?= $application['status'] === $s ? 'selected' : '' ?>>
                            <?= applicationStatusLabel($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
            </div>
        </form>
    </div>

    <?php if ($ranking): ?>
    <div class="detail-card">
        <h3>Kết quả xếp hạng</h3>
        <table class="detail-table">
            <tr><th>Tổng điểm</th><td><strong><?= e($ranking['total_score']) ?></strong></td></tr>
            <tr><th>Xếp hạng</th><td>#<?= e($ranking['rank_in_program']) ?></td></tr>
            <tr><th>Trạng thái</th><td><?= rankingStatusLabel($ranking['status']) ?></td></tr>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Scores Summary -->
<?php if (!empty($scores)): ?>
<div class="section">
    <h3>Điểm chấm</h3>
    <table class="table">
        <thead>
            <tr><th>Tiêu chí</th><th>Trọng số</th><th>Điểm</th><th>Điểm tối đa</th><th>Điểm quy đổi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($scores as $s): ?>
            <tr>
                <td><?= e($s['criterion_name']) ?></td>
                <td><?= e($s['weight']) ?></td>
                <td><?= e($s['score']) ?></td>
                <td><?= e($s['max_score']) ?></td>
                <td><?= round($s['score'] / $s['max_score'] * $s['weight'] * 10, 3) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('index.php?url=admin/scores/' . $application['id']) ?>" class="btn btn-primary">
        Chỉnh sửa điểm
    </a>
</div>
<?php else: ?>
<div class="section">
    <a href="<?= base_url('index.php?url=admin/scores/' . $application['id']) ?>" class="btn btn-primary">
        Chấm điểm hồ sơ này
    </a>
</div>
<?php endif; ?>
