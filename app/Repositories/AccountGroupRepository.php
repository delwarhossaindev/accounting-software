<?php

namespace App\Repositories;

use App\Models\AccountGroup;
use App\Repositories\Contracts\AccountGroupRepositoryInterface;

class AccountGroupRepository extends BaseRepository implements AccountGroupRepositoryInterface
{
    public function __construct(AccountGroup $model)
    {
        parent::__construct($model);
    }
}
