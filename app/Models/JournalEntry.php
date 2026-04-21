<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no', 'date', 'narration', 'voucher_type',
        'user_id', 'total_amount',
        'source_type', 'source_id', 'is_auto_posted',
    ];

    protected $casts = [
        'date' => 'date',
        'is_auto_posted' => 'boolean',
    ];

    public function source()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateVoucherNo($type = 'journal')
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $last = self::where('voucher_type', $type)->latest('id')->first();
        $number = $last ? (int)substr($last->voucher_no, -6) + 1 : 1;
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
