<?php
class Disbursement
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT d.*, st.full_name, st.student_code, sp.title AS program_title
             FROM disbursements d
             JOIN ranking_results rr ON rr.id = d.ranking_result_id
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             ORDER BY d.created_at DESC'
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query(
            'SELECT d.*, st.full_name, st.student_code, sp.title AS program_title
             FROM disbursements d
             JOIN ranking_results rr ON rr.id = d.ranking_result_id
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             WHERE d.id = ?',
            [$id]
        )->fetch() ?: null;
    }

    public function findByRankingResult(int $rankingResultId): ?array
    {
        return $this->db->query(
            'SELECT * FROM disbursements WHERE ranking_result_id = ?', [$rankingResultId]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO disbursements (ranking_result_id, amount_paid, payment_date, status, payment_method, note)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $data['ranking_result_id'],
                $data['amount_paid'],
                $data['payment_date']   ?? null,
                $data['status']         ?? 'pending',
                $data['payment_method'] ?? null,
                $data['note']           ?? null,
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE disbursements SET amount_paid = ?, payment_date = ?, status = ?, payment_method = ?, note = ?
             WHERE id = ?',
            [
                $data['amount_paid'],
                $data['payment_date']   ?? null,
                $data['status'],
                $data['payment_method'] ?? null,
                $data['note']           ?? null,
                $id,
            ]
        );
    }

    public function updateStatus(int $id, string $status, ?string $paymentDate = null): void
    {
        $this->db->query(
            'UPDATE disbursements SET status = ?, payment_date = ? WHERE id = ?',
            [$status, $paymentDate, $id]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM disbursements WHERE id = ?', [$id]);
    }

    public function getTotalPaid(): float
    {
        return (float)$this->db->query(
            "SELECT COALESCE(SUM(amount_paid), 0) FROM disbursements WHERE status = 'paid'"
        )->fetchColumn();
    }
}
