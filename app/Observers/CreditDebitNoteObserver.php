<?php

namespace App\Observers;

use App\Models\CreditDebitNote;
use App\Services\JournalPostingService;

class CreditDebitNoteObserver
{
    public function __construct(private JournalPostingService $posting) {}

    public function created(CreditDebitNote $note): void
    {
        $this->posting->postCreditDebitNote($note->fresh());
    }

    public function updated(CreditDebitNote $note): void
    {
        $watched = ['total', 'subtotal', 'tax', 'type', 'date'];
        if (!empty(array_intersect($watched, array_keys($note->getChanges())))) {
            $this->posting->postCreditDebitNote($note->fresh());
        }
    }

    public function deleted(CreditDebitNote $note): void
    {
        $this->posting->reverseExisting(CreditDebitNote::class, $note->id);
    }
}
