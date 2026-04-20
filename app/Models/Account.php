<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['code', 'name', 'type', 'account_group_id', 'opening_balance', 'description', 'is_active'];

    public function group()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function getBalanceAttribute()
    {
        $debits = $this->journalEntryItems()->sum('debit');
        $credits = $this->journalEntryItems()->sum('credit');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $this->opening_balance + $debits - $credits;
        }
        return $this->opening_balance + $credits - $debits;
    }

    public function getDebitTotalAttribute()
    {
        return $this->journalEntryItems()->sum('debit');
    }

    public function getCreditTotalAttribute()
    {
        return $this->journalEntryItems()->sum('credit');
    }
}
