<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\RecurringExpense;
use App\Models\RecurringInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunRecurringTransactions extends Command
{
    protected $signature = 'recurring:run {--date= : Override the run date (YYYY-MM-DD)}';
    protected $description = 'Generate invoices & expenses from due recurring schedules';

    public function handle(): int
    {
        $today = $this->option('date') ? \Carbon\Carbon::parse($this->option('date')) : now();

        $this->info('Processing recurring invoices due on or before ' . $today->toDateString() . '...');
        $inv = RecurringInvoice::where('is_active', true)
            ->where('next_run_date', '<=', $today->toDateString())
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today->toDateString());
            })->get();

        $invoiceCount = 0;
        foreach ($inv as $r) {
            while ($r->next_run_date <= $today && $r->is_active && (!$r->end_date || $r->next_run_date <= $r->end_date)) {
                DB::transaction(function () use ($r, &$invoiceCount) {
                    $items = $r->items ?? [];
                    $subtotal = collect($items)->sum(fn($i) => ((float)($i['quantity'] ?? 1)) * ((float)($i['unit_price'] ?? 0)));
                    $tax = round($subtotal * ((float)$r->tax_rate) / 100, 2);
                    $discount = (float) $r->discount;
                    $total = $subtotal + $tax - $discount;

                    $invoice = Invoice::create([
                        'invoice_no'  => Invoice::generateInvoiceNo($r->type),
                        'type'        => $r->type,
                        'date'        => $r->next_run_date,
                        'due_date'    => $r->next_run_date->copy()->addDays(30),
                        'customer_id' => $r->customer_id,
                        'supplier_id' => $r->supplier_id,
                        'branch_id'   => $r->branch_id,
                        'subtotal'    => $subtotal,
                        'tax'         => $tax,
                        'discount'    => $discount,
                        'total'       => $total,
                        'paid'        => 0,
                        'due'         => $total,
                        'status'      => 'sent',
                        'notes'       => $r->notes,
                        'user_id'     => $r->user_id,
                    ]);

                    foreach ($items as $item) {
                        InvoiceItem::create([
                            'invoice_id'  => $invoice->id,
                            'description' => $item['description'] ?? 'Item',
                            'quantity'    => $item['quantity'] ?? 1,
                            'unit_price'  => $item['unit_price'] ?? 0,
                            'amount'      => ((float)($item['quantity'] ?? 1)) * ((float)($item['unit_price'] ?? 0)),
                            'account_id'  => $item['account_id'] ?? null,
                        ]);
                    }

                    $r->increment('generated_count');
                    $r->advanceNextRun();
                    $r->save();
                    $invoiceCount++;
                });
                $r->refresh();
            }
        }
        $this->info("Generated {$invoiceCount} invoice(s).");

        $this->info('Processing recurring expenses...');
        $exps = RecurringExpense::where('is_active', true)
            ->where('next_run_date', '<=', $today->toDateString())
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today->toDateString());
            })->get();

        $expenseCount = 0;
        foreach ($exps as $r) {
            while ($r->next_run_date <= $today && $r->is_active && (!$r->end_date || $r->next_run_date <= $r->end_date)) {
                DB::transaction(function () use ($r, &$expenseCount) {
                    Expense::create([
                        'expense_no'     => Expense::generateExpenseNo(),
                        'date'           => $r->next_run_date,
                        'account_id'     => $r->account_id,
                        'supplier_id'    => $r->supplier_id,
                        'amount'         => $r->amount,
                        'category'       => $r->category,
                        'payment_method' => $r->payment_method,
                        'description'    => $r->description ?: $r->name,
                        'user_id'        => $r->user_id,
                    ]);

                    $r->increment('generated_count');
                    $r->advanceNextRun();
                    $r->save();
                    $expenseCount++;
                });
                $r->refresh();
            }
        }
        $this->info("Generated {$expenseCount} expense(s).");

        return 0;
    }
}
