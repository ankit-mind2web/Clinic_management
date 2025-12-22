<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $conn = null;

    public static function getConnection(): PDO
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $envPath = dirname(__DIR__) . '/.env';

        if (!file_exists($envPath)) {
            die('.env file not found');
        }

        $env = parse_ini_file($envPath);

        if ($env === false) {
            die('Failed to read .env file');
        }

        $host    = $env['DB_HOST'];
        $dbname  = $env['DB_NAME'];
        $user    = $env['DB_USER'];
        $pass    = $env['DB_PASS'];
        $charset = $env['DB_CHARSET'];

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

        try {
            self::$conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed');
        }

        return self::$conn;
    }
}
