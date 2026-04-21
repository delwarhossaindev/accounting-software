<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringInvoice extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name', 'type', 'customer_id', 'supplier_id', 'branch_id',
        'frequency', 'start_date', 'end_date', 'next_run_date', 'day_of_period',
        'subtotal', 'tax_rate', 'discount', 'notes',
        'is_active', 'generated_count', 'items', 'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_run_date' => 'date',
        'is_active' => 'boolean',
        'items' => 'array',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function branch()   { return $this->belongsTo(Branch::class); }
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
