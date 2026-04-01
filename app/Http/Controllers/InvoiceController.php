<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'sales');
        $invoices = Invoice::with(['customer', 'supplier'])
            ->where('type', $type)
            ->latest('date')
            ->get();

        return view('invoices.index', compact('invoices', 'type'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'sales');
        $customers = Customer::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        $invoiceNo = Invoice::generateInvoiceNo($type);

        return view('invoices.create', compact('type', 'customers', 'suppliers', 'accounts', 'invoiceNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sales,purchase',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.account_id' => 'nullable|exists:accounts,id',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            $invoice = Invoice::create([
                'invoice_no' => Invoice::generateInvoiceNo($validated['type']),
                'type' => $validated['type'],
                'date' => $validated['date'],
                'due_date' => $validated['due_date'],
                'customer_id' => $validated['customer_id'],
                'supplier_id' => $validated['supplier_id'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'paid' => 0,
                'due' => $total,
                'status' => 'draft',
                'notes' => $validated['notes'],
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price'],
                    'account_id' => $item['account_id'] ?? null,
                ]);
            }
        });

        return redirect()->route('invoices.index', ['type' => $validated['type']])->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'customer', 'supplier', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $type = $invoice->type;
        $invoice->delete();
        return redirect()->route('invoices.index', ['type' => $type])->with('success', 'Invoice deleted successfully.');
    }
}
