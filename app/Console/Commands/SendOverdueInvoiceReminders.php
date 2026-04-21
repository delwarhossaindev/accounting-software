<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\InvoiceOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendOverdueInvoiceReminders extends Command
{
    protected $signature = 'invoices:overdue-reminders {--days= : Send only when days past due matches a specific step (e.g. 7,14,30)}';
    protected $description = 'Email customers with overdue sales invoices';

    public function handle(): int
    {
        $today = Carbon::today();
        $steps = $this->option('days') ? array_map('intval', explode(',', $this->option('days'))) : [7, 14, 30];

        $invoices = Invoice::with('customer')
            ->where('type', 'sales')
            ->where('due', '>', 0)
            ->whereNotNull('due_date')
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->whereDate('due_date', '<', $today)
            ->get();

        $sent = 0;
        foreach ($invoices as $invoice) {
            $days = $today->diffInDays(Carbon::parse($invoice->due_date));
            if (!in_array($days, $steps, true)) continue;
            if (!$invoice->customer?->email) continue;

            Notification::route('mail', $invoice->customer->email)
                ->notify(new InvoiceOverdueNotification($invoice, $days));
            $sent++;
        }

        $this->info("Dispatched {$sent} reminder(s).");
        return 0;
    }
}
