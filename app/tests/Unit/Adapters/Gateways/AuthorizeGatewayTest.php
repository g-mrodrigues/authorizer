<?php

namespace Tests\Unit\Adapters\Gateways;

use App\Adapters\Gateways\AuthorizeGateway;
use App\Adapters\Presenters\PresenterInterface;
use App\UseCase\CaseMapper;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Aux\Helpers\AccountTestHelperTrait;
use Tests\Aux\Helpers\TransactionTestHelperTrait;

class AuthorizeGatewayTest extends TestCase
{
    use AccountTestHelperTrait,
        TransactionTestHelperTrait;

    public function test_shouldParseDataThroughProcess()
    {
        $account = $this->getAccountInputExample();
        $account .= PHP_EOL . $this->getTransactionInputExample();

        $presenterMock = $this->getPresenterMock();
        $caseMapper = $this->getCaseMapperMock();

        $sut = new AuthorizeGateway($presenterMock, $caseMapper);
        $response = $sut->process($account);
        self::assertEquals(2, $response);
    }

    public function test_shouldReturnEmptyStringWhenEmptyInputIsGiven()
    {
        $account = $this->getAccountInputExample();
        $account .= PHP_EOL . $account;

        $presenterMock = $this->getPresenterMock();
        $caseMapper = $this->getCaseMapperMock();

        $sut = new AuthorizeGateway($presenterMock, $caseMapper);
        $response = $sut->process($account);
        self::assertEquals(2, $response);
    }

    private function getPresenterMock(): MockInterface
    {
        $mock = \Mockery::spy(PresenterInterface::class)->makePartial();
        $mock->shouldReceive('stdout')->withAnyArgs()->once()->andReturnUsing(function ($items) {
            return (string) count($items);
        });
        return $mock;
    }

    private function getCaseMapperMock(): MockInterface
    {
        $mock = \Mockery::spy(CaseMapper::class)->makePartial();
        $mock->expects('map')->withAnyArgs()->andReturn($this->createAccount());
        return $mock;
    }
}