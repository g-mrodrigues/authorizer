<?php

namespace App\Adapters\Repositories;

use App\Entities\Account;

interface AccountRepositoryInterface
{
    public function save(Account $account);
    public function getAccount(): Account|null;
}