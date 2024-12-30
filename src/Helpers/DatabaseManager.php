<?php
namespace PandaCore\Helpers;

use PDO;
use PDOException;
use PandaCore\Config\Config;

class DatabaseManager {
    private static ?DatabaseManager $instance = null;
    private ?PDO $pdo = null;


    private function __construct()
    {
        try {
            $host = Config::get('DB_HOST');
            $dbname = Config::get('DB_DATABASE');
            $port = Config::get('DB_PORT');
            $username = Config::get('DB_USERNAME');
            $password = Config::get('DB_PASSWORD');

            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8",
                $username,
                $password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit('Error database connection: ' . $e->getMessage());
        }
    }

    public static function getInstance(): ?DatabaseManager
    {
        if (!self::$instance) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
    public function disconnect(): void
    {
        $this->pdo = null;
        self::$instance = null;
    }
}
