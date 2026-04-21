<?php

namespace App\Repositories\Contracts;

use App\Models\Account;

interface AccountRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCode(string $code): ?Account;

    public function activeOrdered();

    public function byType(string $type);
}
