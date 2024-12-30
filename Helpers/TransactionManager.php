<?php

namespace Helpers;

use Exception;

class TransactionManager
{
    private $pdo;

    /**
     * Constructor nhận đối tượng PDO từ DatabaseManager
     */
    public function __construct()
    {
        $this->pdo = DatabaseManager::getInstance()->getConnection();
    }

    /**
     * Bắt đầu transaction
     */
    public function beginTransaction(): void
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }

    /**
     * Commit transaction
     */
    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }

    /**
     * Rollback transaction
     */
    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    /**
     * Thực thi một callback trong transaction
     *
     * @param callable $callback
     * @return mixed
     * @throws Exception
     */
    public function transaction(callable $callback)
    {
        try {
            $this->beginTransaction();
            $result = $callback($this->pdo); // Truyền PDO vào callback nếu cần
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
