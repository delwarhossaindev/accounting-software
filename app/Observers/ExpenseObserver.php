<?php

namespace App\Observers;

use App\Models\Expense;
use App\Services\JournalPostingService;

class ExpenseObserver
{
    public function __construct(private JournalPostingService $posting) {}

    public function created(Expense $expense): void
    {
        $this->posting->postExpense($expense->fresh());
    }

    public function updated(Expense $expense): void
    {
        $watched = ['amount', 'account_id', 'payment_method', 'date'];
        if (!empty(array_intersect($watched, array_keys($expense->getChanges())))) {
            $this->posting->postExpense($expense->fresh());
        }
    }

    public function deleted(Expense $expense): void
    {
        $this->posting->reverseExisting(Expense::class, $expense->id);
    }
}
