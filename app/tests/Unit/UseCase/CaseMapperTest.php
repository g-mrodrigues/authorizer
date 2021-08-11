<?php

namespace Tests\Unit\UseCase;

use App\Entities\Account;
use App\UseCase\AssignTransactionToAccount;
use App\UseCase\CaseMapper;
use App\UseCase\CreateAccount;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\AccountTestHelperTrait;
use Tests\Unit\Helpers\TransactionTestHelperTrait;

class CaseMapperTest extends TestCase
{
    use TransactionTestHelperTrait,
        AccountTestHelperTrait;

    public function test_shouldMapAccountCorrectly()
    {
        $account = json_decode($this->getAccountInputExample());
        $createAccountMock = $this->getCreateAccountMock();
        $createAccountMock->expects('execute')->withAnyArgs()->once()->andReturnArg(0);
        $assignTransactionToAccountMock = $this->getAssignTransactionToAccountMock();

        $sut = new CaseMapper($createAccountMock, $assignTransactionToAccountMock);
        self::assertInstanceOf(Account::class, $sut->map($account));
    }

    public function test_shouldMapTransactionCorrectly()
    {
        $transaction = json_decode($this->getTransactionInputExample());
        $createAccountMock = $this->getCreateAccountMock();
        $assignTransactionToAccountMock = $this->getAssignTransactionToAccountMock();
        $account = $this->createAccount();
        $assignTransactionToAccountMock->expects('execute')->withAnyArgs()->once()->andReturn($account);

        $sut = new CaseMapper($createAccountMock, $assignTransactionToAccountMock);
        self::assertEquals($account, $sut->map($transaction));
    }

    private function getCreateAccountMock(): MockInterface
    {
        return \Mockery::mock(CreateAccount::class)->makePartial();
    }

    private function getAssignTransactionToAccountMock(): MockInterface
    {
        return \Mockery::mock(AssignTransactionToAccount::class)->makePartial();
    }
}