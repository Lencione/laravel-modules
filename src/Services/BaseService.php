<?php

namespace Lencione\LaravelModules\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(?int $perPage = null): LengthAwarePaginator
    {
        return $this->model->orderBy('id')->paginate($perPage);
    }

    public function getAllWithoutPagination(): Collection
    {
        return $this->model->orderBy('id')->get();
    }

    public function store(array $validated): Model
    {
        return $this->model->create($validated);
    }

    public function getById(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function update(int|string $id, array $validated): Model
    {
        $item = $this->getById($id);
        $item->update($validated);

        return $item;
    }

    public function delete(int|string $id): void
    {
        $this->getById($id)->delete();
    }
}
