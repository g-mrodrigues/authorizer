<?php

namespace Tests\Aux\Helpers;

use App\Entities\Transaction;

trait TransactionTestHelperTrait
{
    public function createTransaction(
        ?int $amount = null,
        ?string $datetime = null,
        ?string $merchant = null
    ): Transaction
    {
        return new Transaction(
            $merchant ?? 'a',
            $amount ?? rand(10, 1000),
            $datetime ?? date(DATE_ATOM, mt_rand(1, time()))
        );
    }

    public function getTransactionInputExample(): string
    {
        return '{"transaction": {"merchant": "Burger King", "amount": 20, "time": "2019-02-13T11:00:00.000Z"}}';
    }
}