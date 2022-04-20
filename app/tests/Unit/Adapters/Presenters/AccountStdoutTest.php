<?php

namespace Tests\Unit\Adapters\Presenters;

use App\Adapters\Presenters\AccountStdout;
use Tests\Aux\Helpers\AccountTestHelperTrait;
use Tests\Feature\TestCase;

class AccountStdoutTest extends TestCase
{
    use AccountTestHelperTrait;

    public function test_shouldReturnCorrectStdout()
    {
        $account = $this->createAccount();
        $response = (new AccountStdout())->format($account);
        $parsedResponse = json_decode($response, true);

        self::assertIsString($response);
        self::assertEquals($account->toArray(), $parsedResponse['account']);
    }
}