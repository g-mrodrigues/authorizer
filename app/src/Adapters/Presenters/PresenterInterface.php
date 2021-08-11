<?php

namespace App\Adapters\Presenters;

interface PresenterInterface
{
    public function stdout(array $accounts): string;
}