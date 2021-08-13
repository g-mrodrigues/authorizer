<?php

namespace App\Adapters\Presenters;

use App\Entities\Entity;

class AccountStdout implements StdoutInterface
{
    public function format(Entity $account): string
    {
        return json_encode([
            'account' => $account->toArray(),
            'violations' => $account->getViolations()
        ]);
    }
}