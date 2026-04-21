<?php

namespace App\Http\Controllers;

use App\Models\RecurringExpense;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\RecurringExpenseRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    public function __construct(
        private RecurringExpenseRepositoryInterface $recurring,
        private AccountRepositoryInterface $accounts,
        private SupplierRepositoryInterface $suppliers,
    ) {}

    public function index()
    {
        $items = $this->recurring->query()
            ->with(['account', 'supplier'])
            ->latest()
            ->paginate(20);
        return view('recurring-expenses.index', compact('items'));
    }

    public function create()
    {
        $accounts = $this->accounts->byType('expense');
        $suppliers = $this->suppliers->query()->orderBy('name')->get();
        return view('recurring-expenses.create', compact('accounts', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'account_id'     => 'required|exists:accounts,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'frequency'      => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'amount'         => 'required|numeric|min:0.01',
            'category'       => 'nullable|string',
            'payment_method' => 'required|string',
            'description'    => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        $data['next_run_date'] = $data['start_date'];

        $this->recurring->create($data);

        return redirect()->route('recurring-expenses.index')->with('success', 'Recurring expense created.');
    }

    public function edit(RecurringExpense $recurringExpense)
    {
        $accounts = $this->accounts->byType('expense');
        $suppliers = $this->suppliers->query()->orderBy('name')->get();
        return view('recurring-expenses.edit', [
            'item' => $recurringExpense,
            'accounts' => $accounts,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, RecurringExpense $recurringExpense)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'account_id'     => 'required|exists:accounts,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'frequency'      => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'amount'         => 'required|numeric|min:0.01',
            'category'       => 'nullable|string',
            'payment_method' => 'required|string',
            'description'    => 'nullable|string',
            'is_active'      => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $this->recurring->update($recurringExpense, $data);

        return redirect()->route('recurring-expenses.index')->with('success', 'Updated.');
    }

    public function destroy(RecurringExpense $recurringExpense)
    {
        $this->recurring->delete($recurringExpense);
        return redirect()->route('recurring-expenses.index')->with('success', 'Deleted.');
    }
}
