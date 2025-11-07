<?php

class db {
    private $pdo;

    public function __construct(array $opts = []) {

        $host = $opts['host'] ?? getenv('DB_HOST') ?: '127.0.0.1';
        $port = $opts['port'] ?? getenv('DB_PORT') ?: '3306';
        $user = $opts['user'] ?? getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root';
        $password = $opts['password'] ?? getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: '';
        $dbname = $opts['database'] ?? getenv('DB_DATABASE') ?: 'mydatabase';
        $charset = $opts['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

        try {
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function get_connection(): PDO {
        return $this->pdo;
    }
    
    public static function test(array $opts = []): string {
        try {
            $db = new self($opts);
            $stmt = $db->get_connection()->query('SELECT 1');
            $val = $stmt ? $stmt->fetchColumn() : false;
            if ($val !== false) {
                return 'OK';
            }
            return 'NO RESULT';
        } catch (Throwable $e) {
            return 'ERR: ' . $e->getMessage();
        }
    }

}