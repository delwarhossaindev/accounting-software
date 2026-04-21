<?php

namespace Database\Seeders;

use App\Models\CreditDebitNote;
use App\Models\CreditDebitNoteItem;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreditDebitNoteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $customers = Customer::orderBy('id')->get();
        $suppliers = Supplier::orderBy('id')->get();

        $laptopBag  = Product::where('sku', 'SKU-00001')->first();
        $mousePad   = Product::where('sku', 'SKU-00002')->first();
        $cartridge  = Product::where('sku', 'SKU-00003')->first();
        $chair      = Product::where('sku', 'SKU-00005')->first();
        $tv         = Product::where('sku', 'SKU-00008')->first();

        $notes = [
            [
                'note_no' => 'CN-000001', 'type' => 'credit', 'date' => '2026-01-22',
                'customer_id' => $customers[0]?->id, 'supplier_id' => null,
                'invoice_no' => 'INV-000001',
                'reason' => 'ক্রেতা কর্তৃক পণ্য ফেরত (ত্রুটিপূর্ণ)',
                'notes' => 'ল্যাপটপ ব্যাগের জিপার ত্রুটিপূর্ণ পাওয়া গেছে',
                'items' => [
                    ['product_id' => $laptopBag?->id, 'description' => 'ল্যাপটপ ব্যাগ (ফেরত)', 'quantity' => 2, 'unit_price' => 500],
                ],
            ],
            [
                'note_no' => 'CN-000002', 'type' => 'credit', 'date' => '2026-02-14',
                'customer_id' => $customers[3]?->id, 'supplier_id' => null,
                'invoice_no' => 'INV-000004',
                'reason' => 'অতিরিক্ত পণ্য পাঠানো হয়েছিল, এডজাস্টমেন্ট',
                'notes' => 'বিল সংশোধন',
                'items' => [
                    ['product_id' => null, 'description' => 'শাড়ি (অতিরিক্ত ফেরত)', 'quantity' => 1, 'unit_price' => 3500],
                ],
            ],
            [
                'note_no' => 'CN-000003', 'type' => 'credit', 'date' => '2026-02-22',
                'customer_id' => $customers[4]?->id, 'supplier_id' => null,
                'invoice_no' => 'INV-000005',
                'reason' => 'পণ্য ড্যামেজ হয়ে এসেছে',
                'notes' => 'LED টিভির স্ক্রিনে ক্র্যাক',
                'items' => [
                    ['product_id' => $tv?->id, 'description' => 'LED টিভি ৩২" (ড্যামেজ ফেরত)', 'quantity' => 1, 'unit_price' => 25000],
                ],
            ],
            [
                'note_no' => 'DN-000001', 'type' => 'debit', 'date' => '2026-01-18',
                'customer_id' => null, 'supplier_id' => $suppliers[0]?->id,
                'invoice_no' => 'BILL-000001',
                'reason' => 'সাপ্লায়ার কে ত্রুটিপূর্ণ পণ্য ফেরত',
                'notes' => 'মাউস প্যাডের কোয়ালিটি খারাপ',
                'items' => [
                    ['product_id' => $mousePad?->id, 'description' => 'মাউস প্যাড (ফেরত সাপ্লায়ার)', 'quantity' => 10, 'unit_price' => 80],
                ],
            ],
            [
                'note_no' => 'DN-000002', 'type' => 'debit', 'date' => '2026-02-02',
                'customer_id' => null, 'supplier_id' => $suppliers[1]?->id,
                'invoice_no' => 'BILL-000002',
                'reason' => 'ভুল মডেল পাঠানো',
                'notes' => 'ভুল কার্টিজ মডেল',
                'items' => [
                    ['product_id' => $cartridge?->id, 'description' => 'প্রিন্টার কার্টিজ (ভুল মডেল)', 'quantity' => 2, 'unit_price' => 1800],
                ],
            ],
            [
                'note_no' => 'DN-000003', 'type' => 'debit', 'date' => '2026-02-28',
                'customer_id' => null, 'supplier_id' => $suppliers[2]?->id,
                'invoice_no' => 'BILL-000003',
                'reason' => 'কোয়ালিটি ইস্যু - ফার্নিচার ফেরত',
                'notes' => 'অফিস চেয়ার হ্যান্ডেল ভাঙা',
                'items' => [
                    ['product_id' => $chair?->id, 'description' => 'অফিস চেয়ার (ফেরত সাপ্লায়ার)', 'quantity' => 1, 'unit_price' => 5500],
                ],
            ],
        ];

        foreach ($notes as $n) {
            $items = $n['items'];
            $invoiceNo = $n['invoice_no'] ?? null;
            unset($n['items'], $n['invoice_no']);

            $n['invoice_id'] = $invoiceNo
                ? Invoice::where('invoice_no', $invoiceNo)->value('id')
                : null;

            $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $tax = round($subtotal * 0.05, 2);
            $total = $subtotal + $tax;

            $n['subtotal'] = $subtotal;
            $n['tax'] = $tax;
            $n['total'] = $total;
            $n['user_id'] = $user->id;

            $note = CreditDebitNote::firstOrCreate(['note_no' => $n['note_no']], $n);

            if ($note->wasRecentlyCreated) {
                foreach ($items as $item) {
                    CreditDebitNoteItem::create([
                        'note_id' => $note->id,
                        'product_id' => $item['product_id'],
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'amount' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }
        }
    }
}
