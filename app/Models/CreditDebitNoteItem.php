<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDebitNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id', 'product_id', 'description',
        'quantity', 'unit_price', 'amount',
    ];

    public function note()
    {
        return $this->belongsTo(CreditDebitNote::class, 'note_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
