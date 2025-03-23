<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$config = require CONFIG_PATH . '/config.php';
            $dbConfig = self::$config['database'];
            
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
            
            try {
                self::$instance = new PDO(
                    $dsn, 
                    $dbConfig['username'], 
                    $dbConfig['password'], 
                    $dbConfig['options']
                );
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage());
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
} 