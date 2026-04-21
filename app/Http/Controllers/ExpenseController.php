<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseRepositoryInterface $expenses,
        private AccountRepositoryInterface $accounts,
        private SupplierRepositoryInterface $suppliers,
    ) {}

    public function index()
    {
        $expenses = $this->expenses->query()
            ->with(['account', 'supplier'])
            ->latest('date')
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = $this->accounts->query()
            ->where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $suppliers = $this->suppliers->active();
        $expenseNo = Expense::generateExpenseNo();
        return view('expenses.create', compact('accounts', 'suppliers', 'expenseNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $validated['expense_no'] = Expense::generateExpenseNo();
        $validated['user_id'] = auth()->id();

        $this->expenses->create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $accounts = $this->accounts->query()
            ->where('is_active', true)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();
        $suppliers = $this->suppliers->active();
        return view('expenses.edit', compact('expense', 'accounts', 'suppliers'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $this->expenses->update($expense, $validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->expenses->delete($expense);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
