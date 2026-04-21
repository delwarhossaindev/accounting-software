<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function active()
    {
        return $this->query()->where('is_active', true)->orderBy('name')->get();
    }

    public function lowStock()
    {
        return $this->query()
            ->where('is_active', true)
            ->whereRaw('current_stock <= reorder_level')
            ->where('reorder_level', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function outOfStock()
    {
        return $this->query()->where('current_stock', '<=', 0)->orderBy('name')->get();
    }
}
