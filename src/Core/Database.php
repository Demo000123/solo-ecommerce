<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;
    private bool $inTransaction = false;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['DB_NAME'] ?? 'solo_ecommerce';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // For development, you might want to display the error
            if ($_ENV['APP_ENV'] === 'development') {
                die('Database connection error: ' . $e->getMessage());
            } else {
                // For production, log the error but show a generic message
                error_log('Database connection error: ' . $e->getMessage());
                die('Database connection failed. Please try again later.');
            }
        }
    }

    public function query(string $sql, array $params = []): array
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            
            return $statement->fetchAll();
        } catch (PDOException $e) {
            $this->handleError($e);
            return [];
        }
    }

    public function execute(string $sql, array $params = []): bool
    {
        try {
            $statement = $this->connection->prepare($sql);
            return $statement->execute($params);
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    public function insert(string $sql, array $params = []): int
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            
            return (int)$this->connection->lastInsertId();
        } catch (PDOException $e) {
            $this->handleError($e);
            return 0;
        }
    }

    public function beginTransaction(): bool
    {
        if (!$this->inTransaction) {
            $this->inTransaction = $this->connection->beginTransaction();
            return $this->inTransaction;
        }
        
        return false;
    }

    public function commit(): bool
    {
        if ($this->inTransaction) {
            $this->inTransaction = false;
            return $this->connection->commit();
        }
        
        return false;
    }

    public function rollback(): bool
    {
        if ($this->inTransaction) {
            $this->inTransaction = false;
            return $this->connection->rollBack();
        }
        
        return false;
    }

    private function handleError(PDOException $e): void
    {
        // For development, you might want to display the error
        if ($_ENV['APP_ENV'] === 'development') {
            throw $e;
        } else {
            // For production, log the error but don't expose it
            error_log('Database query error: ' . $e->getMessage());
        }
    }
} 