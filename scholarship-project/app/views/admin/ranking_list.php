<div class="page-header">
    <h1>Xếp hạng học bổng</h1>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<p>Chọn chương trình để xem xếp hạng:</p>

<div class="card-grid">
    <?php foreach ($programs as $prog): ?>
    <div class="program-card">
        <h3><?= e($prog['title']) ?></h3>
        <p class="text-muted"><?= e($prog['semester_name'] ?? '') ?></p>
        <a href="<?= base_url('index.php?url=admin/rankingByProgram/' . $prog['id']) ?>"
           class="btn btn-primary btn-sm">Xem xếp hạng</a>
    </div>
    <?php endforeach; ?>
</div>
