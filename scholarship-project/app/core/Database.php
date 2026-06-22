<?php
/**
 * Database – Singleton Pattern
 * Ensures only one PDO connection exists throughout the request lifecycle.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        require_once __DIR__ . '/../config/database.php';

        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname='    . DB_NAME
             . ';charset='   . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production, log this instead of echoing
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    /** Prevent cloning of the singleton */
    private function __clone() {}

    /** Get the singleton instance */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Return the underlying PDO object */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Prepare and execute a query with bound parameters.
     *
     * @param string $sql
     * @param array  $params
     * @return PDOStatement
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Return last inserted ID */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
}
