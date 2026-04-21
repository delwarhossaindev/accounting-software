<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
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
}
