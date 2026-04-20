<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('sort_order')->orderBy('name')->get();
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'is_head_office' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_head_office'] = $request->has('is_head_office');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DB::transaction(function () use ($validated) {
            if ($validated['is_head_office']) {
                Branch::where('is_head_office', true)->update(['is_head_office' => false]);
            }
            Branch::create($validated);
        });

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'is_head_office' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_head_office'] = $request->has('is_head_office');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DB::transaction(function () use ($validated, $branch) {
            if ($validated['is_head_office']) {
                Branch::where('is_head_office', true)->where('id', '!=', $branch->id)->update(['is_head_office' => false]);
            }
            $branch->update($validated);
        });

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->invoices()->count() > 0) {
            return back()->with('error', 'Cannot delete branch with existing invoices.');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
