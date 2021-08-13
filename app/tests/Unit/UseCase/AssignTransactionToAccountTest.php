<?php

namespace Tests\Unit\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Enum\AccountViolationsEnum;
use App\UseCase\AssignTransactionToAccount;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\TransactionTestHelperTrait;
use Tests\Unit\Helpers\AccountTestHelperTrait;

class AssignTransactionToAccountTest extends TestCase
{
    use AccountTestHelperTrait,
        TransactionTestHelperTrait;

    public function test_shouldAssignTransactionToAccountSuccessfully()
    {
        $account = $this->createAccount(true, 100);
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturn($account);
        $mock->expects('save')->once()->andReturnArg(0);
        $transaction = $this->createTransaction(50);

        $sut = new AssignTransactionToAccount($mock);
        $account = $sut->execute($transaction);

        self::assertEquals(50, $account->getAvailableLimit());
        self::assertEmpty($account->getViolations());
    }

    public function test_shouldAddViolationWhenAccountIsNotInitialized()
    {
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturnNull();
        $transaction = $this->createTransaction(50);

        $sut = new AssignTransactionToAccount($mock);
        $account = $sut->execute($transaction);

        self::assertTrue($account->hasViolations());
        self::assertEquals([AccountViolationsEnum::ACCOUNT_NOT_INITIALIZED], $account->getViolations());
    }

    private function getAccountRepositoryMock(): MockInterface
    {
        return \Mockery::mock(AccountRepositoryInterface::class)->makePartial();
    }
}
