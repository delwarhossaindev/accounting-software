<?php

namespace App\Repositories\Contracts;

use App\Models\CompanySetting;

interface CompanySettingRepositoryInterface
{
    public function current(): CompanySetting;

    public function update(array $data): CompanySetting;
}
