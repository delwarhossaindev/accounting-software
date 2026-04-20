<?php

namespace App\Http\Controllers;

use App\Models\CreditDebitNote;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditDebitNoteController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'credit');
        $notes = CreditDebitNote::with('customer', 'supplier', 'invoice')
            ->where('type', $type)
            ->latest('date')
            ->get();

        return view('credit-debit-notes.index', compact('notes', 'type'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'credit');
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $invoices = Invoice::where('type', $type === 'credit' ? 'sales' : 'purchase')->latest('date')->limit(200)->get();
        $noteNo = CreditDebitNote::generateNoteNo($type);

        return view('credit-debit-notes.create', compact('type', 'customers', 'suppliers', 'products', 'invoices', 'noteNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:credit,debit',
            'date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'reason' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal + $tax;

            $note = CreditDebitNote::create([
                'note_no' => CreditDebitNote::generateNoteNo($validated['type']),
                'type' => $validated['type'],
                'date' => $validated['date'],
                'customer_id' => $validated['customer_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'invoice_id' => $validated['invoice_id'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $note->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['quantity'] * $item['unit_price'],
                ]);

                // Stock movement: credit note = goods come BACK IN, debit note = goods go OUT
                if (!empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $movementType = $validated['type'] === 'credit' ? 'in' : 'out';

                        if ($movementType === 'out' && $product->current_stock < $item['quantity']) {
                            throw new \Exception("Insufficient stock for {$product->name}");
                        }

                        if ($movementType === 'in') {
                            $product->current_stock += $item['quantity'];
                        } else {
                            $product->current_stock -= $item['quantity'];
                        }
                        $product->save();

                        StockMovement::create([
                            'product_id' => $product->id,
                            'type' => $movementType,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'date' => $validated['date'],
                            'reference_type' => $validated['type'] . '_note',
                            'reference_id' => $note->id,
                            'notes' => ucfirst($validated['type']) . " Note #{$note->note_no}",
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('credit-debit-notes.index', ['type' => $validated['type']])
            ->with('success', ucfirst($validated['type']) . ' note created successfully.');
    }

    public function show(CreditDebitNote $creditDebitNote)
    {
        $creditDebitNote->load('items.product', 'customer', 'supplier', 'invoice');
        return view('credit-debit-notes.show', ['note' => $creditDebitNote]);
    }

    public function destroy(CreditDebitNote $creditDebitNote)
    {
        $type = $creditDebitNote->type;

        DB::transaction(function () use ($creditDebitNote) {
            // Reverse stock
            foreach ($creditDebitNote->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($creditDebitNote->type === 'credit') {
                            $product->current_stock -= $item->quantity;
                        } else {
                            $product->current_stock += $item->quantity;
                        }
                        $product->save();
                    }
                }
            }

            StockMovement::where('reference_type', $creditDebitNote->type . '_note')
                ->where('reference_id', $creditDebitNote->id)
                ->delete();

            $creditDebitNote->delete();
        });

        return redirect()->route('credit-debit-notes.index', ['type' => $type])->with('success', 'Note deleted successfully.');
    }
}
