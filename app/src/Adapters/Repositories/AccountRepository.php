<?php


namespace App\Adapters\Repositories;

use App\Drivers\Database\DatabaseInterface;
use App\Entities\Account;

class AccountRepository implements AccountRepositoryInterface
{
    const ACCOUNT_INDEX = 'account';

    private DatabaseInterface $database;

    private int $accountId = 0;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function save(Account $account)
    {
        $this->accountId = $this->database->insert(self::ACCOUNT_INDEX, $account);
    }

    public function getAccount(): Account|null
    {
        if ($this->accountId == 0) {
            return null;
        }

        return $this->database->select(self::ACCOUNT_INDEX, $this->accountId);
    }
}