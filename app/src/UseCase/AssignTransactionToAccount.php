<?php

namespace App\UseCase;

use App\Adapters\Repositories\AccountRepositoryInterface;
use App\Entities\Enum\AccountViolationsEnum;
use App\Entities\{Account, Entity};

class AssignTransactionToAccount implements UseCaseInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository
    )
    {
    }

    public function execute(Entity $transaction): Account
    {
        $account = $this->accountRepository->getAccount();

        if (!$account) {
            return (new Account(0, false))
                ->addViolation(AccountViolationsEnum::ACCOUNT_NOT_INITIALIZED);
        }

        return $account->processTransaction($transaction);
    }
}
