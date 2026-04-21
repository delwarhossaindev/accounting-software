<?php

namespace App\Repositories;

use App\Models\TaxRate;
use App\Repositories\Contracts\TaxRateRepositoryInterface;

class TaxRateRepository extends BaseRepository implements TaxRateRepositoryInterface
{
    public function __construct(TaxRate $model)
    {
        parent::__construct($model);
    }

    public function active()
    {
        return $this->query()->where('is_active', true)->orderBy('name')->get();
    }
}
