<?php

namespace App\Repositories\Contracts;

interface BankReconciliationRepositoryInterface extends BaseRepositoryInterface
{
    public function historyForAccount(int $accountId, int $limit = 10);
}
