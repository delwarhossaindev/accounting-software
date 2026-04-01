<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('group')->orderBy('code')->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $groups = AccountGroup::all();
        $types = ['asset', 'liability', 'equity', 'income', 'expense'];
        return view('accounts.create', compact('groups', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'account_group_id' => 'nullable|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $validated['opening_balance'] = $validated['opening_balance'] ?? 0;
        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        $groups = AccountGroup::all();
        $types = ['asset', 'liability', 'equity', 'income', 'expense'];
        return view('accounts.edit', compact('account', 'groups', 'types'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'account_group_id' => 'nullable|exists:account_groups,id',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $account->update($validated);
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }

    public function ledger(Account $account, Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $entries = $account->journalEntryItems()
            ->with('journalEntry')
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->orderBy('id')
            ->get();

        $runningBalance = $account->opening_balance;

        return view('accounts.ledger', compact('account', 'entries', 'startDate', 'endDate', 'runningBalance'));
    }
}
