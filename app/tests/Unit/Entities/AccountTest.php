<?php

namespace Tests\Unit\Entities;

use App\Entities\Account;
use App\Entities\Enum\AccountViolationsEnum;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\Aux\Helpers\AccountTestHelperTrait;
use Tests\Aux\Helpers\TransactionTestHelperTrait;

class AccountTest extends TestCase
{
    use AccountTestHelperTrait,
        TransactionTestHelperTrait;

    public function test_shouldCreateAccountInstance()
    {
        $account = $this->createAccount();
        self::assertInstanceOf(Account::class, $account);
    }

    public function test_shouldAddViolationsCorrectly()
    {
        $account = $this->createAccount();
        $account->addViolation(AccountViolationsEnum::CARD_NOT_ACTIVE);

        self::assertNotEmpty($account->getViolations());
    }

    public function test_shouldAddViolationWhenCardIsNotActive()
    {
        $account = $this->createAccount(false);
        $account->isActiveCard();

        self::assertEquals([AccountViolationsEnum::CARD_NOT_ACTIVE], $account->getViolations());
    }

    public function test_shouldAddViolationWhenInsufficientLimit()
    {
        $account = $this->createAccount();
        $account->isSufficientLimit($account->getAvailableLimit() + 1);

        self::assertEquals([AccountViolationsEnum::INSUFFICIENT_LIMIT], $account->getViolations());
    }

    public function test_shouldNotAddTransactionWhenAccountHasViolations()
    {
        $account = $this->createAccount(false);
        $transaction = $this->createTransaction();
        $account->isActiveCard();
        $account->addTransaction($transaction);

        self::assertNotEmpty($account->getViolations());
    }

    public function test_shouldAddTransactionWhenAccountHasNoViolations()
    {
        $account = $this->createAccount(true);
        $accountAvailableLimit = $account->getAvailableLimit();
        $transaction = $this->createTransaction($accountAvailableLimit - 10);
        $account->isActiveCard();
        $account->addTransaction($transaction);

        self::assertEmpty($account->getViolations());
        self::assertNotEmpty($account->getTransactions());
        self::assertEquals(10, $account->getAvailableLimit());
    }

    public function test_shouldAddViolationWhenIsHighFrequencySmallInterval()
    {
        $account = $this->createAccount(true);
        $timeInterval = Carbon::now()->subMinute();
        $transaction = $this->createTransaction(null, $timeInterval->format(DATE_ATOM));
        $account->addTransaction($transaction)
            ->addTransaction($transaction)
            ->addTransaction($transaction)
            ->isHighFrequencySmallInterval($timeInterval);

        self::assertTrue($account->hasViolations());
        self::assertEquals([AccountViolationsEnum::HIGH_FREQUENCY_SMALL_INTERVAL], $account->getViolations());
        self::assertCount(3, $account->getTransactions());
    }

    public function test_shouldNotAddViolationWhenIsNoHighFrequencySmallInterval()
    {
        $account = $this->createAccount(true);
        $timeInterval = Carbon::now()->subMinute();
        $transaction = $this->createTransaction(null, $timeInterval->format(DATE_ATOM));
        $account->addTransaction($transaction)
            ->addTransaction($transaction)
            ->isHighFrequencySmallInterval($timeInterval);

        self::assertFalse($account->hasViolations());
        self::assertCount(2, $account->getTransactions());
    }

    public function test_shouldAddViolationWhenTransactionIsDoubled()
    {
        $account = $this->createAccount(true);
        $transaction = $this->createTransaction();
        $account->addTransaction($transaction)
            ->isDoubleTransaction($transaction);

        self::assertTrue($account->hasViolations());
        self::assertEquals([AccountViolationsEnum::DOUBLED_TRANSACTIONS], $account->getViolations());
    }

    public function test_shouldNotAddViolationWhenTransactionIsNotDouble()
    {
        $account = $this->createAccount(true);
        $transactionOne = $this->createTransaction();
        $transactionTwo = $this->createTransaction();
        $account->addTransaction($transactionOne)
            ->isDoubleTransaction($transactionTwo);

        self::assertFalse($account->hasViolations());
    }
}
