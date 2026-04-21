<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\JournalPostingService;

class PaymentObserver
{
    public function __construct(private JournalPostingService $posting) {}

    public function created(Payment $payment): void
    {
        $this->posting->postPayment($payment->fresh());
    }

    public function updated(Payment $payment): void
    {
        $watched = ['amount', 'account_id', 'type', 'date'];
        if (!empty(array_intersect($watched, array_keys($payment->getChanges())))) {
            $this->posting->postPayment($payment->fresh());
        }
    }

    public function deleted(Payment $payment): void
    {
        $this->posting->reverseExisting(Payment::class, $payment->id);
    }
}
