<?php
class Application
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT a.*, st.full_name, st.student_code, sp.title AS program_title
             FROM applications a
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             ORDER BY a.created_at DESC'
        )->fetchAll();
    }

    public function getByStudent(int $studentId): array
    {
        return $this->db->query(
            'SELECT a.*, sp.title AS program_title, sp.amount_per_student
             FROM applications a
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             WHERE a.student_id = ?
             ORDER BY a.created_at DESC',
            [$studentId]
        )->fetchAll();
    }

    public function getByProgram(int $programId): array
    {
        return $this->db->query(
            'SELECT a.*, st.full_name, st.student_code, st.gpa
             FROM applications a
             JOIN students st ON st.id = a.student_id
             WHERE a.scholarship_program_id = ?
             ORDER BY a.created_at DESC',
            [$programId]
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query(
            'SELECT a.*, st.full_name, st.student_code, st.gpa, st.has_f_grade, st.income_class,
                    sp.title AS program_title, sp.amount_per_student
             FROM applications a
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             WHERE a.id = ?',
            [$id]
        )->fetch() ?: null;
    }

    public function findByStudentAndProgram(int $studentId, int $programId): ?array
    {
        return $this->db->query(
            'SELECT * FROM applications WHERE student_id = ? AND scholarship_program_id = ?',
            [$studentId, $programId]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO applications (student_id, scholarship_program_id, status, note, submission_date)
             VALUES (?, ?, ?, ?, ?)',
            [
                $data['student_id'],
                $data['scholarship_program_id'],
                $data['status']          ?? 'submitted',
                $data['note']            ?? null,
                $data['submission_date'] ?? date('Y-m-d H:i:s'),
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->db->query('UPDATE applications SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE applications SET status = ?, note = ? WHERE id = ?',
            [$data['status'], $data['note'] ?? null, $id]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM applications WHERE id = ?', [$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM applications')->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        return (int)$this->db->query(
            'SELECT COUNT(*) FROM applications WHERE status = ?', [$status]
        )->fetchColumn();
    }
}
