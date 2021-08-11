<?php

namespace Tests\Unit\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Enum\AccountViolationsEnum;
use App\UseCase\CreateAccount;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\AccountTestHelperTrait;

class CreateAccountTest extends TestCase
{
    use AccountTestHelperTrait;

    public function test_shouldCreateAccountSuccessfully()
    {
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturnNull();
        $mock->expects('save')->once()->withAnyArgs()->andReturnArg(0);
        $sut = new CreateAccount($mock);

        $account = $this->createAccount();
        self::assertEmpty($sut->execute($account)->getViolations());
    }

    public function test_shouldAddViolationOnTryingToCreateNewAccount()
    {
        $mock = $this->getAccountRepositoryMock();
        $mock->expects('getAccount')->once()->andReturn($this->createAccount());
        $sut = new CreateAccount($mock);
        $account = $this->createAccount();
        $account = $sut->execute($account);

        self::assertNotEmpty($account->getViolations());
        self::assertEquals([AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED], $account->getViolations());
    }

    private function getAccountRepositoryMock(): MockInterface
    {
        return \Mockery::mock(AccountRepositoryInterface::class)->makePartial();
    }
}
