<?php

namespace App\Adapters\Presenters;

use App\Entities\Account;

class AuthorizePresenter implements PresenterInterface
{
    public function stdout(array $accounts): string
    {
        $response = "";
        foreach ($accounts as $account) {
            if ($account instanceof Account) {
                $response .= $this->format($account) . PHP_EOL;
            }
        }

        return $response;
    }

    private function format(Account $account): string
    {
        return json_encode([
            'account' => $account->toArray(),
            'violations' => $account->getViolations()
        ]);
    }
}