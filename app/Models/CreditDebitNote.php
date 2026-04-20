<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDebitNote extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'note_no', 'type', 'date', 'customer_id', 'supplier_id',
        'invoice_id', 'reason', 'subtotal', 'tax', 'total',
        'notes', 'user_id',
    ];

    protected $casts = ['date' => 'date'];

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

    public function items()
    {
        return $this->hasMany(CreditDebitNoteItem::class, 'note_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateNoteNo(string $type): string
    {
        $prefix = $type === 'credit' ? 'CN' : 'DN';
        $last = self::where('type', $type)->latest('id')->first();
        $number = $last ? (int) substr($last->note_no, -6) + 1 : 1;
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
