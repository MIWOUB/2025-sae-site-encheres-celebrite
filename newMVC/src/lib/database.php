<?php
class DatabaseConnection
{
    private static ?PDO $pdo = null;
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('DB_HOST') ?: 'db';
            $dbname = getenv('DB_NAME') ?: 'auction_site';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: 'root';
            $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

            self::$pdo = new PDO(
                'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset,
                $username,
                $password
            );
            // Permet de lancer une exception si le pdo a un probleme (requete SQL, connection, ...)
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->exec("SET time_zone = '" . date('P') . "'");
        }
        return self::$pdo;
    }
}
