<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankReconciliation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'account_id', 'start_date', 'end_date',
        'statement_opening', 'statement_closing',
        'book_balance', 'difference', 'status', 'notes', 'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function account() { return $this->belongsTo(Account::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
