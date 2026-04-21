<?php

namespace App\Services;

use App\Models\Account;
use App\Models\CreditDebitNote;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class JournalPostingService
{
    private array $accountCache = [];

    public function postInvoice(Invoice $invoice): ?JournalEntry
    {
        $this->reverseExisting(Invoice::class, $invoice->id);

        if (in_array($invoice->status, ['draft', 'cancelled'])) {
            return null;
        }

        $subtotal = (float) $invoice->subtotal;
        $tax = (float) $invoice->tax;
        $discount = (float) $invoice->discount;
        $total = (float) $invoice->total;

        if ($total <= 0) return null;

        $items = [];

        if ($invoice->type === 'sales') {
            $ar = $this->accountId('1003');
            $salesRevenue = $this->accountId('4001');
            $taxPayable = $this->accountId('2003');
            $discountGiven = $this->accountId('5108');

            if (!$ar || !$salesRevenue) return null;

            $items[] = ['account_id' => $ar, 'debit' => $total, 'credit' => 0, 'description' => 'A/R for invoice ' . $invoice->invoice_no];
            $items[] = ['account_id' => $salesRevenue, 'debit' => 0, 'credit' => $subtotal, 'description' => 'Sales revenue'];

            if ($tax > 0 && $taxPayable) {
                $items[] = ['account_id' => $taxPayable, 'debit' => 0, 'credit' => $tax, 'description' => 'Tax on sales'];
            }
            if ($discount > 0 && $discountGiven) {
                $items[] = ['account_id' => $discountGiven, 'debit' => $discount, 'credit' => 0, 'description' => 'Discount given'];
            }
        } else {
            $ap = $this->accountId('2001');
            $purchase = $this->accountId('5002');
            $taxPayable = $this->accountId('2003');

            if (!$ap || !$purchase) return null;

            $items[] = ['account_id' => $purchase, 'debit' => $subtotal - $discount, 'credit' => 0, 'description' => 'Purchase for bill ' . $invoice->invoice_no];
            if ($tax > 0 && $taxPayable) {
                $items[] = ['account_id' => $taxPayable, 'debit' => $tax, 'credit' => 0, 'description' => 'Input tax'];
            }
            $items[] = ['account_id' => $ap, 'debit' => 0, 'credit' => $total, 'description' => 'A/P for bill ' . $invoice->invoice_no];
        }

        return $this->writeJournal([
            'date' => $invoice->date,
            'voucher_type' => $invoice->type === 'sales' ? 'sales' : 'purchase',
            'narration' => ($invoice->type === 'sales' ? 'Sales Invoice ' : 'Purchase Bill ') . $invoice->invoice_no,
            'user_id' => $invoice->user_id,
            'source_type' => Invoice::class,
            'source_id' => $invoice->id,
        ], $items);
    }

    public function postPayment(Payment $payment): ?JournalEntry
    {
        $this->reverseExisting(Payment::class, $payment->id);

        $amount = (float) $payment->amount;
        if ($amount <= 0 || !$payment->account_id) return null;

        $cashBankAccount = $payment->account_id;

        if ($payment->type === 'received') {
            $ar = $this->accountId('1003');
            if (!$ar) return null;

            $items = [
                ['account_id' => $cashBankAccount, 'debit' => $amount, 'credit' => 0, 'description' => 'Cash/Bank received'],
                ['account_id' => $ar, 'debit' => 0, 'credit' => $amount, 'description' => 'Clear A/R'],
            ];
        } else {
            $ap = $this->accountId('2001');
            if (!$ap) return null;

            $items = [
                ['account_id' => $ap, 'debit' => $amount, 'credit' => 0, 'description' => 'Clear A/P'],
                ['account_id' => $cashBankAccount, 'debit' => 0, 'credit' => $amount, 'description' => 'Cash/Bank paid'],
            ];
        }

        return $this->writeJournal([
            'date' => $payment->date,
            'voucher_type' => $payment->type === 'received' ? 'receipt' : 'payment',
            'narration' => ($payment->type === 'received' ? 'Payment received ' : 'Payment made ') . $payment->payment_no,
            'user_id' => $payment->user_id,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
        ], $items);
    }

    public function postExpense(Expense $expense): ?JournalEntry
    {
        $this->reverseExisting(Expense::class, $expense->id);

        $amount = (float) $expense->amount;
        if ($amount <= 0 || !$expense->account_id) return null;

        if ($expense->payment_method === 'credit' && $expense->supplier_id) {
            $ap = $this->accountId('2001');
            if (!$ap) return null;
            $creditAccount = $ap;
            $creditDescription = 'Payable to supplier';
        } else {
            $creditAccount = $expense->payment_method === 'bank_transfer' || $expense->payment_method === 'cheque'
                ? $this->accountId('1002')
                : $this->accountId('1001');
            if (!$creditAccount) return null;
            $creditDescription = 'Cash/Bank paid';
        }

        $items = [
            ['account_id' => $expense->account_id, 'debit' => $amount, 'credit' => 0, 'description' => $expense->description ?: 'Expense'],
            ['account_id' => $creditAccount, 'debit' => 0, 'credit' => $amount, 'description' => $creditDescription],
        ];

        return $this->writeJournal([
            'date' => $expense->date,
            'voucher_type' => 'payment',
            'narration' => 'Expense ' . $expense->expense_no,
            'user_id' => $expense->user_id,
            'source_type' => Expense::class,
            'source_id' => $expense->id,
        ], $items);
    }

    public function postCreditDebitNote(CreditDebitNote $note): ?JournalEntry
    {
        $this->reverseExisting(CreditDebitNote::class, $note->id);

        $subtotal = (float) $note->subtotal;
        $tax = (float) $note->tax;
        $total = (float) $note->total;
        if ($total <= 0) return null;

        if ($note->type === 'credit') {
            $ar = $this->accountId('1003');
            $sales = $this->accountId('4001');
            $taxPayable = $this->accountId('2003');
            if (!$ar || !$sales) return null;

            $items = [
                ['account_id' => $sales, 'debit' => $subtotal, 'credit' => 0, 'description' => 'Sales return'],
            ];
            if ($tax > 0 && $taxPayable) {
                $items[] = ['account_id' => $taxPayable, 'debit' => $tax, 'credit' => 0, 'description' => 'Tax on return'];
            }
            $items[] = ['account_id' => $ar, 'debit' => 0, 'credit' => $total, 'description' => 'Reduce A/R'];
        } else {
            $ap = $this->accountId('2001');
            $purchase = $this->accountId('5002');
            $taxPayable = $this->accountId('2003');
            if (!$ap || !$purchase) return null;

            $items = [
                ['account_id' => $ap, 'debit' => $total, 'credit' => 0, 'description' => 'Reduce A/P'],
                ['account_id' => $purchase, 'debit' => 0, 'credit' => $subtotal, 'description' => 'Purchase return'],
            ];
            if ($tax > 0 && $taxPayable) {
                $items[] = ['account_id' => $taxPayable, 'debit' => 0, 'credit' => $tax, 'description' => 'Tax reversal'];
            }
        }

        return $this->writeJournal([
            'date' => $note->date,
            'voucher_type' => 'journal',
            'narration' => ($note->type === 'credit' ? 'Credit Note ' : 'Debit Note ') . $note->note_no,
            'user_id' => $note->user_id,
            'source_type' => CreditDebitNote::class,
            'source_id' => $note->id,
        ], $items);
    }

    public function reverseExisting(string $sourceType, int $sourceId): void
    {
        JournalEntry::where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->where('is_auto_posted', true)
            ->get()
            ->each
            ->delete();
    }

    protected function writeJournal(array $header, array $items): ?JournalEntry
    {
        return DB::transaction(function () use ($header, $items) {
            $header['voucher_no'] = JournalEntry::generateVoucherNo($header['voucher_type']);
            $header['total_amount'] = collect($items)->sum('debit');
            $header['is_auto_posted'] = true;

            $entry = JournalEntry::create($header);

            foreach ($items as $item) {
                if (!empty($item['account_id'])) {
                    JournalEntryItem::create(array_merge($item, ['journal_entry_id' => $entry->id]));
                }
            }

            return $entry;
        });
    }

    protected function accountId(string $code): ?int
    {
        if (!array_key_exists($code, $this->accountCache)) {
            $this->accountCache[$code] = Account::where('code', $code)->value('id');
        }
        return $this->accountCache[$code];
    }
}
