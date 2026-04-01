<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['account', 'supplier'])->latest('date')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->where('type', 'expense')->orderBy('code')->get();
        $suppliers = Supplier::where('is_active', true)->get();
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

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $accounts = Account::where('is_active', true)->where('type', 'expense')->orderBy('code')->get();
        $suppliers = Supplier::where('is_active', true)->get();
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

        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
