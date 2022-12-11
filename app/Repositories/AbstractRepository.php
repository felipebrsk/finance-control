<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    /**
     * The quantity of items per page.
     *
     * @var int
     */
    protected const PER_PAGE = 20;

    /**
     * The abstract model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = $this->resolve();
    }

    /**
     * Get all model records.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(): LengthAwarePaginator
    {
        return $this->model->paginate(self::PER_PAGE);
    }

    /**
     * Create a model record.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Find a model record.
     *
     * @param mixed $id
     * @return ?\Illuminate\Database\Eloquent\Model
     */
    public function find(mixed $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a model collection where in array.
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findIn(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Find or fail a model record.
     *
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(mixed $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update a model record.
     *
     * @param array $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, mixed $id): Model
    {
        $model = $this->findOrFail($id);

        $model->update($data);

        return $model;
    }

    /**
     * Delete the model record.
     *
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $model = $this->findOrFail($id);

        $model->delete();
    }

    /**
     * Resolve the model instance.
     *
     * @return object
     */
    public function resolve(): object
    {
        return resolve($this->model);
    }
}
