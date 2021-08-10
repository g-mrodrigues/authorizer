<?php

namespace App\UseCase;

use App\Entities\Account;

interface AuthorizeServiceInterface
{
    function createAccount(bool $activeCard, int $availableLimit): Account;
}