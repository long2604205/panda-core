<?php
namespace PandaCore\Core\Repositories;

use PandaCore\Core\Models\Model;

class Repository implements RepositoryInterface
{
    protected mixed $model = null;

    public function __construct()
    {
        $this->model = new $this->model();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all(): array
    {
        return $this->model->all();
    }

    public function save(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this->model, $key)) {
                $this->model->$key = $value;
            }
        }

        $this->model->save();
        return $this->model;
    }

    public function delete(mixed $id): void
    {
        $record = $this->find($id);
        if ($record) {
            $record->delete();
        }
    }

    public function getArray(): array
    {
        return $this->model->toArray();
    }
}