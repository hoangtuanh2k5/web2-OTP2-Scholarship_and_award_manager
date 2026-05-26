<?php
class UserModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM users WHERE id = ?', [$id])->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        return $this->db->query('SELECT * FROM users WHERE username = ?', [$username])->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->query('SELECT * FROM users WHERE email = ?', [$email])->fetch() ?: null;
    }

    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO users (role, username, email, password_hash) VALUES (?, ?, ?, ?)',
            [$data['role'], $data['username'], $data['email'], $data['password_hash']]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE users SET role = ?, username = ?, email = ? WHERE id = ?',
            [$data['role'], $data['username'], $data['email'], $id]
        );
    }

    public function updatePassword(int $id, string $hash): void
    {
        $this->db->query('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $id]);
    }

    public function delete(int $id): void
    {
        $this->db->query('DELETE FROM users WHERE id = ?', [$id]);
    }

    public function countAll(): int
    {
        return (int)$this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}