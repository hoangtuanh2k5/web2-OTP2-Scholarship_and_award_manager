<div class="page-header">
    <h1>Xếp hạng: <?= e($program['title']) ?></h1>
    <p class="text-muted">
        Số suất: <strong><?= e($program['max_number_of_awards']) ?></strong> |
        Giá trị: <strong><?= formatCurrency((float)$program['amount_per_student']) ?></strong>
    </p>
    <a href="<?= base_url('index.php?url=admin/rankings') ?>" class="btn btn-outline">← Quay lại</a>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<?php if (empty($rankings)): ?>
    <p class="text-muted">Chưa có kết quả xếp hạng cho chương trình này.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Hạng</th>
                <th>Sinh viên</th>
                <th>Mã SV</th>
                <th>GPA</th>
                <th>Tổng điểm</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rankings as $i => $r): ?>
            <tr class="<?= $i < $program['max_number_of_awards'] ? 'row-highlight' : '' ?>">
                <td><strong>#<?= e($r['rank_in_program']) ?></strong></td>
                <td><?= e($r['full_name']) ?></td>
                <td><?= e($r['student_code']) ?></td>
                <td><?= e($r['gpa']) ?></td>
                <td><strong><?= e($r['total_score']) ?></strong></td>
                <td>
                    <span class="badge <?= $r['status'] === 'award_granted' ? 'badge-success' : ($r['status'] === 'rejected' ? 'badge-danger' : 'badge-warning') ?>">
                        <?= rankingStatusLabel($r['status']) ?>
                    </span>
                </td>
                <td>
                    <form method="POST" action="<?= base_url('index.php?url=admin/updateRankingStatus/' . $r['id']) ?>"
                          style="display:flex;gap:.5rem">
                        <input type="hidden" name="program_id" value="<?= e($program['id']) ?>">
                        <select name="status" class="form-control form-control--sm">
                            <option value="suggested"     <?= $r['status'] === 'suggested'     ? 'selected' : '' ?>>Đề xuất</option>
                            <option value="award_granted" <?= $r['status'] === 'award_granted' ? 'selected' : '' ?>>Cấp học bổng</option>
                            <option value="rejected"      <?= $r['status'] === 'rejected'      ? 'selected' : '' ?>>Từ chối</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="text-muted">Hàng nền xanh = trong ngân sách (<?= e($program['max_number_of_awards']) ?> suất đầu)</p>
<?php endif; ?>
