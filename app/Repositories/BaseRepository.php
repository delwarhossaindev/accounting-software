<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model) {}

    public function model(): Model
    {
        return $this->model;
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(array $with = [], array $orderBy = ['id' => 'desc']): Collection
    {
        $q = $this->query()->with($with);
        foreach ($orderBy as $col => $dir) {
            $q->orderBy($col, $dir);
        }
        return $q->get();
    }

    public function paginate(int $perPage = 20, array $with = [], array $orderBy = ['id' => 'desc']): LengthAwarePaginator
    {
        $q = $this->query()->with($with);
        foreach ($orderBy as $col => $dir) {
            $q->orderBy($col, $dir);
        }
        return $q->paginate($perPage);
    }

    public function find(int $id, array $with = []): ?Model
    {
        return $this->query()->with($with)->find($id);
    }

    public function findOrFail(int $id, array $with = []): Model
    {
        return $this->query()->with($with)->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model|int $model, array $data): Model
    {
        $model = $model instanceof Model ? $model : $this->findOrFail($model);
        $model->update($data);
        return $model->refresh();
    }

    public function delete(Model|int $model): bool
    {
        $model = $model instanceof Model ? $model : $this->findOrFail($model);
        return (bool) $model->delete();
    }

    public function findBy(string $field, mixed $value, array $with = []): ?Model
    {
        return $this->query()->with($with)->where($field, $value)->first();
    }

    public function where(array $conditions, array $with = [], array $orderBy = ['id' => 'desc']): Collection
    {
        $q = $this->query()->with($with);
        foreach ($conditions as $field => $value) {
            is_array($value) ? $q->whereIn($field, $value) : $q->where($field, $value);
        }
        foreach ($orderBy as $col => $dir) {
            $q->orderBy($col, $dir);
        }
        return $q->get();
    }

    public function count(array $conditions = []): int
    {
        $q = $this->query();
        foreach ($conditions as $field => $value) {
            is_array($value) ? $q->whereIn($field, $value) : $q->where($field, $value);
        }
        return $q->count();
    }
}
