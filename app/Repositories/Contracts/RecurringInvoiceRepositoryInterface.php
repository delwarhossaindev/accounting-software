<?php

namespace App\Repositories\Contracts;

interface RecurringInvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function dueOnOrBefore(string $date);
}
