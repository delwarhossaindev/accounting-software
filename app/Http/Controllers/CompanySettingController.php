<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function edit()
    {
        $company = CompanySetting::current();
        return view('company-settings.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = CompanySetting::current();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'tin' => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'currency_code' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'fiscal_year_start_month' => 'required|integer|between:1,12',
            'invoice_prefix' => 'required|string|max:20',
            'bill_prefix' => 'required|string|max:20',
            'invoice_footer' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);

        // Handle logo
        if ($request->boolean('remove_logo') && $company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
            $validated['logo_path'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        unset($validated['logo'], $validated['remove_logo']);

        $company->update($validated);

        return redirect()->route('company-settings.edit')->with('success', 'Company settings updated successfully.');
    }
}
