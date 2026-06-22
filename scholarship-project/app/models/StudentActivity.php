<?php
class StudentActivity
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByStudent(int $studentId): array
    {
        return $this->db->query(
            'SELECT sa.*, s.name AS semester_name
             FROM student_activities sa
             LEFT JOIN semesters s ON s.id = sa.semester_id
             WHERE sa.student_id = ?
             ORDER BY sa.created_at DESC',
            [$studentId]
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM student_activities WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function countCompleted(int $studentId, ?int $semesterId = null): int
    {
        if ($semesterId) {
            return (int)$this->db->query(
                'SELECT COUNT(*) FROM student_activities WHERE student_id = ? AND semester_id = ? AND completed = 1',
                [$studentId, $semesterId]
            )->fetchColumn();
        }
        return (int)$this->db->query(
            'SELECT COUNT(*) FROM student_activities WHERE student_id = ? AND completed = 1',
            [$studentId]
        )->fetchColumn();
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO student_activities (student_id, semester_id, activity_name, activity_type, description, completed)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $data['student_id'],
                $data['semester_id']   ?? null,
                $data['activity_name'],
                $data['activity_type'],
                $data['description']   ?? null,
                $data['completed']     ?? 1,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE student_activities SET activity_name = ?, activity_type = ?, description = ?, completed = ?, semester_id = ?
             WHERE id = ?',
            [
                $data['activity_name'],
                $data['activity_type'],
                $data['description'] ?? null,
                $data['completed']   ?? 1,
                $data['semester_id'] ?? null,
                $id,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM student_activities WHERE id = ?', [$id]);
    }
}
