<?php

namespace App\Repositories\Contracts;

interface BranchRepositoryInterface extends BaseRepositoryInterface
{
    public function activeOrdered();

    public function headOffice();
}
