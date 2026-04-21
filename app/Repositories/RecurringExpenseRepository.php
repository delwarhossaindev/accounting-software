<?php

namespace App\Repositories;

use App\Models\RecurringExpense;
use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;

class RecurringExpenseRepository extends BaseRepository implements RecurringExpenseRepositoryInterface
{
    public function __construct(RecurringExpense $model)
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
