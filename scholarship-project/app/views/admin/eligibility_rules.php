<div class="page-header">
    <h1>Điều kiện xét học bổng</h1>
    <p class="text-muted">Chương trình: <strong><?= e($program['title']) ?></strong></p>
    <a href="<?= base_url('index.php?url=admin/programs') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<!-- Add Rule Form -->
<div class="form-card">
    <h3>Thêm điều kiện mới</h3>
    <form method="POST" action="<?= base_url('index.php?url=admin/addEligibilityRule/' . $program['id']) ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="rule_type">Loại điều kiện</label>
                <select id="rule_type" name="rule_type" class="form-control">
                    <option value="gpa_min">GPA tối thiểu</option>
                    <option value="no_f_grade">Không có điểm F</option>
                    <option value="min_activities">Số hoạt động tối thiểu</option>
                    <option value="income_class">Hoàn cảnh kinh tế</option>
                </select>
            </div>
            <div class="form-group">
                <label for="rule_value">Giá trị</label>
                <input type="text" id="rule_value" name="rule_value" class="form-control"
                       placeholder="VD: 3.2 / 1 / 2 / low">
            </div>
            <div class="form-group form-group--checkbox">
                <label>
                    <input type="checkbox" name="is_required" value="1" checked>
                    Bắt buộc
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm điều kiện</button>
    </form>
</div>

<!-- Rules List -->
<table class="table">
    <thead>
        <tr><th>Loại điều kiện</th><th>Giá trị</th><th>Bắt buộc</th><th>Thao tác</th></tr>
    </thead>
    <tbody>
        <?php if (empty($rules)): ?>
            <tr><td colspan="4" class="text-center text-muted">Chưa có điều kiện nào.</td></tr>
        <?php else: ?>
            <?php foreach ($rules as $rule): ?>
            <tr>
                <td><?= e($rule['rule_type']) ?></td>
                <td><?= e($rule['rule_value']) ?></td>
                <td><?= $rule['is_required'] ? '✅ Có' : '—' ?></td>
                <td>
                    <a href="<?= base_url('index.php?url=admin/deleteEligibilityRule/' . $rule['id']) ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Xóa điều kiện này?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>