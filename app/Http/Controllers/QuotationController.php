<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer', 'branch')->latest('date')->get();
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $taxRates = TaxRate::where('is_active', true)->orderBy('name')->get();
        $quotationNo = Quotation::generateQuotationNo();

        return view('quotations.create', compact('customers', 'products', 'branches', 'taxRates', 'quotationNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'nullable|exists:branches,id',
            'subject' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.warranty' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            $quotation = Quotation::create([
                'quotation_no' => Quotation::generateQuotationNo(),
                'date' => $validated['date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'customer_id' => $validated['customer_id'],
                'branch_id' => $validated['branch_id'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'warranty' => $item['warranty'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('items.product', 'customer', 'branch', 'convertedInvoice');
        return view('quotations.show', compact('quotation'));
    }

    public function destroy(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot delete a quotation that has been converted to an invoice.');
        }

        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot change status of a converted quotation.');
        }

        $quotation->update(['status' => $validated['status']]);
        return back()->with('success', 'Status updated successfully.');
    }

    public function convertToInvoice(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'This quotation has already been converted.');
        }

        $quotation->load('items');

        $invoice = DB::transaction(function () use ($quotation) {
            $invoice = Invoice::create([
                'invoice_no' => Invoice::generateInvoiceNo('sales'),
                'type' => 'sales',
                'date' => now(),
                'due_date' => now()->addDays(15),
                'customer_id' => $quotation->customer_id,
                'branch_id' => $quotation->branch_id,
                'subtotal' => $quotation->subtotal,
                'tax' => $quotation->tax,
                'discount' => $quotation->discount,
                'total' => $quotation->total,
                'paid' => 0,
                'due' => $quotation->total,
                'status' => 'draft',
                'notes' => "Converted from Quotation {$quotation->quotation_no}",
                'user_id' => auth()->id(),
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'warranty' => $item->warranty,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->amount,
                ]);
            }

            $quotation->update([
                'status' => 'converted',
                'converted_invoice_id' => $invoice->id,
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Quotation converted to invoice.');
    }
}
