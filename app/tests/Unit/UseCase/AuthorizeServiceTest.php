<?php

namespace App\Tests\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Account;
use App\Entities\Enum\AccountViolationsEnum;
use App\UseCase\AuthorizeService;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AuthorizeServiceTest extends TestCase
{
    public function test_shouldCreateAccountSuccessfully()
    {
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturnNull();
        $mock->expects('save')->once()->withAnyArgs()->andReturnArg(0);
        $sut = new AuthorizeService($mock);

        $limit = rand(0, 100);
        $account = $sut->createAccount(true, $limit);
        self::assertInstanceOf(Account::class, $account);
        self::assertEquals($limit, $account->getAvailableLimit());
    }

    public function test_shouldAddViolationOnTryingToCreateNewAccount()
    {
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturn(new Account(1, true));
        $sut = new AuthorizeService($mock);

        $limit = rand(0, 100);
        $account = $sut->createAccount(true, $limit);
        self::assertInstanceOf(Account::class, $account);
        self::assertNotEmpty($account->getViolations());
        self::assertEquals([AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED], $account->getViolations());
    }

    private function getAccountRepositoryMock(): MockInterface
    {
        return \Mockery::mock(AccountRepositoryInterface::class)->makePartial();
    }
}