<?php

namespace App\Tests\Unit\Entities;

use PHPUnit\Framework\TestCase;
use App\Entities\Transaction;

class TransactionTest extends TestCase
{
    public function test_shouldCreateTransactionInstance()
    {
        $transaction = new Transaction('A', 1, '2019-02-13T11:00:00.000Z');
        self::assertInstanceOf(Transaction::class, $transaction);
    }

    public function test_shouldThrowErrorOnCreateTransactionInstanceWhenDatetimeIsWrong()
    {
        self::expectError();
        new Transaction('A', 1, '2019-');
    }
}
