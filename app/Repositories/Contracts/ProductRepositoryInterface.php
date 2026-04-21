<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function active();

    public function lowStock();

    public function outOfStock();
}
