<?php

namespace App\Services\BaseService;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\BaseService\ServiceContract;

abstract class BaseService implements ServiceContract {
    // Abstract model property, to be defined by child classes
    protected Model $model;

    // Constructor should accept the model and instantiate it
    public function __construct(Model $model) {
        $this->model = $model;
    }

    // Common CRUD operations
    /**
     * Get all records of the model.
     *
     * @return iterable
     */
    public function getAll(): iterable {
        return $this->model->all();
    }

    /**
     * Get a record by its ID.
     *
     * @param int $id
     * @return Model
     */
    public function getById(int $id): Model {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model {
        return DB::transaction(function () use ($data) {
            try {
                $this->beforeSave($data);
                $model = $this->model->create($data);
                return $model;
            } catch (Exception $e) {
                $this->handleException($e);
            }
        });
    }

    /**
     * Update a record by its ID.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model {
        return DB::transaction(function () use ($data, $id) {
            try {
                $this->beforeSave($data);
                $model = $this->getById($id);
                $model->update($data);
                return $model;
            } catch (Exception $e) {
                $this->handleException($e);
            }
        });
    }

    /**
     * Delete a record by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        return DB::transaction(function () use ($id) {
            try {
                $model = $this->getById($id);
                return $model->delete();
            } catch (Exception $e) {
                $this->handleException($e);
            }
        });
    }

    /**
     * Get a new query builder instance.
     *
     * @return Builder
     */
    public function query(): Builder {
        return $this->model->newQuery();
    }

    // Allow subclasses to define any logic before saving, e.g., password hashing
    protected function beforeSave(array &$data): void {
        // Abstract method to be implemented by subclasses
    }

    protected function handleException(Exception $e) {
        // Log::error($e->getMessage(), ['exception' => $e]);
        throw $e;
    }

}
