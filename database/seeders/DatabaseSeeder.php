<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user (email verified)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Roles & permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Company settings, branches, tax rates
        $this->call(CompanySettingSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(TaxRateSeeder::class);

        // Seed default chart of accounts
        $this->call(DefaultAccountSeeder::class);

        // Products & opening stock
        $this->call(ProductSeeder::class);

        // Seed demo data (customers, suppliers, invoices, payments, expenses, journals)
        $this->call(DemoDataSeeder::class);

        // Quotations and credit/debit notes (depend on customers, suppliers, invoices, products)
        $this->call(QuotationSeeder::class);
        $this->call(CreditDebitNoteSeeder::class);
    }
}
