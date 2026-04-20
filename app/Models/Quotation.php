<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'quotation_no', 'date', 'valid_until', 'customer_id', 'branch_id',
        'subject', 'subtotal', 'tax', 'discount', 'total',
        'status', 'converted_invoice_id', 'notes', 'terms', 'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function convertedInvoice()
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateQuotationNo(): string
    {
        $last = self::latest('id')->first();
        $number = $last ? (int) substr($last->quotation_no, -6) + 1 : 1;
        return 'QT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
