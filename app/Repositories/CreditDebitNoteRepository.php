<?php

namespace App\Repositories;

use App\Models\CreditDebitNote;
use App\Repositories\Contracts\CreditDebitNoteRepositoryInterface;

class CreditDebitNoteRepository extends BaseRepository implements CreditDebitNoteRepositoryInterface
{
    public function __construct(CreditDebitNote $model)
    {
        parent::__construct($model);
    }

    public function byType(string $type)
    {
        return $this->query()->where('type', $type)->latest('date')->get();
    }
}
