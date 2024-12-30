<?php
namespace PandaCore\Core\Repositories;

interface RepositoryInterface
{
    /**
     * Find a record by ID.
     *
     * @param mixed $id
     * @return mixed
     */
    public function find($id): mixed;

    /**
     * Get all records
     *
     * @return array
     */
    public function all(): array;

    /**
     * Save a record (update or insert)
     *
     * @param array $attributes
     * @return mixed
     */
    public function save(array $attributes): mixed;

    /**
     * Delete a record by ID
     *
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void;

    /**
     * Return all records as an array
     *
     * @return array
     */
    public function getArray(): array;
}
