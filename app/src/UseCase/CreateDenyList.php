<?php

namespace App\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Account;
use App\Entities\Entity;
use App\Entities\Enum\AccountViolationsEnum;

class CreateDenyList implements UseCaseInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    )
    {

    }

    public function execute(Entity $entity): Account
    {
        $account = $this->accountRepository->getAccount();

        if (!$account) {
            return (new Account(0, false))
                ->addViolation(AccountViolationsEnum::ACCOUNT_NOT_INITIALIZED);
        }

        $this->accountRepository->save($account->addDenyList($entity)->toSave());
        return $account;
    }
}