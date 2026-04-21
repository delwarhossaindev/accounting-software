<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function model(): Model;

    public function query(): Builder;

    public function all(array $with = [], array $orderBy = ['id' => 'desc']): Collection;

    public function paginate(int $perPage = 20, array $with = [], array $orderBy = ['id' => 'desc']): LengthAwarePaginator;

    public function find(int $id, array $with = []): ?Model;

    public function findOrFail(int $id, array $with = []): Model;

    public function create(array $data): Model;

    public function update(Model|int $model, array $data): Model;

    public function delete(Model|int $model): bool;

    public function findBy(string $field, mixed $value, array $with = []): ?Model;

    public function where(array $conditions, array $with = [], array $orderBy = ['id' => 'desc']): Collection;

    public function count(array $conditions = []): int;
}
