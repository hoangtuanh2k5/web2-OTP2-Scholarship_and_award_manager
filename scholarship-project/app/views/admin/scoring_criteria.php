<div class="page-header">
    <h1>Tiêu chí chấm điểm</h1>
    <p class="text-muted">Chương trình: <strong><?= e($program['title']) ?></strong></p>
    <a href="<?= base_url('index.php?url=admin/programs') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<!-- Add Criterion Form -->
<div class="form-card">
    <h3>Thêm tiêu chí mới</h3>
    <form method="POST" action="<?= base_url('index.php?url=admin/addScoringCriterion/' . $program['id']) ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="criterion_name">Tên tiêu chí *</label>
                <input type="text" id="criterion_name" name="criterion_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="weight">Trọng số (0.01 – 1.00) *</label>
                <input type="number" id="weight" name="weight" class="form-control"
                       min="0.01" max="1" step="0.01" required placeholder="VD: 0.5">
            </div>
            <div class="form-group">
                <label for="max_score">Điểm tối đa</label>
                <input type="number" id="max_score" name="max_score" class="form-control"
                       min="1" value="10">
            </div>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <input type="text" id="description" name="description" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Thêm tiêu chí</button>
    </form>
</div>

<!-- Criteria List -->
<?php
$totalWeight = array_sum(array_column($criteria, 'weight'));
?>
<div class="info-note">
    Tổng trọng số hiện tại: <strong><?= number_format($totalWeight, 3) ?></strong>
    <?= abs($totalWeight - 1.0) < 0.001 ? '✅ Hợp lệ' : '⚠️ Nên bằng 1.000' ?>
</div>

<table class="table">
    <thead>
        <tr><th>Tên tiêu chí</th><th>Mô tả</th><th>Trọng số</th><th>Điểm tối đa</th><th>Thao tác</th></tr>
    </thead>
    <tbody>
        <?php if (empty($criteria)): ?>
            <tr><td colspan="5" class="text-center text-muted">Chưa có tiêu chí nào.</td></tr>
        <?php else: ?>
            <?php foreach ($criteria as $c): ?>
            <tr>
                <td><?= e($c['criterion_name']) ?></td>
                <td><?= e($c['description'] ?? '—') ?></td>
                <td><?= e($c['weight']) ?></td>
                <td><?= e($c['max_score']) ?></td>
                <td>
                    <a href="<?= base_url('index.php?url=admin/deleteScoringCriterion/' . $c['id']) ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Xóa tiêu chí này?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
