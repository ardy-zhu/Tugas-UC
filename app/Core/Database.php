<?php

namespace App\Core;

use mysqli;

final class Database
{
    private static ?self $instance = null;

    private mysqli $connection;

    private function __construct()
    {
        $config = require APP_ROOT . '/config/database.php';

        $this->connection = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database']
        );

        if ($this->connection->connect_error) {
            throw new \RuntimeException('Database connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset($config['charset']);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
