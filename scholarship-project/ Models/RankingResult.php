<?php
class RankingResult
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByProgram(int $programId): array
    {
        return $this->db->query(
            'SELECT rr.*, a.scholarship_program_id, st.full_name, st.student_code, st.gpa
             FROM ranking_results rr
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             WHERE a.scholarship_program_id = ?
             ORDER BY rr.rank_in_program ASC',
            [$programId]
        )->fetchAll();
    }

    public function findByApplication(int $applicationId): ?array
    {
        return $this->db->query(
            'SELECT * FROM ranking_results WHERE application_id = ?', [$applicationId]
        )->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        return $this->db->query(
            'SELECT rr.*, st.full_name, st.student_code, sp.title AS program_title, sp.amount_per_student
             FROM ranking_results rr
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             WHERE rr.id = ?',
            [$id]
        )->fetch() ?: null;
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT rr.*, st.full_name, st.student_code, sp.title AS program_title
             FROM ranking_results rr
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             ORDER BY sp.title, rr.rank_in_program'
        )->fetchAll();
    }

    public function upsert(int $applicationId, float $totalScore): void
    {
        $existing = $this->findByApplication($applicationId);
        if ($existing) {
            $this->db->query(
                'UPDATE ranking_results SET total_score = ? WHERE application_id = ?',
                [$totalScore, $applicationId]
            );
        } else {
            $this->db->query(
                'INSERT INTO ranking_results (application_id, total_score, rank_in_program, status)
                 VALUES (?, ?, 0, "suggested")',
                [$applicationId, $totalScore]
            );
        }
    }

    /**
     * Recalculate ranks for all applications in a program.
     * Ranks by total_score DESC, assigns sequential rank numbers.
     */
    public function recalculateRanks(int $programId): void
    {
        $rows = $this->getByProgram($programId);
        usort($rows, fn($a, $b) => $b['total_score'] <=> $a['total_score']);

        foreach ($rows as $i => $row) {
            $this->db->query(
                'UPDATE ranking_results SET rank_in_program = ? WHERE id = ?',
                [$i + 1, $row['id']]
            );
        }
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->db->query('UPDATE ranking_results SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM ranking_results WHERE id = ?', [$id]);
    }

    public function countAwarded(): int
    {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM ranking_results WHERE status = 'award_granted'"
        )->fetchColumn();
    }
}
