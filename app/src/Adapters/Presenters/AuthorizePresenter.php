<?php

namespace App\Adapters\Presenters;

use App\Entities\Account;

class AuthorizePresenter implements PresenterInterface
{
    private StdoutInterface $stdout;

    public function __construct(StdoutInterface $stdout)
    {
        $this->stdout = $stdout;
    }

    public function stdout(array $accounts): string
    {
        $response = "";
        foreach ($accounts as $account) {
            if ($account instanceof Account) {
                $response .= $this->stdout->format($account) . PHP_EOL;
            }
        }

        return $response;
    }
}
