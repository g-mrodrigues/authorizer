<?php

namespace App\UseCase;

use \stdClass;
use \UnexpectedValueException;
use App\Entities\Account;
use App\Entities\Entity;
use App\Entities\Transaction;

final class OperationFactory
{
    const ACCOUNT_ATTRIBUTE = 'account';
    const TRANSACTION_ATTRIBUTE = 'transaction';

    public static function factory(stdClass $operation): Entity
    {
        if (isset($operation->{self::ACCOUNT_ATTRIBUTE}) && $account = $operation->{self::ACCOUNT_ATTRIBUTE}) {
            return new Account($account->{'available-limit'}, $account->{'active-card'});
        }

        if (isset($operation->{self::TRANSACTION_ATTRIBUTE}) &&
            $transaction = $operation->{self::TRANSACTION_ATTRIBUTE}) {
            return new Transaction($transaction->{'merchant'}, $transaction->{'amount'}, $transaction->{'time'});
        }

        throw new UnexpectedValueException('Operation not found');
    }
}
