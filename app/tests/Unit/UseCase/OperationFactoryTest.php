<?php

namespace Tests\Unit\UseCase;

use App\Entities\Account;
use App\Entities\Transaction;
use App\UseCase\OperationFactory;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\AccountTestHelperTrait;
use Tests\Unit\Helpers\TransactionTestHelperTrait;
use \stdClass;
use UnexpectedValueException;

class OperationFactoryTest extends TestCase
{
    use TransactionTestHelperTrait,
        AccountTestHelperTrait;

    public function test_shouldReturnCorrectInstanceWhenAccountInputIsGiven()
    {
        $input = $this->getAccountInputExample();
        self::assertInstanceOf(Account::class, OperationFactory::factory(json_decode($input)));
    }

    public function test_shouldReturnCorrectInstanceWhenTransactionInputIsGiven()
    {
        $input = $this->getTransactionInputExample();
        self::assertInstanceOf(Transaction::class, OperationFactory::factory(json_decode($input)));
    }

    public function test_shouldThrowExceptionWhenNotMappedInputIsGiven()
    {
        $input = new stdClass();
        self::expectException(UnexpectedValueException::class);
        OperationFactory::factory($input);
    }
}
