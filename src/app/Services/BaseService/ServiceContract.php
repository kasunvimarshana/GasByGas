<?php

namespace App\Services\BaseService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface ServiceContract {
    /**
     * Get all records of the model.
     *
     * @return iterable
     */
    public function getAll(): iterable;

    /**
     * Get a record by its ID.
     *
     * @param int $id
     * @return Model
     */
    public function getById(int $id): Model;

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record by its ID.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get a new query builder instance.
     *
     * @return Builder
     */
    public function query(): Builder;
}

