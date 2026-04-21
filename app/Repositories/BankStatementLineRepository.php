<?php

namespace App\Repositories;

use App\Models\BankStatementLine;
use App\Repositories\Contracts\BankStatementLineRepositoryInterface;

class BankStatementLineRepository extends BaseRepository implements BankStatementLineRepositoryInterface
{
    public function __construct(BankStatementLine $model)
    {
        parent::__construct($model);
    }

    public function forAccountPeriod(int $accountId, string $start, string $end)
    {
        return $this->query()
            ->where('account_id', $accountId)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get();
    }
}
