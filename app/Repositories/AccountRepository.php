<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code): ?Account
    {
        return $this->query()->where('code', $code)->first();
    }

    public function activeOrdered()
    {
        return $this->query()->where('is_active', true)->orderBy('code')->get();
    }

    public function byType(string $type)
    {
        return $this->query()->where('type', $type)->orderBy('code')->get();
    }
}
