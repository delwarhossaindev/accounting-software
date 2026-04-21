<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'ডেমো ট্রেডিং কোম্পানি',
                'address' => 'হাউজ-১২, রোড-৫, ধানমন্ডি, ঢাকা-১২০৫',
                'phone' => '+৮৮০ ১৭১১-১২৩৪৫৬',
                'email' => 'info@demotrading.com',
                'website' => 'https://demotrading.com',
                'tin' => '১২৩৪৫৬৭৮৯০১২',
                'bin' => '০০০১২৩৪৫-০১০১',
                'currency_code' => 'BDT',
                'currency_symbol' => '৳',
                'fiscal_year_start_month' => 7,
                'invoice_prefix' => 'INV',
                'bill_prefix' => 'BILL',
                'invoice_footer' => 'ধন্যবাদ আমাদের সাথে ব্যবসা করার জন্য।',
                'terms_conditions' => "১. পণ্য ডেলিভারির ৭ দিনের মধ্যে পেমেন্ট পরিশোধ করতে হবে।\n২. বিক্রিত পণ্য ফেরতযোগ্য নয়।\n৩. ডেলিভারি চার্জ ক্রেতা বহন করবেন।",
            ]
        );
    }
}
