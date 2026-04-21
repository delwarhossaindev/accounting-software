<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Repositories\Contracts\AccountGroupRepositoryInterface;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private AccountRepositoryInterface $accounts,
        private AccountGroupRepositoryInterface $groupsRepo,
    ) {}

    public function index()
    {
        $accounts = $this->accounts->all(['group'], ['code' => 'asc']);
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $groups = $this->groupsRepo->all();
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
        $this->accounts->create($validated);

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function edit(Account $account)
    {
        $groups = $this->groupsRepo->all();
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

        $this->accounts->update($account, $validated);
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $this->accounts->delete($account);
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
