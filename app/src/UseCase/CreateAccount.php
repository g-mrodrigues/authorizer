<?php

namespace App\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Enum\AccountViolationsEnum;
use App\Entities\{Account, Entity};

class CreateAccount implements UseCaseInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    )
    {
    }

    public function execute(Entity $account): Account
    {
        if ($this->accountRepository->getAccount()) {
            return $account->addViolation(AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED);
        }

        $this->accountRepository->save($account->toSave());
        return $account;
    }
}
