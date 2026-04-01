<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_no', 'date', 'account_id', 'supplier_id', 'amount',
        'category', 'payment_method', 'reference', 'description', 'user_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateExpenseNo()
    {
        $last = self::latest('id')->first();
        $number = $last ? (int)substr($last->expense_no, -6) + 1 : 1;
        return 'EXP-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
