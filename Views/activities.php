<div class="page-header">
    <h1>Hoạt động của tôi</h1>
    <a href="<?= base_url('index.php?url=student/dashboard') ?>" class="btn btn-outline">← Dashboard</a>
</div>

<?php $flash = Session::getFlash('success'); if ($flash): ?>
    <div class="alert alert-success"><?= e($flash) ?></div>
<?php endif; ?>
<?php $flash = Session::getFlash('error'); if ($flash): ?>
    <div class="alert alert-danger"><?= $flash ?></div>
<?php endif; ?>

<!-- Add Activity Form -->
<div class="form-card">
    <h3>Thêm hoạt động mới</h3>
    <form method="POST" action="<?= base_url('index.php?url=student/addActivity') ?>" id="activityForm">
        <div class="form-row">
            <div class="form-group">
                <label for="activity_name">Tên hoạt động *</label>
                <input type="text" id="activity_name" name="activity_name" class="form-control" required>
                <span class="field-error" id="err-name"></span>
            </div>
            <div class="form-group">
                <label for="activity_type">Loại hoạt động *</label>
                <select id="activity_type" name="activity_type" class="form-control" required>
                    <option value="">-- Chọn loại --</option>
                    <option value="club">Câu lạc bộ</option>
                    <option value="event">Sự kiện / Hội thảo</option>
                    <option value="competition">Cuộc thi</option>
                    <option value="community">Tình nguyện / Cộng đồng</option>
                    <option value="research">Nghiên cứu khoa học</option>
                </select>
                <span class="field-error" id="err-type"></span>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="semester_id">Học kỳ</label>
                <select id="semester_id" name="semester_id" class="form-control">
                    <option value="">-- Không chọn --</option>
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?= e($sem['id']) ?>"><?= e($sem['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <input type="text" id="description" name="description" class="form-control"
                       placeholder="Mô tả ngắn về hoạt động">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm hoạt động</button>
    </form>
</div>

<!-- Activity List -->
<div class="section">
    <h3>Danh sách hoạt động (<?= count($activities) ?>)</h3>
    <?php if (empty($activities)): ?>
        <p class="text-muted">Chưa có hoạt động nào được ghi nhận.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên hoạt động</th>
                    <th>Loại</th>
                    <th>Học kỳ</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $i => $act): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($act['activity_name']) ?></td>
                    <td><?= e($act['activity_type']) ?></td>
                    <td><?= e($act['semester_name'] ?? '—') ?></td>
                    <td><?= e($act['description'] ?? '—') ?></td>
                    <td>
                        <a href="<?= base_url('index.php?url=student/deleteActivity/' . $act['id']) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa hoạt động này?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
document.getElementById('activityForm').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');

    const name = document.getElementById('activity_name');
    const type = document.getElementById('activity_type');

    if (!name.value.trim()) {
        document.getElementById('err-name').textContent = 'Vui lòng nhập tên hoạt động.';
        name.classList.add('is-invalid'); valid = false;
    }
    if (!type.value) {
        document.getElementById('err-type').textContent = 'Vui lòng chọn loại hoạt động.';
        type.classList.add('is-invalid'); valid = false;
    }
    if (!valid) e.preventDefault();
});
</script>
