<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Repositories\Contracts\AccountGroupRepositoryInterface;
use Illuminate\Http\Request;

class AccountGroupController extends Controller
{
    public function __construct(private AccountGroupRepositoryInterface $groups) {}

    public function index()
    {
        $groups = $this->groups->query()
            ->with(['parent', 'children', 'accounts'])
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('account-groups.index', compact('groups'));
    }

    public function create()
    {
        $parentGroups = $this->groups->all([], ['name' => 'asc']);
        $types = ['asset', 'liability', 'equity', 'income', 'expense'];

        return view('account-groups.create', compact('parentGroups', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:account_groups,id',
        ]);

        $this->groups->create($validated);

        return redirect()->route('account-groups.index')->with('success', 'Account Group created successfully.');
    }

    public function edit(AccountGroup $accountGroup)
    {
        $parentGroups = $this->groups->query()
            ->where('id', '!=', $accountGroup->id)
            ->orderBy('name')
            ->get();
        $types = ['asset', 'liability', 'equity', 'income', 'expense'];

        return view('account-groups.edit', compact('accountGroup', 'parentGroups', 'types'));
    }

    public function update(Request $request, AccountGroup $accountGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:account_groups,id',
        ]);

        if ($validated['parent_id'] == $accountGroup->id) {
            return back()->withErrors(['parent_id' => 'A group cannot be its own parent.']);
        }

        $this->groups->update($accountGroup, $validated);

        return redirect()->route('account-groups.index')->with('success', 'Account Group updated successfully.');
    }

    public function destroy(AccountGroup $accountGroup)
    {
        if ($accountGroup->accounts()->count() > 0) {
            return back()->withErrors(['This group cannot be deleted because it has associated accounts.']);
        }

        $this->groups->delete($accountGroup);

        return redirect()->route('account-groups.index')->with('success', 'Account Group deleted successfully.');
    }
}
