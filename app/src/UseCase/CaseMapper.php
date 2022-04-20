<?php

namespace App\UseCase;

use App\Entities\{Account, DenyList, Transaction};
use \stdClass;

class CaseMapper
{
    public function __construct(
        private CreateAccount $createAccount,
        private AssignTransactionToAccount $assignTransactionToAccount,
        private CreateDenyList $createDenyList
    )
    {
    }

    public function map(?stdClass $operation): Account|null
    {
        if (!$operation) {
            return null;
        }

        $instance = OperationFactory::factory($operation);
        return match (get_class($instance)) {
            Account::class => $this->createAccount->execute($instance),
            Transaction::class => $this->assignTransactionToAccount->execute($instance),
            DenyList::class => $this->createDenyList->execute($instance)
        };
    }
}
