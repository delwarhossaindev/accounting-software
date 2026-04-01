<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_no', 'type', 'date', 'customer_id', 'supplier_id',
        'invoice_id', 'account_id', 'amount', 'payment_method', 'reference', 'notes', 'user_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generatePaymentNo($type = 'received')
    {
        $prefix = $type === 'received' ? 'RCV' : 'PAY';
        $last = self::where('type', $type)->latest('id')->first();
        $number = $last ? (int)substr($last->payment_no, -6) + 1 : 1;
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
