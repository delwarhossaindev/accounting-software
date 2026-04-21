<?php

namespace App\Repositories;

use App\Models\BankReconciliation;
use App\Repositories\Contracts\BankReconciliationRepositoryInterface;

class BankReconciliationRepository extends BaseRepository implements BankReconciliationRepositoryInterface
{
    public function __construct(BankReconciliation $model)
    {
        parent::__construct($model);
    }

    public function historyForAccount(int $accountId, int $limit = 10)
    {
        return $this->query()
            ->with('account')
            ->where('account_id', $accountId)
            ->latest('end_date')
            ->take($limit)
            ->get();
    }
}
