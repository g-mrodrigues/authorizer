<?php

namespace App\Tests\Unit\Entities;

use App\Entities\Account;
use App\Entities\Enum\AccountViolationsEnum;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function test_shouldCreateAccountInstance()
    {
        $account = new Account(rand(10, 1000), rand(0, 1));
        self::assertInstanceOf(Account::class, $account);
    }

    public function test_shouldReturnFalseOnCheckIfHasAvailableLimit()
    {
        $limit = 150;
        $account = new Account($limit, true);

        self::assertFalse($account->checkIfHasAvailableLimit($limit + 1));
    }

    public function test_shouldReturnTrueOnCheckIfHasAvailableLimit()
    {
        $limit = 150;
        $account = new Account($limit, true);

        self::assertTrue($account->checkIfHasAvailableLimit($limit));
    }

    public function test_shouldAddViolationsWhenCardIsNotActive()
    {
        $limit = rand(10, 100);
        $account = new Account($limit, false);
        $account->debit($limit - 1);

        self::assertEquals([AccountViolationsEnum::CARD_NOT_ACTIVE], $account->getViolations());
    }

    public function test_shouldAddViolationsWhenCardIsNotActiveAndThereIsNoLimitAvailable()
    {
        $limit = rand(10, 100);
        $account = new Account($limit, false);
        $account->debit($limit + 1);

        self::assertEquals([
            AccountViolationsEnum::CARD_NOT_ACTIVE,
            AccountViolationsEnum::INSUFFICIENT_LIMIT
        ], $account->getViolations());
    }


    public function test_shouldDebitFromAvailableLimit()
    {
        $limit = 150;
        $account = new Account($limit, true);
        $account->debit($limit);

        self::assertEquals(0, $account->getAvailableLimit());
    }
}