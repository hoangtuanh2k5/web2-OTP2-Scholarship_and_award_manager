<?php
class AwardCertificate
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT ac.*, st.full_name, st.student_code, sp.title AS program_title
             FROM award_certificates ac
             JOIN ranking_results rr ON rr.id = ac.ranking_result_id
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             ORDER BY ac.issued_date DESC'
        )->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query(
            'SELECT ac.*, st.full_name, st.student_code, sp.title AS program_title, sp.amount_per_student
             FROM award_certificates ac
             JOIN ranking_results rr ON rr.id = ac.ranking_result_id
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             WHERE ac.id = ?',
            [$id]
        )->fetch() ?: null;
    }

    public function findByCode(string $code): ?array
    {
        return $this->db->query(
            'SELECT ac.*, st.full_name, st.student_code, st.faculty,
                    sp.title AS program_title, sp.amount_per_student,
                    d.status AS disbursement_status, d.payment_date
             FROM award_certificates ac
             JOIN ranking_results rr ON rr.id = ac.ranking_result_id
             JOIN applications a ON a.id = rr.application_id
             JOIN students st ON st.id = a.student_id
             JOIN scholarship_programs sp ON sp.id = a.scholarship_program_id
             LEFT JOIN disbursements d ON d.ranking_result_id = rr.id
             WHERE ac.certificate_code = ?',
            [$code]
        )->fetch() ?: null;
    }

    public function findByRankingResult(int $rankingResultId): ?array
    {
        return $this->db->query(
            'SELECT * FROM award_certificates WHERE ranking_result_id = ?', [$rankingResultId]
        )->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO award_certificates (ranking_result_id, certificate_code, issued_date, pdf_url, status)
             VALUES (?, ?, ?, ?, ?)',
            [
                $data['ranking_result_id'],
                $data['certificate_code'],
                $data['issued_date'],
                $data['pdf_url'] ?? null,
                $data['status']  ?? 'issued',
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE award_certificates SET certificate_code = ?, issued_date = ?, pdf_url = ?, status = ?
             WHERE id = ?',
            [
                $data['certificate_code'],
                $data['issued_date'],
                $data['pdf_url'] ?? null,
                $data['status'],
                $id,
            ]
        );
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->db->query('UPDATE award_certificates SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM award_certificates WHERE id = ?', [$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM award_certificates')->fetchColumn();
    }
}
