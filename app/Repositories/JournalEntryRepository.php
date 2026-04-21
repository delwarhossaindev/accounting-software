<?php

namespace App\Repositories;

use App\Models\JournalEntry;
use App\Repositories\Contracts\JournalEntryRepositoryInterface;

class JournalEntryRepository extends BaseRepository implements JournalEntryRepositoryInterface
{
    public function __construct(JournalEntry $model)
    {
        parent::__construct($model);
    }

    public function forSource(string $sourceType, int $sourceId)
    {
        return $this->query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->get();
    }
}
