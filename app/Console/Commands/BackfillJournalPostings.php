<?php

namespace App\Console\Commands;

use App\Models\CreditDebitNote;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\JournalPostingService;
use Illuminate\Console\Command;

class BackfillJournalPostings extends Command
{
    protected $signature = 'journal:backfill {--fresh : Delete & repost all auto-posted journals}';
    protected $description = 'Backfill double-entry journal postings for existing transactions';

    public function handle(JournalPostingService $posting): int
    {
        if ($this->option('fresh')) {
            $this->warn('Deleting existing auto-posted journal entries...');
            \App\Models\JournalEntry::where('is_auto_posted', true)->delete();
        }

        $this->info('Posting invoices...');
        Invoice::orderBy('id')->chunk(200, function ($invoices) use ($posting) {
            foreach ($invoices as $inv) {
                $posting->postInvoice($inv);
            }
            $this->output->write('.');
        });
        $this->newLine();

        $this->info('Posting payments...');
        Payment::orderBy('id')->chunk(200, function ($payments) use ($posting) {
            foreach ($payments as $p) {
                $posting->postPayment($p);
            }
            $this->output->write('.');
        });
        $this->newLine();

        $this->info('Posting expenses...');
        Expense::orderBy('id')->chunk(200, function ($expenses) use ($posting) {
            foreach ($expenses as $e) {
                $posting->postExpense($e);
            }
            $this->output->write('.');
        });
        $this->newLine();

        $this->info('Posting credit/debit notes...');
        CreditDebitNote::orderBy('id')->chunk(200, function ($notes) use ($posting) {
            foreach ($notes as $n) {
                $posting->postCreditDebitNote($n);
            }
            $this->output->write('.');
        });
        $this->newLine();

        $this->info('Backfill complete.');
        return 0;
    }
}
