<?php
namespace PandaCore\Core\Database;

use Exception;

class TableQueryBuilder
{
    protected string $table;
    protected array $conditions = [];
    protected array $bindings = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    // Add WHERE condition
    public function where(string $column, string $operator, $value): self
    {
        $this->conditions[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Add AND condition
    public function andWhere(string $column, string $operator, $value): self
    {
        return $this->where($column, $operator, $value);
    }

    // Add OR condition
    public function orWhere(string $column, string $operator, $value): self
    {
        $this->conditions[] = "OR {$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Get all records
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        // WHERE
        $whereConditions = array_filter($this->conditions, fn($condition) => !str_starts_with($condition, 'ORDER BY') && !str_starts_with($condition, 'LIMIT') && !str_starts_with($condition, 'GROUP BY') && !str_starts_with($condition, 'HAVING') && !str_starts_with($condition, 'DISTINCT'));
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }

        // GROUP BY
        $groupBy = array_filter($this->conditions, fn($condition) => str_starts_with($condition, 'GROUP BY'));
        if (!empty($groupBy)) {
            $sql .= " " . implode(' ', $groupBy);
        }

        // HAVING
        $having = array_filter($this->conditions, fn($condition) => str_starts_with($condition, 'HAVING'));
        if (!empty($having)) {
            $sql .= " " . implode(' ', $having);
        }

        // ORDER BY
        $orderBy = array_filter($this->conditions, fn($condition) => str_starts_with($condition, 'ORDER BY'));
        if (!empty($orderBy)) {
            $sql .= " " . implode(', ', $orderBy);
        }

        // LIMIT
        $limit = array_filter($this->conditions, fn($condition) => str_starts_with($condition, 'LIMIT'));
        if (!empty($limit)) {
            $sql .= " " . implode(' ', $limit);
        }

        return DB::query($sql, $this->bindings);
    }

    // Delete a record

    /**
     * @throws Exception
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        return DB::execute($sql, $this->bindings);
    }

    // Add ORDER BY condition
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->conditions[] = "ORDER BY {$column} {$direction}";
        return $this;
    }

    // Add LIMIT condition
    public function limit(int $limit): self
    {
        if ($limit <= 0) {
            throw new InvalidArgumentException('Limit must be a positive integer.');
        }

        $this->conditions[] = "LIMIT {$limit}";
        return $this;
    }

    // Perform COUNT
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $result = DB::query($sql, $this->bindings);
        return $result[0]['COUNT(*)'] ?? 0;
    }

    // Add GROUP BY condition
    public function groupBy(string $column): self
    {
        $this->conditions[] = "GROUP BY {$column}";
        return $this;
    }

    // Perform INNER JOIN
    public function join(string $table, string $onCondition): self
    {
        $this->conditions[] = "INNER JOIN {$table} ON {$onCondition}";
        return $this;
    }

    // Perform LEFT JOIN
    public function leftJoin(string $table, string $onCondition): self
    {
        $this->conditions[] = "LEFT JOIN {$table} ON {$onCondition}";
        return $this;
    }

    // Perform RIGHT JOIN
    public function rightJoin(string $table, string $onCondition): self
    {
        $this->conditions[] = "RIGHT JOIN {$table} ON {$onCondition}";
        return $this;
    }

    // Add DISTINCT condition
    public function distinct(): self
    {
        $this->conditions[] = "DISTINCT";
        return $this;
    }

    // Add HAVING condition
    public function having(string $condition): self
    {
        $this->conditions[] = "HAVING {$condition}";
        return $this;
    }
}
