<?php

namespace App\Adapters\Presenters;

use App\Entities\Entity;

interface StdoutInterface
{
    public function format(Entity $account): string;
}