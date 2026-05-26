<?php $isEdit = isset($program); ?>
<div class="page-header">
    <h1><?= $isEdit ? 'Sửa chương trình học bổng' : 'Thêm chương trình học bổng' ?></h1>
    <a href="<?= base_url('index.php?url=admin/programs') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<div class="form-card">
    <form method="POST"
          action="<?= $isEdit ? base_url('index.php?url=admin/doEditProgram/' . $program['id']) : base_url('index.php?url=admin/doCreateProgram') ?>"
          novalidate>

        <div class="form-group">
            <label for="title">Tên chương trình *</label>
            <input type="text" id="title" name="title" class="form-control" required
                   value="<?= e($program['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?= e($program['description'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="amount_per_student">Giá trị học bổng (VNĐ) *</label>
                <input type="number" id="amount_per_student" name="amount_per_student" class="form-control"
                       min="0" step="100000" required value="<?= e($program['amount_per_student'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="max_number_of_awards">Số suất tối đa *</label>
                <input type="number" id="max_number_of_awards" name="max_number_of_awards" class="form-control"
                       min="1" required value="<?= e($program['max_number_of_awards'] ?? 1) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="semester_id">Học kỳ</label>
                <select id="semester_id" name="semester_id" class="form-control">
                    <option value="">-- Không chọn --</option>
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?= e($sem['id']) ?>"
                            <?= isset($program) && (int)$program['semester_id'] === (int)$sem['id'] ? 'selected' : '' ?>>
                            <?= e($sem['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="application_deadline">Hạn nộp hồ sơ</label>
                <input type="date" id="application_deadline" name="application_deadline" class="form-control"
                       value="<?= e($program['application_deadline'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select id="status" name="status" class="form-control">
                <option value="active" <?= ($program['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Đang mở</option>
                <option value="closed" <?= ($program['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Đã đóng</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo chương trình' ?></button>
            <a href="<?= base_url('index.php?url=admin/programs') ?>" class="btn btn-outline">Hủy</a>
        </div>
    </form>
</div>