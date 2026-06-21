<?php
class ScoringCriterion
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByProgram(int $programId): array
    {
        return $this->db->query(
            'SELECT * FROM scoring_criteria WHERE scholarship_program_id = ? ORDER BY id',
            [$programId]
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM scoring_criteria WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO scoring_criteria (scholarship_program_id, criterion_name, description, weight, max_score)
             VALUES (?, ?, ?, ?, ?)',
            [
                $data['scholarship_program_id'],
                $data['criterion_name'],
                $data['description'] ?? null,
                $data['weight'],
                $data['max_score']   ?? 10,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE scoring_criteria SET criterion_name = ?, description = ?, weight = ?, max_score = ? WHERE id = ?',
            [$data['criterion_name'], $data['description'] ?? null, $data['weight'], $data['max_score'], $id]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM scoring_criteria WHERE id = ?', [$id]);
    }
}