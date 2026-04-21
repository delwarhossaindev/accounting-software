<?php

namespace App\Repositories\Contracts;

interface CreditDebitNoteRepositoryInterface extends BaseRepositoryInterface
{
    public function byType(string $type);
}
