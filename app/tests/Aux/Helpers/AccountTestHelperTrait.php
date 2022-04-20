<?php

namespace Tests\Aux\Helpers;

use App\Entities\Account;

trait AccountTestHelperTrait
{
    public function createAccount(?bool $isActive = null, ?int $amount = null): Account
    {
        return new Account(
            $amount ?? rand(10, 1000),
            $isActive ?? rand(0, 1)
        );
    }

    public function getAccountInputExample(): string
    {
        return '{"account": {"active-card": true, "available-limit": 100}}';
    }
}