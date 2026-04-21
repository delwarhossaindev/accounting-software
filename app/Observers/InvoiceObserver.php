<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\JournalPostingService;

class InvoiceObserver
{
    public function __construct(private JournalPostingService $posting) {}

    public function created(Invoice $invoice): void
    {
        $this->posting->postInvoice($invoice->fresh());
    }

    public function updated(Invoice $invoice): void
    {
        $watched = ['total', 'subtotal', 'tax', 'discount', 'status', 'date', 'type'];
        if (!empty(array_intersect($watched, array_keys($invoice->getChanges())))) {
            $this->posting->postInvoice($invoice->fresh());
        }
    }

    public function deleted(Invoice $invoice): void
    {
        $this->posting->reverseExisting(Invoice::class, $invoice->id);
    }
}
