<?php
// Opens the MySQL database connection used by the models.

class Database
{
    public static function connect()
    {
        // Local XAMPP defaults, override with environment variables.
        $host   = getenv('DB_HOST') ?: '127.0.0.1';
        $port   = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'contact_manager';
        $user   = getenv('DB_USER') ?: 'root';
        $pass   = getenv('DB_PASS') ?: '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            exit('Database connection failed. Please try again later.');
        }
    }
}
