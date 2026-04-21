<?php

namespace App\Repositories;

use App\Models\CompanySetting;
use App\Repositories\Contracts\CompanySettingRepositoryInterface;

class CompanySettingRepository implements CompanySettingRepositoryInterface
{
    public function current(): CompanySetting
    {
        return CompanySetting::current();
    }

    public function update(array $data): CompanySetting
    {
        $settings = $this->current();
        $settings->update($data);
        CompanySetting::forgetCache();
        return $settings->refresh();
    }
}
