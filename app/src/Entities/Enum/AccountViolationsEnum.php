<?php

namespace App\Entities\Enum;

class AccountViolationsEnum
{
    const ACCOUNT_ALREADY_INITIALIZED = 'account-already-initialized';
    const CARD_NOT_ACTIVE = 'card-not-active';
    const INSUFFICIENT_LIMIT = 'insufficient-limit';
    const HIGH_FREQUENCY_SMALL_INTERVAL = 'high-frequency-small-interval';
    const DOUBLE_TRANSACTIONS = 'double-transactions';
}