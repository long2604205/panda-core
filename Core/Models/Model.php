<?php
namespace Core\Models;

use Helpers\DatabaseManager;
use PDO;
use PDOException;
use Exception;

class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    
    protected array $fillable = [];
    private PDO $connection;
    
    public function __construct()
    {
        $this->connection = DatabaseManager::getInstance()->getConnection();
    }

    public function find($id): ?static
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $this->mapToModel($data);
        }
        return null;
    }

    public function all(): array
    {
        $stmt = $this->connection->query("SELECT * FROM {$this->table}");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapToModel'], $results);
    }

    public function save(): void
    {
        $columns = $this->fillable;
        $values = [];
        foreach ($columns as $col) {
            $values[$col] = $this->$col ?? null;
        }

        if (isset($this->{$this->primaryKey})) {
            // Update
            $set = implode(', ', array_map(fn($col) => "$col = :$col", $columns));
            $stmt = $this->connection->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = :{$this->primaryKey}");
            $values[$this->primaryKey] = $this->{$this->primaryKey};
        } else {
            // Insert
            $columnsString = implode(', ', $columns);
            $placeholders = implode(', ', array_map(fn($col) => ":$col", $columns));
            $stmt = $this->connection->prepare("INSERT INTO {$this->table} ($columnsString) VALUES ($placeholders)");
        }

        $stmt->execute($values);

        if (!isset($this->{$this->primaryKey})) {
            $this->{$this->primaryKey} = $this->connection->lastInsertId();
        }
    }

    public function delete(): void
    {
        if (isset($this->{$this->primaryKey})) {
            $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
            $stmt->execute(['id' => $this->{$this->primaryKey}]);
        }
    }

    public function get($rawResults): array
    {
        $mappedResults = [];
        foreach ($rawResults as $data) {
            $mappedResults[] = $this->mapToModel($data);
        }
        return $mappedResults;
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->fillable as $field) {
            if (property_exists($this, $field)) {
                $data[$field] = $this->$field;
            }
        }
        return $data;
    }

    public function unSetAttributes(): void
    {
        foreach ($this->fillable as $field) {
            if (property_exists($this, $field)) {
                $this->$field = null;
            }
        }
    }

    private function mapToModel($data): static
    {
        $model = new static();

        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $model->$field = $data[$field];
            }
        }

        return $model;
    }
}
