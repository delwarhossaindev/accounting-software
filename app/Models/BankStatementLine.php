<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankStatementLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id', 'date', 'description', 'reference',
        'debit', 'credit', 'balance', 'status',
        'journal_entry_id', 'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function account()       { return $this->belongsTo(Account::class); }
    public function journalEntry()  { return $this->belongsTo(JournalEntry::class); }
    public function user()          { return $this->belongsTo(User::class); }
}
