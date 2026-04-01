<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntryItem;
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
}
