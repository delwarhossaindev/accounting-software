<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $units = ['pcs', 'kg', 'gm', 'ltr', 'ml', 'box', 'pack', 'dozen', 'meter', 'feet'];
        $nextSku = Product::generateSku();
        return view('products.create', compact('units', 'nextSku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['current_stock'] = $validated['current_stock'] ?? 0;
        $validated['reorder_level'] = $validated['reorder_level'] ?? 0;

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            if ($product->current_stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->current_stock,
                    'unit_price' => $product->purchase_price,
                    'date' => now(),
                    'reference_type' => 'opening_stock',
                    'notes' => 'Opening stock balance',
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $units = ['pcs', 'kg', 'gm', 'ltr', 'ml', 'box', 'pack', 'dozen', 'meter', 'feet'];
        return view('products.edit', compact('product', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['reorder_level'] = $validated['reorder_level'] ?? 0;

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->stockMovements()->count() > 0) {
            return back()->with('error', 'Cannot delete product with stock movement history.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $product) {
            $qty = $validated['quantity'];

            if ($validated['type'] === 'in') {
                $product->current_stock += $qty;
            } elseif ($validated['type'] === 'out') {
                if ($product->current_stock < $qty) {
                    throw new \Exception('Insufficient stock.');
                }
                $product->current_stock -= $qty;
            } else {
                $product->current_stock = $qty;
            }

            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $qty,
                'unit_price' => $product->purchase_price,
                'date' => now(),
                'reference_type' => 'manual_adjustment',
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Stock adjusted successfully.');
    }

    public function stockReport(Request $request)
    {
        $products = Product::orderBy('name')->get();

        $totalStockValue = $products->sum(fn($p) => $p->current_stock * $p->purchase_price);
        $outOfStock = $products->filter(fn($p) => $p->current_stock <= 0)->count();
        $lowStock = $products->filter(fn($p) => $p->reorder_level > 0 && $p->current_stock > 0 && $p->current_stock <= $p->reorder_level)->count();

        return view('products.stock-report', compact('products', 'totalStockValue', 'outOfStock', 'lowStock'));
    }

    public function movements(Product $product)
    {
        $movements = $product->stockMovements()
            ->with('user')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('products.movements', compact('product', 'movements'));
    }
}
