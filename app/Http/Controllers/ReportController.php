<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JournalEntryItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $accounts = Account::all()->map(function ($account) use ($date) {
            $items = $account->journalEntryItems()
                ->whereHas('journalEntry', function ($q) use ($date) {
                    $q->where('date', '<=', $date);
                });

            $debit = $items->sum('debit') + (in_array($account->type, ['asset', 'expense']) ? $account->opening_balance : 0);
            $credit = $items->sum('credit') + (in_array($account->type, ['liability', 'equity', 'income']) ? $account->opening_balance : 0);

            return [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => $debit,
                'credit' => $credit,
            ];
        })->filter(function ($a) {
            return $a['debit'] > 0 || $a['credit'] > 0;
        });

        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');

        return view('reports.trial-balance', compact('accounts', 'totalDebit', 'totalCredit', 'date'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $incomeAccounts = Account::where('type', 'income')->get()->map(function ($account) use ($startDate, $endDate) {
            $balance = $account->journalEntryItems()
                ->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });

            $amount = $balance->sum('credit') - $balance->sum('debit');

            return [
                'name' => $account->name,
                'amount' => $amount,
            ];
        })->filter(fn($a) => $a['amount'] != 0);

        $expenseAccounts = Account::where('type', 'expense')->get()->map(function ($account) use ($startDate, $endDate) {
            $balance = $account->journalEntryItems()
                ->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                });

            $amount = $balance->sum('debit') - $balance->sum('credit');

            return [
                'name' => $account->name,
                'amount' => $amount,
            ];
        })->filter(fn($a) => $a['amount'] != 0);

        $totalIncome = $incomeAccounts->sum('amount');
        $totalExpenses = $expenseAccounts->sum('amount');
        $netIncome = $totalIncome - $totalExpenses;

        return view('reports.income-statement', compact(
            'incomeAccounts', 'expenseAccounts', 'totalIncome', 'totalExpenses', 'netIncome', 'startDate', 'endDate'
        ));
    }

    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $getAccountsByType = function ($type) use ($date) {
            return Account::where('type', $type)->get()->map(function ($account) use ($date) {
                $items = $account->journalEntryItems()
                    ->whereHas('journalEntry', function ($q) use ($date) {
                        $q->where('date', '<=', $date);
                    });

                $debit = $items->sum('debit');
                $credit = $items->sum('credit');

                if (in_array($account->type, ['asset', 'expense'])) {
                    $balance = $account->opening_balance + $debit - $credit;
                } else {
                    $balance = $account->opening_balance + $credit - $debit;
                }

                return [
                    'name' => $account->name,
                    'balance' => $balance,
                ];
            })->filter(fn($a) => $a['balance'] != 0);
        };

        $assets = $getAccountsByType('asset');
        $liabilities = $getAccountsByType('liability');
        $equity = $getAccountsByType('equity');

        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        return view('reports.balance-sheet', compact(
            'assets', 'liabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'date'
        ));
    }

    public function agedReceivables(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->format('Y-m-d'));

        $customers = Customer::with(['invoices' => function ($q) use ($asOfDate) {
            $q->where('type', 'sales')
              ->where('date', '<=', $asOfDate)
              ->whereIn('status', ['sent', 'partial', 'overdue', 'paid'])
              ->where('due', '>', 0);
        }])->get();

        $report = $customers->map(function ($customer) use ($asOfDate) {
            $buckets = ['current' => 0, '1_30' => 0, '31_60' => 0, '61_90' => 0, 'over_90' => 0];

            foreach ($customer->invoices as $invoice) {
                $dueDate = $invoice->due_date ?? $invoice->date;
                $daysPast = \Carbon\Carbon::parse($dueDate)->diffInDays(\Carbon\Carbon::parse($asOfDate), false);

                if ($daysPast <= 0) $buckets['current'] += $invoice->due;
                elseif ($daysPast <= 30) $buckets['1_30'] += $invoice->due;
                elseif ($daysPast <= 60) $buckets['31_60'] += $invoice->due;
                elseif ($daysPast <= 90) $buckets['61_90'] += $invoice->due;
                else $buckets['over_90'] += $invoice->due;
            }

            $total = array_sum($buckets);

            return [
                'customer' => $customer,
                'buckets' => $buckets,
                'total' => $total,
            ];
        })->filter(fn($r) => $r['total'] > 0)->values();

        $totals = [
            'current' => $report->sum(fn($r) => $r['buckets']['current']),
            '1_30' => $report->sum(fn($r) => $r['buckets']['1_30']),
            '31_60' => $report->sum(fn($r) => $r['buckets']['31_60']),
            '61_90' => $report->sum(fn($r) => $r['buckets']['61_90']),
            'over_90' => $report->sum(fn($r) => $r['buckets']['over_90']),
            'total' => $report->sum('total'),
        ];

        return view('reports.aged-receivables', compact('report', 'totals', 'asOfDate'));
    }

    public function cashflowStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Find cash & bank accounts (codes 1001, 1002)
        $cashAccounts = Account::whereIn('code', ['1001', '1002'])->get();

        $opening = 0;
        $closing = 0;
        foreach ($cashAccounts as $acc) {
            $before = JournalEntryItem::whereHas('journalEntry', fn($q) => $q->where('date', '<', $startDate))
                ->where('account_id', $acc->id);
            $opening += $acc->opening_balance + $before->sum('debit') - $before->sum('credit');

            $upto = JournalEntryItem::whereHas('journalEntry', fn($q) => $q->where('date', '<=', $endDate))
                ->where('account_id', $acc->id);
            $closing += $acc->opening_balance + $upto->sum('debit') - $upto->sum('credit');
        }

        // Only journal items that hit a cash/bank account between the dates
        $cashIds = $cashAccounts->pluck('id')->toArray();

        $movements = JournalEntryItem::with(['journalEntry', 'account'])
            ->whereHas('journalEntry', fn($q) => $q->whereBetween('date', [$startDate, $endDate]))
            ->whereIn('account_id', $cashIds)
            ->get();

        $operating = ['in' => 0, 'out' => 0, 'items' => []];
        $investing = ['in' => 0, 'out' => 0, 'items' => []];
        $financing = ['in' => 0, 'out' => 0, 'items' => []];

        foreach ($movements as $mv) {
            $je = $mv->journalEntry;
            $amount = ((float) $mv->debit) - ((float) $mv->credit);

            $counterItems = JournalEntryItem::with('account')
                ->where('journal_entry_id', $je->id)
                ->where('id', '!=', $mv->id)
                ->get();

            foreach ($counterItems as $co) {
                if (!$co->account) continue;
                $contra = ((float) $co->credit) - ((float) $co->debit);
                $bucket = $this->classifyCashflow($co->account);
                $key = $contra > 0 ? 'in' : 'out';
                $absAmt = abs($contra);

                if ($absAmt == 0) continue;

                ${$bucket}[$key] += $absAmt;
                ${$bucket}['items'][] = [
                    'date' => $je->date,
                    'account' => $co->account->name,
                    'narration' => $je->narration,
                    'inflow' => $contra > 0 ? $absAmt : 0,
                    'outflow' => $contra < 0 ? $absAmt : 0,
                ];
            }
        }

        $netOperating = $operating['in'] - $operating['out'];
        $netInvesting = $investing['in'] - $investing['out'];
        $netFinancing = $financing['in'] - $financing['out'];
        $netChange = $netOperating + $netInvesting + $netFinancing;

        return view('reports.cashflow-statement', compact(
            'startDate', 'endDate', 'opening', 'closing',
            'operating', 'investing', 'financing',
            'netOperating', 'netInvesting', 'netFinancing', 'netChange'
        ));
    }

    private function classifyCashflow(Account $account): string
    {
        // Investing: fixed assets (codes 15xx, except inventory)
        if (preg_match('/^15\d\d$/', $account->code)) {
            return 'investing';
        }
        // Financing: owner equity, loans
        if ($account->type === 'equity' || str_starts_with($account->code, '25')) {
            return 'financing';
        }
        return 'operating';
    }

    public function agedPayables(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->format('Y-m-d'));

        $suppliers = Supplier::with(['invoices' => function ($q) use ($asOfDate) {
            $q->where('type', 'purchase')
              ->where('date', '<=', $asOfDate)
              ->whereIn('status', ['sent', 'partial', 'overdue', 'paid'])
              ->where('due', '>', 0);
        }])->get();

        $report = $suppliers->map(function ($supplier) use ($asOfDate) {
            $buckets = ['current' => 0, '1_30' => 0, '31_60' => 0, '61_90' => 0, 'over_90' => 0];

            foreach ($supplier->invoices as $invoice) {
                $dueDate = $invoice->due_date ?? $invoice->date;
                $daysPast = \Carbon\Carbon::parse($dueDate)->diffInDays(\Carbon\Carbon::parse($asOfDate), false);

                if ($daysPast <= 0) $buckets['current'] += $invoice->due;
                elseif ($daysPast <= 30) $buckets['1_30'] += $invoice->due;
                elseif ($daysPast <= 60) $buckets['31_60'] += $invoice->due;
                elseif ($daysPast <= 90) $buckets['61_90'] += $invoice->due;
                else $buckets['over_90'] += $invoice->due;
            }

            $total = array_sum($buckets);

            return [
                'supplier' => $supplier,
                'buckets' => $buckets,
                'total' => $total,
            ];
        })->filter(fn($r) => $r['total'] > 0)->values();

        $totals = [
            'current' => $report->sum(fn($r) => $r['buckets']['current']),
            '1_30' => $report->sum(fn($r) => $r['buckets']['1_30']),
            '31_60' => $report->sum(fn($r) => $r['buckets']['31_60']),
            '61_90' => $report->sum(fn($r) => $r['buckets']['61_90']),
            'over_90' => $report->sum(fn($r) => $r['buckets']['over_90']),
            'total' => $report->sum('total'),
        ];

        return view('reports.aged-payables', compact('report', 'totals', 'asOfDate'));
    }
}
