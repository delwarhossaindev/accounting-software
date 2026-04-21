<?php

namespace App\Http\Controllers;

use App\Models\RecurringInvoice;
use App\Repositories\Contracts\BranchRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\RecurringInvoiceRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Http\Request;

class RecurringInvoiceController extends Controller
{
    public function __construct(
        private RecurringInvoiceRepositoryInterface $recurring,
        private CustomerRepositoryInterface $customers,
        private SupplierRepositoryInterface $suppliers,
        private BranchRepositoryInterface $branches,
    ) {}

    public function index()
    {
        $items = $this->recurring->query()
            ->with(['customer', 'supplier'])
            ->latest()
            ->paginate(20);
        return view('recurring-invoices.index', compact('items'));
    }

    public function create()
    {
        $customers = $this->customers->query()->orderBy('name')->get();
        $suppliers = $this->suppliers->query()->orderBy('name')->get();
        $branches  = $this->branches->activeOrdered();
        return view('recurring-invoices.create', compact('customers', 'suppliers', 'branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:sales,purchase',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'frequency'  => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'tax_rate'   => 'nullable|numeric|min:0',
            'discount'   => 'nullable|numeric|min:0',
            'notes'      => 'nullable|string',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:0',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $data['user_id'] = auth()->id();
        $data['next_run_date'] = $data['start_date'];
        $data['subtotal'] = collect($data['items'])
            ->sum(fn($i) => ((float)$i['quantity']) * ((float)$i['unit_price']));

        $this->recurring->create($data);

        return redirect()->route('recurring-invoices.index')->with('success', 'Recurring invoice created.');
    }

    public function edit(RecurringInvoice $recurringInvoice)
    {
        $customers = $this->customers->query()->orderBy('name')->get();
        $suppliers = $this->suppliers->query()->orderBy('name')->get();
        $branches  = $this->branches->activeOrdered();
        return view('recurring-invoices.edit', [
            'item' => $recurringInvoice,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, RecurringInvoice $recurringInvoice)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:sales,purchase',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id'  => 'nullable|exists:branches,id',
            'frequency'  => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'tax_rate'   => 'nullable|numeric|min:0',
            'discount'   => 'nullable|numeric|min:0',
            'is_active'  => 'sometimes|boolean',
            'notes'      => 'nullable|string',
            'items'      => 'required|array|min:1',
        ]);

        $data['subtotal'] = collect($data['items'])
            ->sum(fn($i) => ((float)$i['quantity']) * ((float)$i['unit_price']));
        $data['is_active'] = $request->boolean('is_active');

        $this->recurring->update($recurringInvoice, $data);

        return redirect()->route('recurring-invoices.index')->with('success', 'Updated.');
    }

    public function destroy(RecurringInvoice $recurringInvoice)
    {
        $this->recurring->delete($recurringInvoice);
        return redirect()->route('recurring-invoices.index')->with('success', 'Deleted.');
    }
}
