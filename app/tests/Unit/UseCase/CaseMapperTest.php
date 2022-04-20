<?php

namespace Tests\Unit\UseCase;

use App\Entities\Account;
use App\UseCase\AssignTransactionToAccount;
use App\UseCase\CaseMapper;
use App\UseCase\CreateAccount;
use App\UseCase\CreateDenyList;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Aux\Helpers\AccountTestHelperTrait;
use Tests\Aux\Helpers\DenyListHelperTrait;
use Tests\Aux\Helpers\TransactionTestHelperTrait;

class CaseMapperTest extends TestCase
{
    use TransactionTestHelperTrait,
        AccountTestHelperTrait,
        DenyListHelperTrait;

    public function test_shouldMapAccountCorrectly()
    {
        $account = json_decode($this->getAccountInputExample());
        $createAccountMock = $this->getCreateAccountMock();
        $createAccountMock->expects('execute')->withAnyArgs()->once()->andReturnArg(0);
        $assignTransactionToAccountMock = $this->getAssignTransactionToAccountMock();
        $createDenyListMock = $this->getCreateDenyListMock();

        $sut = new CaseMapper($createAccountMock, $assignTransactionToAccountMock, $createDenyListMock);
        self::assertInstanceOf(Account::class, $sut->map($account));
    }

    public function test_shouldMapTransactionCorrectly()
    {
        $transaction = json_decode($this->getTransactionInputExample());
        $createAccountMock = $this->getCreateAccountMock();
        $assignTransactionToAccountMock = $this->getAssignTransactionToAccountMock();
        $createDenyListMock = $this->getCreateDenyListMock();
        $account = $this->createAccount();
        $assignTransactionToAccountMock->expects('execute')->withAnyArgs()->once()->andReturn($account);

        $sut = new CaseMapper($createAccountMock, $assignTransactionToAccountMock, $createDenyListMock);
        self::assertEquals($account, $sut->map($transaction));
    }

    public function test_shouldMapDenyListCorrectly()
    {
        $denyList = json_decode($this->getMerchantDenyInputExample());
        $createAccountMock = $this->getCreateAccountMock();
        $assignTransactionToAccountMock = $this->getAssignTransactionToAccountMock();
        $createDenyListMock = $this->getCreateDenyListMock();
        $account = $this->createAccount()->addDenyList($this->createDenyListWithMerchants(['merchant-A','merchant-B']));
        $createDenyListMock->expects('execute')->withAnyArgs()->once()->andReturn($account);

        $sut = new CaseMapper($createAccountMock, $assignTransactionToAccountMock, $createDenyListMock);
        self::assertEquals($account, $sut->map($denyList));
    }

    private function getCreateAccountMock(): MockInterface
    {
        return \Mockery::mock(CreateAccount::class)->makePartial();
    }

    private function getAssignTransactionToAccountMock(): MockInterface
    {
        return \Mockery::mock(AssignTransactionToAccount::class)->makePartial();
    }

    private function getCreateDenyListMock(): MockInterface
    {
        return \Mockery::mock(CreateDenyList::class)->makePartial();
    }
}
