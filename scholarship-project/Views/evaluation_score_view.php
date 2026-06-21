<div class="page-header">
    <h1>Chấm điểm hồ sơ #<?= e($application['id']) ?></h1>
    <p class="text-muted">
        Sinh viên: <strong><?= e($application['full_name']) ?></strong> |
        Chương trình: <strong><?= e($application['program_title']) ?></strong>
    </p>
    <a href="<?= base_url('index.php?url=admin/applicationDetail/' . $application['id']) ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<?php if (empty($criteria)): ?>
    <div class="alert alert-warning">
        Chương trình này chưa có tiêu chí chấm điểm.
        <a href="<?= base_url('index.php?url=admin/scoringCriteria/' . $application['scholarship_program_id']) ?>">
            Thêm tiêu chí ngay
        </a>
    </div>
<?php else: ?>
    <!-- Build existing scores map -->
    <?php
    $scoreMap = [];
    foreach ($scores as $s) {
        $scoreMap[$s['scoring_criterion_id']] = $s['score'];
    }
    ?>

    <div class="form-card">
        <form method="POST" action="<?= base_url('index.php?url=admin/saveScores/' . $application['id']) ?>"
              id="scoreForm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tiêu chí</th>
                        <th>Mô tả</th>
                        <th>Trọng số</th>
                        <th>Điểm tối đa</th>
                        <th>Điểm chấm</th>
                        <th>Điểm quy đổi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($criteria as $c): ?>
                    <?php $currentScore = $scoreMap[$c['id']] ?? 0; ?>
                    <tr>
                        <td><?= e($c['criterion_name']) ?></td>
                        <td><?= e($c['description'] ?? '—') ?></td>
                        <td><?= e($c['weight']) ?></td>
                        <td><?= e($c['max_score']) ?></td>
                        <td>
                            <input type="number"
                                   name="scores[<?= e($c['id']) ?>]"
                                   class="form-control score-input"
                                   min="0" max="<?= e($c['max_score']) ?>"
                                   value="<?= e($currentScore) ?>"
                                   data-weight="<?= e($c['weight']) ?>"
                                   data-max="<?= e($c['max_score']) ?>"
                                   style="width:80px">
                        </td>
                        <td class="converted-score">
                            <?= round($currentScore / $c['max_score'] * $c['weight'] * 10, 3) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><strong>Tổng điểm dự kiến</strong></td>
                        <td><strong id="totalScore">—</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Lưu điểm & Cập nhật xếp hạng</button>
            </div>
        </form>
    </div>

    <script>
    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.score-input').forEach(function(input) {
            const score  = parseFloat(input.value) || 0;
            const weight = parseFloat(input.dataset.weight);
            const max    = parseFloat(input.dataset.max);
            const converted = (score / max) * weight * 10;
            total += converted;
            // Update converted cell
            const row = input.closest('tr');
            row.querySelector('.converted-score').textContent = converted.toFixed(3);
        });
        document.getElementById('totalScore').textContent = total.toFixed(3);
    }

    document.querySelectorAll('.score-input').forEach(function(input) {
        input.addEventListener('input', recalcTotal);
    });

    recalcTotal();
    </script>
<?php endif; ?>
