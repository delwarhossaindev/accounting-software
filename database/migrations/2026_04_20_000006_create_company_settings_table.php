<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('My Company');
            $table->string('logo_path')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tin', 50)->nullable(); // Tax Identification Number
            $table->string('bin', 50)->nullable(); // VAT / Business Identification Number
            $table->string('currency_code', 10)->default('BDT');
            $table->string('currency_symbol', 10)->default('৳');
            $table->unsignedTinyInteger('fiscal_year_start_month')->default(7); // July for BD
            $table->string('invoice_prefix', 20)->default('INV');
            $table->string('bill_prefix', 20)->default('BILL');
            $table->text('invoice_footer')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
        });

        // Seed initial row
        \DB::table('company_settings')->insert([
            'name' => 'My Company',
            'currency_code' => 'BDT',
            'currency_symbol' => '৳',
            'fiscal_year_start_month' => 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
