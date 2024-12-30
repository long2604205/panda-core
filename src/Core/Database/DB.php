<?php
namespace PandaCore\Core\Database;

use PandaCore\Helpers\DatabaseManager;
use PDO;
use PDOException;
use Exception;

class DB
{
    protected static ?DatabaseManager $database = null;

    public function __construct()
    {
        if (static::$database === null) {
            static::$database = DatabaseManager::getInstance();
        }
    }

    // Run raw SQL with parameters

    /**
     * @throws Exception
     */
    public static function query(string $sql, array $params = []): array
    {
        try {
            $statement = static::getConnection()->prepare($sql);
            $statement->execute($params);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Error executing query: ' . $e->getMessage());
        }
    }

    // Run raw SQL without expecting a result (INSERT/UPDATE/DELETE).

    /**
     * @throws Exception
     */
    public static function execute(string $sql, array $params = []): bool
    {
        try {
            $statement = static::getConnection()->prepare($sql);
            return $statement->execute($params);
        } catch (PDOException $e) {
            throw new Exception('Error executing query: ' . $e->getMessage());
        }
    }

    // Query with table
    public static function table(string $table): TableQueryBuilder
    {
        return new TableQueryBuilder($table);
    }

    // Get database connection
    protected static function getConnection(): PDO
    {
        if (static::$database === null) {
            static::$database = DatabaseManager::getInstance();
        }
        return static::$database->getConnection();
    }
}
