<?php

namespace App\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Account;
use App\Entities\Enum\AccountViolationsEnum;

class AuthorizeService implements AuthorizeServiceInterface
{
    private AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    function createAccount(bool $activeCard, int $availableLimit): Account
    {
        $account = new Account($availableLimit, $activeCard);

        if ($this->accountRepository->getAccount()) {
            $account->addViolation(AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED);
            return $account;
        }

        return $this->accountRepository->save($account);
    }
}