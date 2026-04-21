<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringExpense extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name', 'account_id', 'supplier_id',
        'frequency', 'start_date', 'end_date', 'next_run_date',
        'amount', 'category', 'payment_method', 'description',
        'is_active', 'generated_count', 'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_run_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function account()  { return $this->belongsTo(Account::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function user()     { return $this->belongsTo(User::class); }

    public function advanceNextRun(): void
    {
        $next = $this->next_run_date;
        $this->next_run_date = match ($this->frequency) {
            'daily'     => $next->copy()->addDay(),
            'weekly'    => $next->copy()->addWeek(),
            'quarterly' => $next->copy()->addMonths(3),
            'yearly'    => $next->copy()->addYear(),
            default     => $next->copy()->addMonth(),
        };
    }
}
