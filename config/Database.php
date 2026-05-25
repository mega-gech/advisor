<?php

declare(strict_types=1);

class Database {
    private static ?self $instance = null;
    private ?PDO $connection = null;

    private function __construct() {
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect(): PDO {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $config = require CONFIG_PATH . '/app.php';
        $db = $config['database'];

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $db['host'],
            $db['name']
        );

        try {
            $this->connection = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            $debug = filter_var(getenv('APP_DEBUG') ?: '0', FILTER_VALIDATE_BOOLEAN);
            if ($debug) {
                echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
            } else {
                echo 'Database connection failed. Check config and that MySQL is running.';
            }
            exit;
        }

        return $this->connection;
    }
}
