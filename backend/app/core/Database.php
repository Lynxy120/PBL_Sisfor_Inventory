<?php

require_once __DIR__ . '/Env.php';

class Database
{
    private static ?PDO $conn = null;

    public static function connect(): PDO
    {
        if (self::$conn === null) {
            $dsn = "mysql:host=" . Env::get('DB_HOST', 'localhost') .
                ";dbname=" . Env::get('DB_NAME') . ";charset=utf8";

            self::$conn = new PDO(
                $dsn,
                Env::get('DB_USER', 'root'),
                Env::get('DB_PASS', '')
            );
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$conn;
    }

    public static function beginTransaction()
    {
        self::connect()->beginTransaction();
    }

    public static function commit()
    {
        self::connect()->commit();
    }

    public static function rollBack()
    {
        self::connect()->rollBack();
    }
}
