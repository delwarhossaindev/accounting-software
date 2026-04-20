<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('supplier_id')->constrained()->nullOnDelete();
            $table->string('po_no', 100)->nullable()->after('branch_id');
            $table->string('req_no', 100)->nullable()->after('po_no');
            $table->string('sold_by', 100)->nullable()->after('req_no');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('serial_no', 500)->nullable()->after('description');
            $table->string('warranty', 50)->nullable()->after('serial_no');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'po_no', 'req_no', 'sold_by']);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['serial_no', 'warranty']);
        });
    }
};
