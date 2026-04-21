<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface InvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function byType(string $type, array $with = []): Collection;

    public function unpaid();

    public function pastDue(string $date);
}
