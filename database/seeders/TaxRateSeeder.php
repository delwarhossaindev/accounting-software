<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['name' => 'VAT 15%',   'rate' => 15.00, 'is_default' => true,  'is_active' => true],
            ['name' => 'VAT 7.5%',  'rate' => 7.50,  'is_default' => false, 'is_active' => true],
            ['name' => 'VAT 5%',    'rate' => 5.00,  'is_default' => false, 'is_active' => true],
            ['name' => 'VAT 2.4%',  'rate' => 2.40,  'is_default' => false, 'is_active' => true],
            ['name' => 'Zero Rated','rate' => 0.00,  'is_default' => false, 'is_active' => true],
            ['name' => 'AIT 5%',    'rate' => 5.00,  'is_default' => false, 'is_active' => true],
        ];

        foreach ($rates as $r) {
            TaxRate::firstOrCreate(['name' => $r['name']], $r);
        }
    }
}
