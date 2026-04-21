<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $products = [
            ['sku' => 'SKU-00001', 'name' => 'ল্যাপটপ ব্যাগ', 'description' => '১৫.৬ ইঞ্চি ব্যাকপ্যাক, ওয়াটার প্রুফ', 'unit' => 'pcs', 'purchase_price' => 300, 'sale_price' => 500, 'current_stock' => 0, 'reorder_level' => 20, 'category' => 'Bags'],
            ['sku' => 'SKU-00002', 'name' => 'মাউস প্যাড', 'description' => 'নন-স্লিপ রাবার মাউস প্যাড', 'unit' => 'pcs', 'purchase_price' => 80, 'sale_price' => 150, 'current_stock' => 0, 'reorder_level' => 50, 'category' => 'Accessories'],
            ['sku' => 'SKU-00003', 'name' => 'প্রিন্টার কার্টিজ HP-682', 'description' => 'কালার ইঙ্ক কার্টিজ', 'unit' => 'pcs', 'purchase_price' => 1800, 'sale_price' => 2500, 'current_stock' => 0, 'reorder_level' => 10, 'category' => 'Stationery'],
            ['sku' => 'SKU-00004', 'name' => 'A4 পেপার (রিম)', 'description' => '৮০ GSM, ৫০০ শীট প্রতি রিম', 'unit' => 'box', 'purchase_price' => 350, 'sale_price' => 450, 'current_stock' => 0, 'reorder_level' => 30, 'category' => 'Stationery'],
            ['sku' => 'SKU-00005', 'name' => 'অফিস চেয়ার', 'description' => 'হাই ব্যাক এক্সিকিউটিভ চেয়ার', 'unit' => 'pcs', 'purchase_price' => 5500, 'sale_price' => 8000, 'current_stock' => 0, 'reorder_level' => 5, 'category' => 'Furniture'],
            ['sku' => 'SKU-00006', 'name' => 'শাড়ি (সিল্ক)', 'description' => 'বেনারসি সিল্ক শাড়ি', 'unit' => 'pcs', 'purchase_price' => 2200, 'sale_price' => 3500, 'current_stock' => 0, 'reorder_level' => 10, 'category' => 'Textile'],
            ['sku' => 'SKU-00007', 'name' => 'থ্রি-পিস সেট', 'description' => 'লেডিস থ্রি-পিস আনস্টিচড', 'unit' => 'pcs', 'purchase_price' => 1700, 'sale_price' => 2800, 'current_stock' => 0, 'reorder_level' => 15, 'category' => 'Textile'],
            ['sku' => 'SKU-00008', 'name' => 'LED টিভি ৩২"', 'description' => 'Smart LED TV, Full HD', 'unit' => 'pcs', 'purchase_price' => 18000, 'sale_price' => 25000, 'current_stock' => 0, 'reorder_level' => 3, 'category' => 'Electronics'],
            ['sku' => 'SKU-00009', 'name' => 'সাউন্ডবার', 'description' => 'Bluetooth সাউন্ডবার ২.১', 'unit' => 'pcs', 'purchase_price' => 3200, 'sale_price' => 5000, 'current_stock' => 0, 'reorder_level' => 5, 'category' => 'Electronics'],
            ['sku' => 'SKU-00010', 'name' => 'চাল (মিনিকেট) ৫০ কেজি', 'description' => 'প্রিমিয়াম মিনিকেট চাল', 'unit' => 'bag', 'purchase_price' => 2800, 'sale_price' => 3200, 'current_stock' => 0, 'reorder_level' => 20, 'category' => 'Grocery'],
            ['sku' => 'SKU-00011', 'name' => 'ডাল (মসুর) ১০ কেজি', 'description' => 'দেশি মসুর ডাল', 'unit' => 'bag', 'purchase_price' => 950, 'sale_price' => 1200, 'current_stock' => 0, 'reorder_level' => 25, 'category' => 'Grocery'],
            ['sku' => 'SKU-00012', 'name' => 'বিস্কুট (কার্টন)', 'description' => 'অলিম্পিক মিক্স কার্টন', 'unit' => 'carton', 'purchase_price' => 600, 'sale_price' => 800, 'current_stock' => 0, 'reorder_level' => 20, 'category' => 'Grocery'],
            ['sku' => 'SKU-00013', 'name' => 'লুঙ্গি (ডজন)', 'description' => 'পাকিজা লুঙ্গি ডজন', 'unit' => 'dozen', 'purchase_price' => 4500, 'sale_price' => 6000, 'current_stock' => 0, 'reorder_level' => 8, 'category' => 'Textile'],
            ['sku' => 'SKU-00014', 'name' => 'গামছা (ডজন)', 'description' => 'সুতি গামছা, ডজন', 'unit' => 'dozen', 'purchase_price' => 1800, 'sale_price' => 2400, 'current_stock' => 0, 'reorder_level' => 10, 'category' => 'Textile'],
            ['sku' => 'SKU-00015', 'name' => 'মোবাইল চার্জার', 'description' => 'USB-C Fast Charger ২৫W', 'unit' => 'pcs', 'purchase_price' => 450, 'sale_price' => 750, 'current_stock' => 0, 'reorder_level' => 30, 'category' => 'Electronics'],
        ];

        foreach ($products as $p) {
            $p['is_active'] = true;
            $product = Product::firstOrCreate(['sku' => $p['sku']], $p);

            if ($product->wasRecentlyCreated) {
                $openingQty = match ($product->category) {
                    'Electronics' => 10,
                    'Furniture'   => 8,
                    'Grocery'     => 50,
                    'Textile'     => 30,
                    'Stationery'  => 40,
                    default       => 25,
                };

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $openingQty,
                    'unit_price' => $product->purchase_price,
                    'date' => '2026-01-01',
                    'reference_type' => 'opening',
                    'reference_id' => null,
                    'notes' => 'প্রারম্ভিক স্টক',
                    'user_id' => $user?->id,
                ]);

                $product->update(['current_stock' => $openingQty]);
            }
        }
    }
}
