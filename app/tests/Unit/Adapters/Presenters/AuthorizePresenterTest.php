<?php

namespace Tests\Unit\Adapters\Presenters;

use App\Adapters\Presenters\AuthorizePresenter;
use App\Entities\Enum\AccountViolationsEnum;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\AccountTestHelperTrait;

class AuthorizePresenterTest extends TestCase
{
    use AccountTestHelperTrait;

    public function test_shouldReturnFormattedStdout()
    {
        $presenter = new AuthorizePresenter();
        $account = $this->getFakesAccounts(5);
        $response = $presenter->stdout($account);

        self::assertIsString($response);
    }

    private function getFakesAccounts(int $qty = 2): array
    {
        $accounts = [];
        for ($i = 0; $i < $qty; $i++) {
            $account = $this->createAccount();

            if (rand(0,1)) {
                $account->addViolation(Arr::random(AccountViolationsEnum::all()));
            }

            $accounts[] = $account;
        }

        return $accounts;
    }
}