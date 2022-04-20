<?php

namespace App\Adapters\Presenters;

use App\Entities\Account;

interface StdoutInterface
{
    public function format(Account $account): string;
}
