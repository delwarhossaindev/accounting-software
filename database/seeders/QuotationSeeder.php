<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $headOffice = Branch::where('is_head_office', true)->first() ?? Branch::first();
        $ctgBranch = Branch::where('name', 'চট্টগ্রাম শাখা')->first() ?? $headOffice;

        $customers = Customer::orderBy('id')->take(4)->get();
        if ($customers->isEmpty()) {
            return;
        }

        $laptopBag   = Product::where('sku', 'SKU-00001')->first();
        $mousePad    = Product::where('sku', 'SKU-00002')->first();
        $cartridge   = Product::where('sku', 'SKU-00003')->first();
        $paper       = Product::where('sku', 'SKU-00004')->first();
        $chair       = Product::where('sku', 'SKU-00005')->first();
        $tv          = Product::where('sku', 'SKU-00008')->first();
        $soundbar    = Product::where('sku', 'SKU-00009')->first();

        $quotations = [
            [
                'quotation_no' => 'QT-000001', 'date' => '2026-01-08', 'valid_until' => '2026-01-30',
                'customer_id' => $customers[0]->id, 'branch_id' => $headOffice?->id,
                'subject' => 'অফিস ল্যাপটপ ব্যাগ ও এক্সেসরিজ সাপ্লাই',
                'status' => 'accepted',
                'terms' => '১. অফারটি ২২ দিনের জন্য বৈধ।  ২. ডেলিভারি ৭ কর্মদিবসের মধ্যে।',
                'items' => [
                    ['product_id' => $laptopBag?->id, 'description' => 'ল্যাপটপ ব্যাগ', 'quantity' => 10, 'unit_price' => 500, 'warranty' => '6 months'],
                    ['product_id' => $mousePad?->id, 'description' => 'মাউস প্যাড', 'quantity' => 20, 'unit_price' => 150, 'warranty' => null],
                ],
            ],
            [
                'quotation_no' => 'QT-000002', 'date' => '2026-01-15', 'valid_until' => '2026-02-15',
                'customer_id' => $customers[1]->id, 'branch_id' => $headOffice?->id,
                'subject' => 'প্রিন্টার সাপ্লাই ও স্টেশনারি',
                'status' => 'sent',
                'terms' => 'পেমেন্ট: ৫০% অগ্রিম, ৫০% ডেলিভারিতে।',
                'items' => [
                    ['product_id' => $cartridge?->id, 'description' => 'প্রিন্টার কার্টিজ HP-682', 'quantity' => 5, 'unit_price' => 2500, 'warranty' => null],
                    ['product_id' => $paper?->id, 'description' => 'A4 পেপার (রিম)', 'quantity' => 50, 'unit_price' => 450, 'warranty' => null],
                ],
            ],
            [
                'quotation_no' => 'QT-000003', 'date' => '2026-01-25', 'valid_until' => '2026-02-25',
                'customer_id' => $customers[2]->id, 'branch_id' => $ctgBranch?->id,
                'subject' => 'অফিস ফার্নিচার সাপ্লাই',
                'status' => 'draft',
                'terms' => 'ডেলিভারি ও ইনস্টলেশন অন্তর্ভুক্ত।',
                'items' => [
                    ['product_id' => $chair?->id, 'description' => 'অফিস চেয়ার (এক্সিকিউটিভ)', 'quantity' => 4, 'unit_price' => 8000, 'warranty' => '1 year'],
                ],
            ],
            [
                'quotation_no' => 'QT-000004', 'date' => '2026-02-12', 'valid_until' => '2026-03-12',
                'customer_id' => $customers[3]->id, 'branch_id' => $headOffice?->id,
                'subject' => 'ইলেকট্রনিক্স প্যাকেজ (টিভি + সাউন্ডবার)',
                'status' => 'rejected',
                'terms' => 'প্রাইস অন্তর্ভুক্ত VAT।',
                'items' => [
                    ['product_id' => $tv?->id, 'description' => 'LED টিভি ৩২"', 'quantity' => 2, 'unit_price' => 25000, 'warranty' => '2 years'],
                    ['product_id' => $soundbar?->id, 'description' => 'সাউন্ডবার', 'quantity' => 2, 'unit_price' => 5000, 'warranty' => '1 year'],
                ],
            ],
        ];

        foreach ($quotations as $q) {
            $items = $q['items'];
            unset($q['items']);

            $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $tax = round($subtotal * 0.05, 2);
            $discount = 0;
            $total = $subtotal + $tax - $discount;

            $quotation = Quotation::firstOrCreate(
                ['quotation_no' => $q['quotation_no']],
                array_merge($q, [
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => $discount,
                    'total' => $total,
                    'user_id' => $user->id,
                ])
            );

            if ($quotation->wasRecentlyCreated) {
                foreach ($items as $item) {
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $item['product_id'],
                        'description' => $item['description'],
                        'warranty' => $item['warranty'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'amount' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }
        }
    }
}
