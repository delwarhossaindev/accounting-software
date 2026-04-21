<?php

namespace App\Repositories\Contracts;

interface RecurringExpenseRepositoryInterface extends BaseRepositoryInterface
{
    public function dueOnOrBefore(string $date);
}
