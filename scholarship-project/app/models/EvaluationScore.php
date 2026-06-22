<?php
class EvaluationScore
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByApplication(int $applicationId): array
    {
        return $this->db->query(
            'SELECT es.*, sc.criterion_name, sc.weight, sc.max_score
             FROM evaluation_scores es
             JOIN scoring_criteria sc ON sc.id = es.scoring_criterion_id
             WHERE es.application_id = ?',
            [$applicationId]
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM evaluation_scores WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function upsert(array $data): void
    {
        // Insert or update if same application + criterion already exists
        $this->db->query(
            'INSERT INTO evaluation_scores (application_id, scoring_criterion_id, score, evaluated_by)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE score = VALUES(score), evaluated_by = VALUES(evaluated_by),
             evaluation_date = CURRENT_TIMESTAMP',
            [
                $data['application_id'],
                $data['scoring_criterion_id'],
                $data['score'],
                $data['evaluated_by'],
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM evaluation_scores WHERE id = ?', [$id]);
    }

    /**
     * Calculate weighted total score for an application.
     * Formula: SUM(score / max_score * weight) * 10
     */
    public function calculateTotalScore(int $applicationId): float
    {
        $scores = $this->getByApplication($applicationId);
        if (empty($scores)) return 0.0;

        $total = 0.0;
        foreach ($scores as $s) {
            $normalized = (float)$s['score'] / (float)$s['max_score'];
            $total += $normalized * (float)$s['weight'];
        }
        return round($total * 10, 3);
    }
}
