<?php

namespace App\Adapters\Presenters;

use App\Entities\Account;

class AccountStdout implements StdoutInterface
{
    public function format(Account $account): string
    {
        return json_encode([
            'account' => $account->toArray(),
            'violations' => $account->getViolations()
        ]);
    }
}
