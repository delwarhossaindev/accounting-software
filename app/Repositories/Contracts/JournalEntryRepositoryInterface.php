<?php

namespace App\Repositories\Contracts;

interface JournalEntryRepositoryInterface extends BaseRepositoryInterface
{
    public function forSource(string $sourceType, int $sourceId);
}
