<?php

namespace App\Repositories\Contracts;

interface StockMovementRepositoryInterface extends BaseRepositoryInterface
{
    public function forProduct(int $productId);
}
