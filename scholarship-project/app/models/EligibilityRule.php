<?php
class EligibilityRule
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByProgram(int $programId): array
    {
        return $this->db->query(
            'SELECT * FROM eligibility_rules WHERE scholarship_program_id = ?',
            [$programId]
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM eligibility_rules WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO eligibility_rules (scholarship_program_id, rule_type, rule_value, is_required)
             VALUES (?, ?, ?, ?)',
            [
                $data['scholarship_program_id'],
                $data['rule_type'],
                $data['rule_value'],
                $data['is_required'] ?? 1,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE eligibility_rules SET rule_type = ?, rule_value = ?, is_required = ? WHERE id = ?',
            [$data['rule_type'], $data['rule_value'], $data['is_required'] ?? 1, $id]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM eligibility_rules WHERE id = ?', [$id]);
    }

    /**
     * Check whether a student meets all required eligibility rules for a program.
     * Returns array ['passed' => bool, 'failures' => string[]]
     */
    public function checkEligibility(int $programId, array $student, int $activityCount): array
    {
        $rules    = $this->getByProgram($programId);
        $failures = [];

        foreach ($rules as $rule) {
            if (!$rule['is_required']) continue;

            switch ($rule['rule_type']) {
                case 'gpa_min':
                    if ((float)$student['gpa'] < (float)$rule['rule_value']) {
                        $failures[] = 'GPA phải ≥ ' . $rule['rule_value'] . ' (hiện tại: ' . $student['gpa'] . ')';
                    }
                    break;

                case 'no_f_grade':
                    if ((int)$student['has_f_grade'] === 1) {
                        $failures[] = 'Không được có điểm F';
                    }
                    break;

                case 'min_activities':
                    if ($activityCount < (int)$rule['rule_value']) {
                        $failures[] = 'Cần tham gia ít nhất ' . $rule['rule_value'] . ' hoạt động (hiện tại: ' . $activityCount . ')';
                    }
                    break;

                case 'income_class':
                    if ($student['income_class'] !== $rule['rule_value']) {
                        $failures[] = 'Hoàn cảnh kinh tế phải là: ' . $rule['rule_value'];
                    }
                    break;
            }
        }

        return ['passed' => empty($failures), 'failures' => $failures];
    }
}
