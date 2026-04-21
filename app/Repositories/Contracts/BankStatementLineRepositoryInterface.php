<?php

namespace App\Repositories\Contracts;

interface BankStatementLineRepositoryInterface extends BaseRepositoryInterface
{
    public function forAccountPeriod(int $accountId, string $start, string $end);
}
