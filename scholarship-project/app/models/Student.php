<?php
class Student
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM students WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function findByUserId(int $userId): ?array
    {
        return $this->db->query('SELECT * FROM students WHERE user_id = ?', [$userId])->fetch() ?: null;
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT s.*, u.username, u.email FROM students s
             JOIN users u ON u.id = s.user_id
             ORDER BY s.full_name'
        )->fetchAll();
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO students (user_id, student_code, full_name, date_of_birth, phone, address, faculty, gpa, has_f_grade, income_class)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['user_id'],
                $data['student_code'],
                $data['full_name'],
                $data['date_of_birth']  ?? null,
                $data['phone']          ?? null,
                $data['address']        ?? null,
                $data['faculty']        ?? null,
                $data['gpa']            ?? 0.00,
                $data['has_f_grade']    ?? 0,
                $data['income_class']   ?? 'medium',
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE students SET full_name = ?, date_of_birth = ?, phone = ?, address = ?,
             faculty = ?, gpa = ?, has_f_grade = ?, income_class = ? WHERE id = ?',
            [
                $data['full_name'],
                $data['date_of_birth']  ?? null,
                $data['phone']          ?? null,
                $data['address']        ?? null,
                $data['faculty']        ?? null,
                $data['gpa'],
                $data['has_f_grade'],
                $data['income_class'],
                $id,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM students WHERE id = ?', [$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM students')->fetchColumn();
    }
}
