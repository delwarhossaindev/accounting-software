<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Repositories\Contracts\TaxRateRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxRateController extends Controller
{
    public function __construct(private TaxRateRepositoryInterface $taxRates) {}

    public function index()
    {
        $taxRates = $this->taxRates->all([], ['name' => 'asc']);
        return view('tax-rates.index', compact('taxRates'));
    }

    public function create()
    {
        return view('tax-rates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tax_rates,name',
            'rate' => 'required|numeric|min:0|max:100',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated) {
            if ($validated['is_default']) {
                $this->taxRates->query()->where('is_default', true)->update(['is_default' => false]);
            }
            $this->taxRates->create($validated);
        });

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate created successfully.');
    }

    public function edit(TaxRate $taxRate)
    {
        return view('tax-rates.edit', compact('taxRate'));
    }

    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:tax_rates,name,' . $taxRate->id,
            'rate' => 'required|numeric|min:0|max:100',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated, $taxRate) {
            if ($validated['is_default']) {
                $this->taxRates->query()
                    ->where('is_default', true)
                    ->where('id', '!=', $taxRate->id)
                    ->update(['is_default' => false]);
            }
            $this->taxRates->update($taxRate, $validated);
        });

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate updated successfully.');
    }

    public function destroy(TaxRate $taxRate)
    {
        $this->taxRates->delete($taxRate);
        return redirect()->route('tax-rates.index')->with('success', 'Tax rate deleted successfully.');
    }
}
