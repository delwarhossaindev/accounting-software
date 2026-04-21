<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'হেড অফিস',
                'address' => 'হাউজ-১২, রোড-৫, ধানমন্ডি, ঢাকা-১২০৫',
                'phone' => '+৮৮০ ১৭১১-১১১১১১',
                'email' => 'head@demotrading.com',
                'is_head_office' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'চট্টগ্রাম শাখা',
                'address' => 'আগ্রাবাদ বাণিজ্যিক এলাকা, চট্টগ্রাম',
                'phone' => '+৮৮০ ১৭১১-২২২২২২',
                'email' => 'ctg@demotrading.com',
                'is_head_office' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'সিলেট শাখা',
                'address' => 'জিন্দাবাজার, সিলেট',
                'phone' => '+৮৮০ ১৭১১-৩৩৩৩৩৩',
                'email' => 'sylhet@demotrading.com',
                'is_head_office' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'রাজশাহী শাখা',
                'address' => 'সাহেব বাজার, রাজশাহী',
                'phone' => '+৮৮০ ১৭১১-৪৪৪৪৪৪',
                'email' => 'rajshahi@demotrading.com',
                'is_head_office' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($branches as $b) {
            Branch::firstOrCreate(['name' => $b['name']], $b);
        }
    }
}
