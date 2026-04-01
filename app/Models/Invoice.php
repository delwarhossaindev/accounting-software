<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no', 'type', 'date', 'due_date', 'customer_id', 'supplier_id',
        'subtotal', 'tax', 'discount', 'total', 'paid', 'due', 'status', 'notes', 'user_id'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateInvoiceNo($type = 'sales')
    {
        $prefix = $type === 'sales' ? 'INV' : 'BILL';
        $last = self::where('type', $type)->latest('id')->first();
        $number = $last ? (int)substr($last->invoice_no, -6) + 1 : 1;
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
