<?php

namespace App\Repositories;

use App\Models\StockMovement;
use App\Repositories\Contracts\StockMovementRepositoryInterface;

class StockMovementRepository extends BaseRepository implements StockMovementRepositoryInterface
{
    public function __construct(StockMovement $model)
    {
        parent::__construct($model);
    }

    public function forProduct(int $productId)
    {
        return $this->query()
            ->where('product_id', $productId)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();
    }
}
