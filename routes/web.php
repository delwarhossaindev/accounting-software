<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountGroupController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BankReconciliationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CreditDebitNoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\RecurringInvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxRateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Public signed PDF for quotations — shared via WhatsApp/email (no auth, signed link expires)
Route::get('quotations/{quotation}/public-pdf', [QuotationController::class, 'publicPdf'])
    ->middleware('signed')
    ->name('quotations.public-pdf');

// Two-factor challenge (needs auth only — NOT verified, and NOT 2fa middleware itself)
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/challenge', [\App\Http\Controllers\TwoFactorController::class, 'showChallenge'])->name('2fa.challenge');
    Route::post('/2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/resend', [\App\Http\Controllers\TwoFactorController::class, 'resend'])->name('2fa.resend');
    Route::post('/2fa/toggle', [\App\Http\Controllers\TwoFactorController::class, 'toggle'])->name('2fa.toggle');
});

Route::middleware(['auth', 'verified', '2fa'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chart of Accounts
    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::get('accounts/{account}/ledger', [AccountController::class, 'ledger'])->name('accounts.ledger');

    // Account Groups
    Route::resource('account-groups', AccountGroupController::class)->except(['show']);

    // Journal Entries
    Route::resource('journals', JournalEntryController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Customers
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Invoices (Sales & Purchase)
    Route::post('invoices/{invoice}/email', [InvoiceController::class, 'sendEmail'])->name('invoices.email');
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Quotations
    Route::post('quotations/{quotation}/convert', [QuotationController::class, 'convertToInvoice'])->name('quotations.convert');
    Route::post('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.status');
    Route::post('quotations/{quotation}/email', [QuotationController::class, 'sendEmail'])->name('quotations.email');
    Route::get('quotations/{quotation}/whatsapp', [QuotationController::class, 'whatsappLink'])->name('quotations.whatsapp');
    Route::resource('quotations', QuotationController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Credit & Debit Notes
    Route::resource('credit-debit-notes', CreditDebitNoteController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy']);

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show']);

    // Recurring Invoices & Expenses
    Route::resource('recurring-invoices', RecurringInvoiceController::class)->except(['show']);
    Route::resource('recurring-expenses', RecurringExpenseController::class)->except(['show']);

    // CSV Imports
    Route::get('imports', [ImportController::class, 'index'])->name('imports.index');
    Route::get('imports/{entity}/template', [ImportController::class, 'template'])->name('imports.template');
    Route::post('imports/{entity}', [ImportController::class, 'store'])->name('imports.store');

    // Products & Inventory
    Route::get('products/stock-report', [ProductController::class, 'stockReport'])->name('products.stock-report');
    Route::get('products/{product}/movements', [ProductController::class, 'movements'])->name('products.movements');
    Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::resource('products', ProductController::class)->except(['show']);

    // Tax Rates
    Route::resource('tax-rates', TaxRateController::class)->except(['show']);

    // Audit Logs
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // Company Settings
    Route::get('company-settings', [CompanySettingController::class, 'edit'])->name('company-settings.edit');
    Route::put('company-settings', [CompanySettingController::class, 'update'])->name('company-settings.update');

    // Branches
    Route::resource('branches', BranchController::class)->except(['show']);

    // Bank Reconciliation
    Route::get('bank-reconciliation', [BankReconciliationController::class, 'index'])->name('bank-reconciliation.index');
    Route::post('bank-reconciliation/line', [BankReconciliationController::class, 'storeLine'])->name('bank-reconciliation.line.store');
    Route::post('bank-reconciliation/import', [BankReconciliationController::class, 'importStatement'])->name('bank-reconciliation.import');
    Route::post('bank-reconciliation/line/{line}/match', [BankReconciliationController::class, 'match'])->name('bank-reconciliation.line.match');
    Route::post('bank-reconciliation/line/{line}/ignore', [BankReconciliationController::class, 'ignore'])->name('bank-reconciliation.line.ignore');
    Route::delete('bank-reconciliation/line/{line}', [BankReconciliationController::class, 'deleteLine'])->name('bank-reconciliation.line.destroy');
    Route::post('bank-reconciliation/finalize', [BankReconciliationController::class, 'finalize'])->name('bank-reconciliation.finalize');

    // Reports
    Route::get('reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('reports/income-statement', [ReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('reports/cashflow-statement', [ReportController::class, 'cashflowStatement'])->name('reports.cashflow-statement');
    Route::get('reports/aged-receivables', [ReportController::class, 'agedReceivables'])->name('reports.aged-receivables');
    Route::get('reports/aged-payables', [ReportController::class, 'agedPayables'])->name('reports.aged-payables');

    // PDF Export
    Route::get('pdf/invoice/{invoice}', [PdfController::class, 'invoice'])->name('pdf.invoice');
    Route::get('pdf/quotation/{quotation}', [PdfController::class, 'quotation'])->name('pdf.quotation');
    Route::get('pdf/journal/{journal}', [PdfController::class, 'journal'])->name('pdf.journal');
    Route::get('pdf/customers', [PdfController::class, 'customers'])->name('pdf.customers');
    Route::get('pdf/suppliers', [PdfController::class, 'suppliers'])->name('pdf.suppliers');
    Route::get('pdf/expenses', [PdfController::class, 'expenses'])->name('pdf.expenses');
    Route::get('pdf/trial-balance', [PdfController::class, 'trialBalance'])->name('pdf.trial-balance');
    Route::get('pdf/income-statement', [PdfController::class, 'incomeStatement'])->name('pdf.income-statement');
    Route::get('pdf/balance-sheet', [PdfController::class, 'balanceSheet'])->name('pdf.balance-sheet');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings: Users / Roles / Permissions
    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:settings.users.view');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:settings.users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:settings.users.create');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:settings.users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:settings.users.edit');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:settings.users.delete');

        Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:settings.roles.view');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:settings.roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:settings.roles.create');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:settings.roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:settings.roles.edit');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:settings.roles.delete');

        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:settings.permissions.view');
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:settings.permissions.create');
        Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:settings.permissions.create');
        Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:settings.permissions.edit');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:settings.permissions.edit');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:settings.permissions.delete');
    });
});

require __DIR__.'/auth.php';
