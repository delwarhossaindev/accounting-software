<?php

namespace App\Providers;

use App\Models\CreditDebitNote;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Observers\CreditDebitNoteObserver;
use App\Observers\ExpenseObserver;
use App\Observers\InvoiceObserver;
use App\Observers\PaymentObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Invoice::observe(InvoiceObserver::class);
        Payment::observe(PaymentObserver::class);
        Expense::observe(ExpenseObserver::class);
        CreditDebitNote::observe(CreditDebitNoteObserver::class);
    }
}
