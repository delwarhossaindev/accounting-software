<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Map of interface => concrete implementation. Laravel auto-registers these.
     * Add a new repository = add one line here.
     */
    public array $bindings = [
        \App\Repositories\Contracts\AccountRepositoryInterface::class             => \App\Repositories\AccountRepository::class,
        \App\Repositories\Contracts\AccountGroupRepositoryInterface::class        => \App\Repositories\AccountGroupRepository::class,
        \App\Repositories\Contracts\CustomerRepositoryInterface::class            => \App\Repositories\CustomerRepository::class,
        \App\Repositories\Contracts\SupplierRepositoryInterface::class            => \App\Repositories\SupplierRepository::class,
        \App\Repositories\Contracts\InvoiceRepositoryInterface::class             => \App\Repositories\InvoiceRepository::class,
        \App\Repositories\Contracts\PaymentRepositoryInterface::class             => \App\Repositories\PaymentRepository::class,
        \App\Repositories\Contracts\ExpenseRepositoryInterface::class             => \App\Repositories\ExpenseRepository::class,
        \App\Repositories\Contracts\JournalEntryRepositoryInterface::class        => \App\Repositories\JournalEntryRepository::class,
        \App\Repositories\Contracts\ProductRepositoryInterface::class             => \App\Repositories\ProductRepository::class,
        \App\Repositories\Contracts\TaxRateRepositoryInterface::class             => \App\Repositories\TaxRateRepository::class,
        \App\Repositories\Contracts\BranchRepositoryInterface::class              => \App\Repositories\BranchRepository::class,
        \App\Repositories\Contracts\QuotationRepositoryInterface::class           => \App\Repositories\QuotationRepository::class,
        \App\Repositories\Contracts\CreditDebitNoteRepositoryInterface::class     => \App\Repositories\CreditDebitNoteRepository::class,
        \App\Repositories\Contracts\RecurringInvoiceRepositoryInterface::class    => \App\Repositories\RecurringInvoiceRepository::class,
        \App\Repositories\Contracts\RecurringExpenseRepositoryInterface::class    => \App\Repositories\RecurringExpenseRepository::class,
        \App\Repositories\Contracts\UserRepositoryInterface::class                => \App\Repositories\UserRepository::class,
        \App\Repositories\Contracts\BankStatementLineRepositoryInterface::class   => \App\Repositories\BankStatementLineRepository::class,
        \App\Repositories\Contracts\BankReconciliationRepositoryInterface::class  => \App\Repositories\BankReconciliationRepository::class,
        \App\Repositories\Contracts\StockMovementRepositoryInterface::class       => \App\Repositories\StockMovementRepository::class,
        \App\Repositories\Contracts\AuditLogRepositoryInterface::class            => \App\Repositories\AuditLogRepository::class,
        \App\Repositories\Contracts\CompanySettingRepositoryInterface::class      => \App\Repositories\CompanySettingRepository::class,
    ];
}
