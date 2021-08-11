<?php

namespace App\UseCase;

use App\Entities\Account;
use App\Entities\Entity;

interface UseCaseInterface
{
    public function execute(Entity $entity): Account;
}
