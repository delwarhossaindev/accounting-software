<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'logo_path', 'address', 'phone', 'email', 'website',
        'tin', 'bin', 'currency_code', 'currency_symbol',
        'fiscal_year_start_month', 'invoice_prefix', 'bill_prefix',
        'invoice_footer', 'terms_conditions',
    ];

    protected $casts = [
        'fiscal_year_start_month' => 'integer',
    ];

    public static function current(): self
    {
        return Cache::remember('company_settings', 3600, function () {
            return self::firstOrCreate(['id' => 1], ['name' => 'My Company']);
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('company_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::forgetCache());
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }
}
