<?php
class Semester
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM semesters ORDER BY code DESC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM semesters WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO semesters (code, name, start_date, end_date) VALUES (?, ?, ?, ?)',
            [$data['code'], $data['name'], $data['start_date'] ?? null, $data['end_date'] ?? null]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE semesters SET code = ?, name = ?, start_date = ?, end_date = ? WHERE id = ?',
            [$data['code'], $data['name'], $data['start_date'] ?? null, $data['end_date'] ?? null, $id]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM semesters WHERE id = ?', [$id]);
    }
}