<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Database\Seeder;

class DefaultAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Account Groups
        $groups = [
            ['name' => 'Current Assets', 'type' => 'asset'],
            ['name' => 'Fixed Assets', 'type' => 'asset'],
            ['name' => 'Current Liabilities', 'type' => 'liability'],
            ['name' => 'Long Term Liabilities', 'type' => 'liability'],
            ['name' => 'Owner Equity', 'type' => 'equity'],
            ['name' => 'Sales Revenue', 'type' => 'income'],
            ['name' => 'Other Income', 'type' => 'income'],
            ['name' => 'Operating Expenses', 'type' => 'expense'],
            ['name' => 'Administrative Expenses', 'type' => 'expense'],
        ];

        foreach ($groups as $group) {
            AccountGroup::firstOrCreate(['name' => $group['name']], $group);
        }

        // Default Accounts
        $accounts = [
            // Assets
            ['code' => '1001', 'name' => 'Cash in Hand', 'type' => 'asset', 'group' => 'Current Assets'],
            ['code' => '1002', 'name' => 'Cash at Bank', 'type' => 'asset', 'group' => 'Current Assets'],
            ['code' => '1003', 'name' => 'Accounts Receivable', 'type' => 'asset', 'group' => 'Current Assets'],
            ['code' => '1004', 'name' => 'Inventory', 'type' => 'asset', 'group' => 'Current Assets'],
            ['code' => '1005', 'name' => 'Prepaid Expenses', 'type' => 'asset', 'group' => 'Current Assets'],
            ['code' => '1501', 'name' => 'Furniture & Fixtures', 'type' => 'asset', 'group' => 'Fixed Assets'],
            ['code' => '1502', 'name' => 'Office Equipment', 'type' => 'asset', 'group' => 'Fixed Assets'],
            ['code' => '1503', 'name' => 'Vehicle', 'type' => 'asset', 'group' => 'Fixed Assets'],

            // Liabilities
            ['code' => '2001', 'name' => 'Accounts Payable', 'type' => 'liability', 'group' => 'Current Liabilities'],
            ['code' => '2002', 'name' => 'Salary Payable', 'type' => 'liability', 'group' => 'Current Liabilities'],
            ['code' => '2003', 'name' => 'Tax Payable', 'type' => 'liability', 'group' => 'Current Liabilities'],
            ['code' => '2501', 'name' => 'Bank Loan', 'type' => 'liability', 'group' => 'Long Term Liabilities'],

            // Equity
            ['code' => '3001', 'name' => 'Owner Capital', 'type' => 'equity', 'group' => 'Owner Equity'],
            ['code' => '3002', 'name' => 'Retained Earnings', 'type' => 'equity', 'group' => 'Owner Equity'],
            ['code' => '3003', 'name' => 'Owner Drawings', 'type' => 'equity', 'group' => 'Owner Equity'],

            // Income
            ['code' => '4001', 'name' => 'Sales Revenue', 'type' => 'income', 'group' => 'Sales Revenue'],
            ['code' => '4002', 'name' => 'Service Revenue', 'type' => 'income', 'group' => 'Sales Revenue'],
            ['code' => '4003', 'name' => 'Interest Income', 'type' => 'income', 'group' => 'Other Income'],
            ['code' => '4004', 'name' => 'Other Income', 'type' => 'income', 'group' => 'Other Income'],

            // Expenses
            ['code' => '5001', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'group' => 'Operating Expenses'],
            ['code' => '5002', 'name' => 'Purchase', 'type' => 'expense', 'group' => 'Operating Expenses'],
            ['code' => '5101', 'name' => 'Salary Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5102', 'name' => 'Rent Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5103', 'name' => 'Utility Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5104', 'name' => 'Office Supplies', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5105', 'name' => 'Transportation Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5106', 'name' => 'Telephone & Internet', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5107', 'name' => 'Depreciation Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
            ['code' => '5108', 'name' => 'Miscellaneous Expense', 'type' => 'expense', 'group' => 'Administrative Expenses'],
        ];

        foreach ($accounts as $acc) {
            $group = AccountGroup::where('name', $acc['group'])->first();
            Account::firstOrCreate(
                ['code' => $acc['code']],
                [
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'account_group_id' => $group?->id,
                    'opening_balance' => 0,
                ]
            );
        }
    }
}
