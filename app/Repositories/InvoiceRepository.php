<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function byType(string $type, array $with = []): Collection
    {
        return $this->query()
            ->with($with)
            ->where('type', $type)
            ->latest('date')
            ->get();
    }

    public function unpaid()
    {
        return $this->query()
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->get();
    }

    public function pastDue(string $date)
    {
        return $this->query()
            ->where('type', 'sales')
            ->where('due', '>', 0)
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->whereDate('due_date', '<', $date)
            ->get();
    }
}
