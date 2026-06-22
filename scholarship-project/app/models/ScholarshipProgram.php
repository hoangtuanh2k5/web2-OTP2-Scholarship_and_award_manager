<?php
class ScholarshipProgram
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT sp.*, s.name AS semester_name
             FROM scholarship_programs sp
             LEFT JOIN semesters s ON s.id = sp.semester_id
             ORDER BY sp.created_at DESC'
        )->fetchAll();
    }

    public function getActive(): array
    {
        return $this->db->query(
            "SELECT sp.*, s.name AS semester_name
             FROM scholarship_programs sp
             LEFT JOIN semesters s ON s.id = sp.semester_id
             WHERE sp.status = 'active'
             ORDER BY sp.application_deadline ASC"
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query(
            'SELECT sp.*, s.name AS semester_name
             FROM scholarship_programs sp
             LEFT JOIN semesters s ON s.id = sp.semester_id
             WHERE sp.id = ?',
            [$id]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO scholarship_programs
             (title, description, amount_per_student, max_number_of_awards, semester_id, application_deadline, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $data['title'],
                $data['description']          ?? null,
                $data['amount_per_student'],
                $data['max_number_of_awards'],
                $data['semester_id']           ?? null,
                $data['application_deadline']  ?? null,
                $data['status']                ?? 'active',
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE scholarship_programs
             SET title = ?, description = ?, amount_per_student = ?, max_number_of_awards = ?,
                 semester_id = ?, application_deadline = ?, status = ?
             WHERE id = ?',
            [
                $data['title'],
                $data['description']          ?? null,
                $data['amount_per_student'],
                $data['max_number_of_awards'],
                $data['semester_id']           ?? null,
                $data['application_deadline']  ?? null,
                $data['status'],
                $id,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM scholarship_programs WHERE id = ?', [$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM scholarship_programs')->fetchColumn();
    }
}
