<?php

namespace App\Repositories;

use App\Models\RecurringInvoice;
use App\Repositories\Contracts\RecurringInvoiceRepositoryInterface;

class RecurringInvoiceRepository extends BaseRepository implements RecurringInvoiceRepositoryInterface
{
    public function __construct(RecurringInvoice $model)
    {
        parent::__construct($model);
    }

    public function dueOnOrBefore(string $date)
    {
        return $this->query()
            ->where('is_active', true)
            ->where('next_run_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            ->get();
    }
}
