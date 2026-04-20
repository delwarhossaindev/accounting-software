<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\TaxRate;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $taxRates = TaxRate::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $invoiceNo = Invoice::generateInvoiceNo($type);

        return view('invoices.create', compact('type', 'customers', 'suppliers', 'accounts', 'products', 'taxRates', 'branches', 'invoiceNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sales,purchase',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id' => 'nullable|exists:branches,id',
            'po_no' => 'nullable|string|max:100',
            'req_no' => 'nullable|string|max:100',
            'sold_by' => 'nullable|string|max:100',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.serial_no' => 'nullable|string|max:500',
            'items.*.warranty' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.account_id' => 'nullable|exists:accounts,id',
        ]);

        // Pre-check stock availability for sales invoices
        if ($validated['type'] === 'sales') {
            foreach ($validated['items'] as $item) {
                if (!empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->current_stock < $item['quantity']) {
                        return back()
                            ->withInput()
                            ->with('error', "Insufficient stock for {$product->name}. Available: {$product->current_stock} {$product->unit}");
                    }
                }
            }
        }

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
                'branch_id' => $validated['branch_id'] ?? null,
                'po_no' => $validated['po_no'] ?? null,
                'req_no' => $validated['req_no'] ?? null,
                'sold_by' => $validated['sold_by'] ?? null,
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
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'serial_no' => $item['serial_no'] ?? null,
                    'warranty' => $item['warranty'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price'],
                    'account_id' => $item['account_id'] ?? null,
                ]);

                // Handle stock movement for products
                if (!empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        if ($validated['type'] === 'sales') {
                            $product->current_stock -= $item['quantity'];
                            $movementType = 'out';
                        } else {
                            $product->current_stock += $item['quantity'];
                            $movementType = 'in';
                        }
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'type' => $movementType,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'date' => $validated['date'],
                            'reference_type' => 'invoice',
                            'reference_id' => $invoice->id,
                            'notes' => "Invoice #{$invoice->invoice_no}",
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('invoices.index', ['type' => $validated['type']])->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer', 'supplier', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    public function sendEmail(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            Mail::to($validated['email'])
                ->send(new InvoiceMail($invoice, $validated['message'] ?? ''));

            // Mark invoice as "sent" if it was draft
            if ($invoice->status === 'draft') {
                $invoice->status = 'sent';
                $invoice->save();
            }

            return back()->with('success', 'Invoice emailed to ' . $validated['email']);
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        $type = $invoice->type;

        DB::transaction(function () use ($invoice) {
            // Reverse stock movements
            foreach ($invoice->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($invoice->type === 'sales') {
                            $product->current_stock += $item->quantity;
                        } else {
                            $product->current_stock -= $item->quantity;
                        }
                        $product->save();
                    }
                }
            }

            // Delete linked stock movements
            StockMovement::where('reference_type', 'invoice')
                ->where('reference_id', $invoice->id)
                ->delete();

            $invoice->delete();
        });

        return redirect()->route('invoices.index', ['type' => $type])->with('success', 'Invoice deleted successfully.');
    }
}
