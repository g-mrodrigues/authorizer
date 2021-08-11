<?php

namespace App\Adapters\Repositories;

use App\Drivers\Database\{DatabaseInterface, InMemDatabase};
use App\Entities\Account;

class AccountRepository implements AccountRepositoryInterface
{
    const ACCOUNT_INDEX = 'account';

    private DatabaseInterface $database;

    public function __construct()
    {
        $this->database = InMemDatabase::getInstance();
    }

    public function save(Account $account): Account
    {
        $this->database->insert(self::ACCOUNT_INDEX, $account);
        return $account;
    }

    public function getAccount(): Account|null
    {
        if ($account = $this->database->select(self::ACCOUNT_INDEX)) {
            return array_shift($account);
        }

        return null;
    }
}
