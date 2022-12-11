<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractService
{
    /**
     * The abstract repository.
     *
     * @var object
     */
    protected $repository;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = $this->resolve();
    }

    /**
     * Get all repository records.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(): LengthAwarePaginator
    {
        return $this->repository->all();
    }

    /**
     * Create a repository record.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * Find a repository record.
     *
     * @param mixed $id
     * @return ?\Illuminate\Database\Eloquent\Model
     */
    public function find(mixed $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Find a model collection where in array.
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findIn(array $ids): Collection
    {
        return $this->repository->findIn($ids);
    }

    /**
     * Find or fail a repository record.
     *
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail(mixed $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Update a repository record.
     *
     * @param array $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, mixed $id): Model
    {
        return $this->repository->update($data, $id);
    }

    /**
     * Delete the repository record.
     *
     * @param mixed $id
     * @return void
     */
    public function delete(mixed $id): void
    {
        $this->repository->delete($id);
    }

    /**
     * Resolve the repository instance.
     *
     * @return
     */
    public function resolve(): object
    {
        return resolve($this->repository);
    }
}
