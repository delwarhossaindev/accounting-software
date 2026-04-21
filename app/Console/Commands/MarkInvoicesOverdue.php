<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class MarkInvoicesOverdue extends Command
{
    protected $signature = 'invoices:mark-overdue';
    protected $description = 'Flip past-due unpaid invoices to "overdue" status';

    public function handle(): int
    {
        $count = Invoice::where('type', 'sales')
            ->where('due', '>', 0)
            ->whereIn('status', ['sent', 'partial'])
            ->whereDate('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} invoice(s) as overdue.");
        return 0;
    }
}
