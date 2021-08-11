<?php

namespace App\Entities\Enum;

class AccountViolationsEnum
{
    const ACCOUNT_NOT_INITIALIZED = 'account-not-initialized';
    const ACCOUNT_ALREADY_INITIALIZED = 'account-already-initialized';
    const CARD_NOT_ACTIVE = 'card-not-active';
    const INSUFFICIENT_LIMIT = 'insufficient-limit';
    const HIGH_FREQUENCY_SMALL_INTERVAL = 'high-frequency-small-interval';
    const DOUBLE_TRANSACTIONS = 'doubled-transactions';

    public static function all(): array
    {
        return [
            self::ACCOUNT_ALREADY_INITIALIZED,
            self::CARD_NOT_ACTIVE,
            self::INSUFFICIENT_LIMIT,
            self::HIGH_FREQUENCY_SMALL_INTERVAL,
            self::DOUBLE_TRANSACTIONS
        ];
    }
}