<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // =====================
        // Customers
        // =====================
        $customers = [
            ['name' => 'রহিম ট্রেডার্স', 'email' => 'rahim@example.com', 'phone' => '01711-111111', 'address' => 'মতিঝিল, ঢাকা', 'opening_balance' => 5000],
            ['name' => 'করিম এন্টারপ্রাইজ', 'email' => 'karim@example.com', 'phone' => '01722-222222', 'address' => 'নয়াপল্টন, ঢাকা', 'opening_balance' => 12000],
            ['name' => 'জামাল অ্যান্ড সন্স', 'email' => 'jamal@example.com', 'phone' => '01733-333333', 'address' => 'আগ্রাবাদ, চট্টগ্রাম', 'opening_balance' => 0],
            ['name' => 'সালমা ফ্যাশন হাউস', 'email' => 'salma@example.com', 'phone' => '01744-444444', 'address' => 'বনানী, ঢাকা', 'opening_balance' => 8000],
            ['name' => 'হাসান ইলেকট্রনিক্স', 'email' => 'hasan@example.com', 'phone' => '01755-555555', 'address' => 'এলিফ্যান্ট রোড, ঢাকা', 'opening_balance' => 0],
            ['name' => 'নাসির মার্ট', 'email' => 'nasir@example.com', 'phone' => '01766-666666', 'address' => 'সিলেট সদর, সিলেট', 'opening_balance' => 3500],
            ['name' => 'আমিনুল হক স্টোর', 'email' => 'aminul@example.com', 'phone' => '01777-777777', 'address' => 'রাজশাহী সদর, রাজশাহী', 'opening_balance' => 0],
            ['name' => 'ফাতেমা টেক্সটাইল', 'email' => 'fatema@example.com', 'phone' => '01788-888888', 'address' => 'খুলনা সদর, খুলনা', 'opening_balance' => 15000],
        ];

        $createdCustomers = [];
        foreach ($customers as $c) {
            $createdCustomers[] = Customer::firstOrCreate(['email' => $c['email']], $c);
        }

        // =====================
        // Suppliers
        // =====================
        $suppliers = [
            ['name' => 'ঢাকা হোলসেল সাপ্লায়ার্স', 'email' => 'dhaka.wholesale@example.com', 'phone' => '01811-111111', 'address' => 'চকবাজার, ঢাকা', 'opening_balance' => 20000],
            ['name' => 'চট্টগ্রাম ট্রেডিং কোম্পানি', 'email' => 'ctg.trading@example.com', 'phone' => '01822-222222', 'address' => 'কাটগড়, চট্টগ্রাম', 'opening_balance' => 0],
            ['name' => 'গ্লোবাল ইম্পোর্ট হাউস', 'email' => 'global.import@example.com', 'phone' => '01833-333333', 'address' => 'তেজগাঁও, ঢাকা', 'opening_balance' => 50000],
            ['name' => 'স্টার সাপ্লাই চেইন', 'email' => 'star.supply@example.com', 'phone' => '01844-444444', 'address' => 'উত্তরা, ঢাকা', 'opening_balance' => 10000],
            ['name' => 'রাজশাহী প্রোডাক্টস', 'email' => 'rajshahi.products@example.com', 'phone' => '01855-555555', 'address' => 'রাজশাহী সদর, রাজশাহী', 'opening_balance' => 0],
            ['name' => 'বাংলা ম্যানুফ্যাকচারিং', 'email' => 'bangla.mfg@example.com', 'phone' => '01866-666666', 'address' => 'গাজীপুর, ঢাকা', 'opening_balance' => 35000],
        ];

        $createdSuppliers = [];
        foreach ($suppliers as $s) {
            $createdSuppliers[] = Supplier::firstOrCreate(['email' => $s['email']], $s);
        }

        // Get accounts by code
        $cashInHand = Account::where('code', '1001')->first();
        $cashAtBank = Account::where('code', '1002')->first();
        $accountsReceivable = Account::where('code', '1003')->first();
        $inventory = Account::where('code', '1004')->first();
        $accountsPayable = Account::where('code', '2001')->first();
        $salesRevenue = Account::where('code', '4001')->first();
        $serviceRevenue = Account::where('code', '4002')->first();
        $purchase = Account::where('code', '5002')->first();
        $salaryExpense = Account::where('code', '5101')->first();
        $rentExpense = Account::where('code', '5102')->first();
        $utilityExpense = Account::where('code', '5103')->first();
        $officeSupplies = Account::where('code', '5104')->first();
        $transportExpense = Account::where('code', '5105')->first();
        $telephoneExpense = Account::where('code', '5106')->first();
        $miscExpense = Account::where('code', '5108')->first();
        $cogs = Account::where('code', '5001')->first();

        // =====================
        // Sales Invoices
        // =====================
        $salesInvoices = [
            [
                'invoice_no' => 'INV-000001', 'type' => 'sales', 'date' => '2026-01-05', 'due_date' => '2026-02-05',
                'customer_id' => $createdCustomers[0]->id, 'status' => 'paid',
                'items' => [
                    ['description' => 'ল্যাপটপ ব্যাগ', 'quantity' => 10, 'unit_price' => 500, 'account_id' => $salesRevenue?->id],
                    ['description' => 'মাউস প্যাড', 'quantity' => 20, 'unit_price' => 150, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000002', 'type' => 'sales', 'date' => '2026-01-12', 'due_date' => '2026-02-12',
                'customer_id' => $createdCustomers[1]->id, 'status' => 'partial',
                'items' => [
                    ['description' => 'প্রিন্টার কার্টিজ', 'quantity' => 5, 'unit_price' => 2500, 'account_id' => $salesRevenue?->id],
                    ['description' => 'A4 পেপার (রিম)', 'quantity' => 50, 'unit_price' => 450, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000003', 'type' => 'sales', 'date' => '2026-01-20', 'due_date' => '2026-02-20',
                'customer_id' => $createdCustomers[2]->id, 'status' => 'sent',
                'items' => [
                    ['description' => 'অফিস চেয়ার', 'quantity' => 4, 'unit_price' => 8000, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000004', 'type' => 'sales', 'date' => '2026-02-01', 'due_date' => '2026-03-01',
                'customer_id' => $createdCustomers[3]->id, 'status' => 'paid',
                'items' => [
                    ['description' => 'শাড়ি (সিল্ক)', 'quantity' => 15, 'unit_price' => 3500, 'account_id' => $salesRevenue?->id],
                    ['description' => 'থ্রি-পিস সেট', 'quantity' => 10, 'unit_price' => 2800, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000005', 'type' => 'sales', 'date' => '2026-02-10', 'due_date' => '2026-03-10',
                'customer_id' => $createdCustomers[4]->id, 'status' => 'sent',
                'items' => [
                    ['description' => 'LED টিভি 32"', 'quantity' => 2, 'unit_price' => 25000, 'account_id' => $salesRevenue?->id],
                    ['description' => 'সাউন্ডবার', 'quantity' => 2, 'unit_price' => 5000, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000006', 'type' => 'sales', 'date' => '2026-02-18', 'due_date' => '2026-03-18',
                'customer_id' => $createdCustomers[5]->id, 'status' => 'partial',
                'items' => [
                    ['description' => 'চাল (মিনিকেট) ৫০ কেজি', 'quantity' => 10, 'unit_price' => 3200, 'account_id' => $salesRevenue?->id],
                    ['description' => 'ডাল (মসুর) ১০ কেজি', 'quantity' => 20, 'unit_price' => 1200, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000007', 'type' => 'sales', 'date' => '2026-03-01', 'due_date' => '2026-04-01',
                'customer_id' => $createdCustomers[6]->id, 'status' => 'draft',
                'items' => [
                    ['description' => 'বিস্কুট (কার্টন)', 'quantity' => 30, 'unit_price' => 800, 'account_id' => $salesRevenue?->id],
                ],
            ],
            [
                'invoice_no' => 'INV-000008', 'type' => 'sales', 'date' => '2026-03-10', 'due_date' => '2026-04-10',
                'customer_id' => $createdCustomers[7]->id, 'status' => 'sent',
                'items' => [
                    ['description' => 'লুঙ্গি (ডজন)', 'quantity' => 5, 'unit_price' => 6000, 'account_id' => $salesRevenue?->id],
                    ['description' => 'গামছা (ডজন)', 'quantity' => 10, 'unit_price' => 2400, 'account_id' => $salesRevenue?->id],
                ],
            ],
        ];

        foreach ($salesInvoices as $inv) {
            $items = $inv['items'];
            unset($inv['items']);

            $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $tax = round($subtotal * 0.05, 2);
            $discount = 0;
            $total = $subtotal + $tax - $discount;

            $paidAmount = match ($inv['status']) {
                'paid' => $total,
                'partial' => round($total * 0.5, 2),
                default => 0,
            };

            $invoice = Invoice::firstOrCreate(
                ['invoice_no' => $inv['invoice_no']],
                array_merge($inv, [
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => $discount,
                    'total' => $total,
                    'paid' => $paidAmount,
                    'due' => $total - $paidAmount,
                    'user_id' => $user->id,
                ])
            );

            if ($invoice->wasRecentlyCreated) {
                foreach ($items as $item) {
                    InvoiceItem::create(array_merge($item, [
                        'invoice_id' => $invoice->id,
                        'amount' => $item['quantity'] * $item['unit_price'],
                    ]));
                }
            }
        }

        // =====================
        // Purchase Invoices (Bills)
        // =====================
        $purchaseInvoices = [
            [
                'invoice_no' => 'BILL-000001', 'type' => 'purchase', 'date' => '2026-01-03', 'due_date' => '2026-02-03',
                'supplier_id' => $createdSuppliers[0]->id, 'status' => 'paid',
                'items' => [
                    ['description' => 'ল্যাপটপ ব্যাগ (হোলসেল)', 'quantity' => 50, 'unit_price' => 300, 'account_id' => $purchase?->id],
                    ['description' => 'মাউস প্যাড (হোলসেল)', 'quantity' => 100, 'unit_price' => 80, 'account_id' => $purchase?->id],
                ],
            ],
            [
                'invoice_no' => 'BILL-000002', 'type' => 'purchase', 'date' => '2026-01-10', 'due_date' => '2026-02-10',
                'supplier_id' => $createdSuppliers[1]->id, 'status' => 'paid',
                'items' => [
                    ['description' => 'প্রিন্টার কার্টিজ (বাল্ক)', 'quantity' => 20, 'unit_price' => 1800, 'account_id' => $purchase?->id],
                ],
            ],
            [
                'invoice_no' => 'BILL-000003', 'type' => 'purchase', 'date' => '2026-01-25', 'due_date' => '2026-02-25',
                'supplier_id' => $createdSuppliers[2]->id, 'status' => 'partial',
                'items' => [
                    ['description' => 'অফিস ফার্নিচার সেট', 'quantity' => 10, 'unit_price' => 5500, 'account_id' => $purchase?->id],
                    ['description' => 'ফাইল ক্যাবিনেট', 'quantity' => 5, 'unit_price' => 12000, 'account_id' => $purchase?->id],
                ],
            ],
            [
                'invoice_no' => 'BILL-000004', 'type' => 'purchase', 'date' => '2026-02-05', 'due_date' => '2026-03-05',
                'supplier_id' => $createdSuppliers[3]->id, 'status' => 'sent',
                'items' => [
                    ['description' => 'কাপড় (থান)', 'quantity' => 30, 'unit_price' => 2000, 'account_id' => $purchase?->id],
                ],
            ],
            [
                'invoice_no' => 'BILL-000005', 'type' => 'purchase', 'date' => '2026-02-15', 'due_date' => '2026-03-15',
                'supplier_id' => $createdSuppliers[4]->id, 'status' => 'paid',
                'items' => [
                    ['description' => 'চাল (বস্তা)', 'quantity' => 100, 'unit_price' => 2800, 'account_id' => $purchase?->id],
                ],
            ],
            [
                'invoice_no' => 'BILL-000006', 'type' => 'purchase', 'date' => '2026-03-01', 'due_date' => '2026-04-01',
                'supplier_id' => $createdSuppliers[5]->id, 'status' => 'partial',
                'items' => [
                    ['description' => 'ইলেকট্রনিক্স পার্টস', 'quantity' => 200, 'unit_price' => 500, 'account_id' => $purchase?->id],
                    ['description' => 'প্যাকেজিং ম্যাটেরিয়াল', 'quantity' => 500, 'unit_price' => 50, 'account_id' => $purchase?->id],
                ],
            ],
        ];

        foreach ($purchaseInvoices as $inv) {
            $items = $inv['items'];
            unset($inv['items']);

            $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $tax = round($subtotal * 0.05, 2);
            $total = $subtotal + $tax;

            $paidAmount = match ($inv['status']) {
                'paid' => $total,
                'partial' => round($total * 0.5, 2),
                default => 0,
            };

            $invoice = Invoice::firstOrCreate(
                ['invoice_no' => $inv['invoice_no']],
                array_merge($inv, [
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => 0,
                    'total' => $total,
                    'paid' => $paidAmount,
                    'due' => $total - $paidAmount,
                    'user_id' => $user->id,
                ])
            );

            if ($invoice->wasRecentlyCreated) {
                foreach ($items as $item) {
                    InvoiceItem::create(array_merge($item, [
                        'invoice_id' => $invoice->id,
                        'amount' => $item['quantity'] * $item['unit_price'],
                    ]));
                }
            }
        }

        // =====================
        // Payments Received (from customers)
        // =====================
        $paymentsReceived = [
            ['payment_no' => 'RCV-000001', 'type' => 'received', 'date' => '2026-01-20', 'customer_id' => $createdCustomers[0]->id, 'invoice_id' => 1, 'account_id' => $cashInHand?->id, 'amount' => 8925, 'payment_method' => 'cash', 'notes' => 'পুরো টাকা পরিশোধ'],
            ['payment_no' => 'RCV-000002', 'type' => 'received', 'date' => '2026-01-25', 'customer_id' => $createdCustomers[1]->id, 'invoice_id' => 2, 'account_id' => $cashAtBank?->id, 'amount' => 18375, 'payment_method' => 'bank_transfer', 'notes' => 'আংশিক পরিশোধ - বিকাশ'],
            ['payment_no' => 'RCV-000003', 'type' => 'received', 'date' => '2026-02-15', 'customer_id' => $createdCustomers[3]->id, 'invoice_id' => 4, 'account_id' => $cashAtBank?->id, 'amount' => 83475, 'payment_method' => 'bank_transfer', 'notes' => 'পুরো টাকা - ব্যাংক ট্রান্সফার'],
            ['payment_no' => 'RCV-000004', 'type' => 'received', 'date' => '2026-03-01', 'customer_id' => $createdCustomers[5]->id, 'invoice_id' => 6, 'account_id' => $cashInHand?->id, 'amount' => 29400, 'payment_method' => 'cash', 'notes' => 'আংশিক পরিশোধ'],
        ];

        foreach ($paymentsReceived as $p) {
            // Get invoice by invoice_no instead of hardcoded ID
            $invoiceNo = 'INV-' . str_pad($p['invoice_id'], 6, '0', STR_PAD_LEFT);
            $invoice = Invoice::where('invoice_no', $invoiceNo)->first();
            $p['invoice_id'] = $invoice?->id;
            $p['user_id'] = $user->id;

            Payment::firstOrCreate(['payment_no' => $p['payment_no']], $p);
        }

        // =====================
        // Payments Made (to suppliers)
        // =====================
        $paymentsMade = [
            ['payment_no' => 'PAY-000001', 'type' => 'made', 'date' => '2026-01-15', 'supplier_id' => $createdSuppliers[0]->id, 'invoice_id' => 'BILL-000001', 'account_id' => $cashInHand?->id, 'amount' => 24150, 'payment_method' => 'cash', 'notes' => 'সাপ্লায়ার পেমেন্ট - ক্যাশ'],
            ['payment_no' => 'PAY-000002', 'type' => 'made', 'date' => '2026-01-22', 'supplier_id' => $createdSuppliers[1]->id, 'invoice_id' => 'BILL-000002', 'account_id' => $cashAtBank?->id, 'amount' => 37800, 'payment_method' => 'bank_transfer', 'notes' => 'চেক পেমেন্ট'],
            ['payment_no' => 'PAY-000003', 'type' => 'made', 'date' => '2026-02-20', 'supplier_id' => $createdSuppliers[2]->id, 'invoice_id' => 'BILL-000003', 'account_id' => $cashAtBank?->id, 'amount' => 63000, 'payment_method' => 'cheque', 'notes' => 'আংশিক পেমেন্ট'],
            ['payment_no' => 'PAY-000004', 'type' => 'made', 'date' => '2026-03-05', 'supplier_id' => $createdSuppliers[4]->id, 'invoice_id' => 'BILL-000005', 'account_id' => $cashInHand?->id, 'amount' => 294000, 'payment_method' => 'cash', 'notes' => 'চাল ক্রয় পেমেন্ট'],
        ];

        foreach ($paymentsMade as $p) {
            $invoice = Invoice::where('invoice_no', $p['invoice_id'])->first();
            $p['invoice_id'] = $invoice?->id;
            $p['user_id'] = $user->id;

            Payment::firstOrCreate(['payment_no' => $p['payment_no']], $p);
        }

        // =====================
        // Expenses
        // =====================
        $expenses = [
            ['expense_no' => 'EXP-000001', 'date' => '2026-01-05', 'account_id' => $rentExpense?->id, 'amount' => 25000, 'category' => 'rent', 'payment_method' => 'bank_transfer', 'description' => 'জানুয়ারি মাসের অফিস ভাড়া'],
            ['expense_no' => 'EXP-000002', 'date' => '2026-01-10', 'account_id' => $utilityExpense?->id, 'amount' => 5500, 'category' => 'utilities', 'payment_method' => 'cash', 'description' => 'বিদ্যুৎ বিল - জানুয়ারি'],
            ['expense_no' => 'EXP-000003', 'date' => '2026-01-15', 'account_id' => $salaryExpense?->id, 'amount' => 120000, 'category' => 'salary', 'payment_method' => 'bank_transfer', 'description' => 'কর্মচারী বেতন - জানুয়ারি (৪ জন)'],
            ['expense_no' => 'EXP-000004', 'date' => '2026-01-20', 'account_id' => $officeSupplies?->id, 'amount' => 3500, 'category' => 'office', 'payment_method' => 'cash', 'description' => 'অফিস স্টেশনারি ক্রয়'],
            ['expense_no' => 'EXP-000005', 'date' => '2026-02-05', 'account_id' => $rentExpense?->id, 'amount' => 25000, 'category' => 'rent', 'payment_method' => 'bank_transfer', 'description' => 'ফেব্রুয়ারি মাসের অফিস ভাড়া'],
            ['expense_no' => 'EXP-000006', 'date' => '2026-02-08', 'account_id' => $telephoneExpense?->id, 'amount' => 4200, 'category' => 'telephone', 'payment_method' => 'cash', 'description' => 'ইন্টারনেট ও ফোন বিল'],
            ['expense_no' => 'EXP-000007', 'date' => '2026-02-10', 'account_id' => $utilityExpense?->id, 'amount' => 6200, 'category' => 'utilities', 'payment_method' => 'cash', 'description' => 'বিদ্যুৎ ও গ্যাস বিল - ফেব্রুয়ারি'],
            ['expense_no' => 'EXP-000008', 'date' => '2026-02-15', 'account_id' => $salaryExpense?->id, 'amount' => 120000, 'category' => 'salary', 'payment_method' => 'bank_transfer', 'description' => 'কর্মচারী বেতন - ফেব্রুয়ারি'],
            ['expense_no' => 'EXP-000009', 'date' => '2026-02-22', 'account_id' => $transportExpense?->id, 'amount' => 8000, 'category' => 'transport', 'payment_method' => 'cash', 'description' => 'মাল পরিবহন খরচ'],
            ['expense_no' => 'EXP-000010', 'date' => '2026-03-05', 'account_id' => $rentExpense?->id, 'amount' => 25000, 'category' => 'rent', 'payment_method' => 'bank_transfer', 'description' => 'মার্চ মাসের অফিস ভাড়া'],
            ['expense_no' => 'EXP-000011', 'date' => '2026-03-10', 'account_id' => $utilityExpense?->id, 'amount' => 5800, 'category' => 'utilities', 'payment_method' => 'cash', 'description' => 'বিদ্যুৎ বিল - মার্চ'],
            ['expense_no' => 'EXP-000012', 'date' => '2026-03-15', 'account_id' => $salaryExpense?->id, 'amount' => 120000, 'category' => 'salary', 'payment_method' => 'bank_transfer', 'description' => 'কর্মচারী বেতন - মার্চ'],
            ['expense_no' => 'EXP-000013', 'date' => '2026-03-18', 'account_id' => $miscExpense?->id, 'supplier_id' => $createdSuppliers[3]->id, 'amount' => 15000, 'category' => 'maintenance', 'payment_method' => 'cash', 'description' => 'অফিস মেরামত ও রক্ষণাবেক্ষণ'],
            ['expense_no' => 'EXP-000014', 'date' => '2026-03-25', 'account_id' => $officeSupplies?->id, 'amount' => 7500, 'category' => 'office', 'payment_method' => 'cash', 'description' => 'প্রিন্টার কালি ও কাগজ ক্রয়'],
        ];

        foreach ($expenses as $e) {
            $e['user_id'] = $user->id;
            Expense::firstOrCreate(['expense_no' => $e['expense_no']], $e);
        }

        // =====================
        // Journal Entries
        // =====================
        $journalEntries = [
            [
                'voucher_no' => 'JOU-000001', 'date' => '2026-01-01', 'voucher_type' => 'journal',
                'narration' => 'প্রারম্ভিক মূলধন জমা - ব্যবসা শুরু',
                'items' => [
                    ['account_id' => $cashInHand?->id, 'debit' => 200000, 'credit' => 0, 'description' => 'নগদ মূলধন'],
                    ['account_id' => $cashAtBank?->id, 'debit' => 500000, 'credit' => 0, 'description' => 'ব্যাংকে জমা'],
                    ['account_id' => Account::where('code', '3001')->first()?->id, 'debit' => 0, 'credit' => 700000, 'description' => 'মালিকের মূলধন'],
                ],
            ],
            [
                'voucher_no' => 'JOU-000002', 'date' => '2026-01-15', 'voucher_type' => 'contra',
                'narration' => 'ব্যাংক থেকে নগদ উত্তোলন',
                'items' => [
                    ['account_id' => $cashInHand?->id, 'debit' => 50000, 'credit' => 0, 'description' => 'নগদ গ্রহণ'],
                    ['account_id' => $cashAtBank?->id, 'debit' => 0, 'credit' => 50000, 'description' => 'ব্যাংক থেকে উত্তোলন'],
                ],
            ],
            [
                'voucher_no' => 'JOU-000003', 'date' => '2026-02-01', 'voucher_type' => 'journal',
                'narration' => 'ফার্নিচার ক্রয় - অফিস সেটআপ',
                'items' => [
                    ['account_id' => Account::where('code', '1501')->first()?->id, 'debit' => 85000, 'credit' => 0, 'description' => 'ফার্নিচার ক্রয়'],
                    ['account_id' => $cashAtBank?->id, 'debit' => 0, 'credit' => 85000, 'description' => 'ব্যাংক থেকে পেমেন্ট'],
                ],
            ],
            [
                'voucher_no' => 'JOU-000004', 'date' => '2026-02-10', 'voucher_type' => 'journal',
                'narration' => 'কম্পিউটার ও প্রিন্টার ক্রয়',
                'items' => [
                    ['account_id' => Account::where('code', '1502')->first()?->id, 'debit' => 120000, 'credit' => 0, 'description' => 'অফিস সরঞ্জাম ক্রয়'],
                    ['account_id' => $cashAtBank?->id, 'debit' => 0, 'credit' => 120000, 'description' => 'ব্যাংক পেমেন্ট'],
                ],
            ],
            [
                'voucher_no' => 'JOU-000005', 'date' => '2026-03-01', 'voucher_type' => 'contra',
                'narration' => 'নগদ ব্যাংকে জমা',
                'items' => [
                    ['account_id' => $cashAtBank?->id, 'debit' => 100000, 'credit' => 0, 'description' => 'ব্যাংকে জমা'],
                    ['account_id' => $cashInHand?->id, 'debit' => 0, 'credit' => 100000, 'description' => 'নগদ থেকে স্থানান্তর'],
                ],
            ],
            [
                'voucher_no' => 'JOU-000006', 'date' => '2026-03-15', 'voucher_type' => 'journal',
                'narration' => 'অবচয় সমন্বয় - ত্রৈমাসিক',
                'items' => [
                    ['account_id' => Account::where('code', '5107')->first()?->id, 'debit' => 15000, 'credit' => 0, 'description' => 'অবচয় খরচ'],
                    ['account_id' => Account::where('code', '1501')->first()?->id, 'debit' => 0, 'credit' => 8000, 'description' => 'ফার্নিচার অবচয়'],
                    ['account_id' => Account::where('code', '1502')->first()?->id, 'debit' => 0, 'credit' => 7000, 'description' => 'সরঞ্জাম অবচয়'],
                ],
            ],
        ];

        foreach ($journalEntries as $je) {
            $items = $je['items'];
            unset($je['items']);

            $totalAmount = collect($items)->sum('debit');
            $je['total_amount'] = $totalAmount;
            $je['user_id'] = $user->id;

            $entry = JournalEntry::firstOrCreate(
                ['voucher_no' => $je['voucher_no']],
                $je
            );

            if ($entry->wasRecentlyCreated) {
                foreach ($items as $item) {
                    if ($item['account_id']) {
                        JournalEntryItem::create(array_merge($item, [
                            'journal_entry_id' => $entry->id,
                        ]));
                    }
                }
            }
        }
    }
}
