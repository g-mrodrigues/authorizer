<?php

namespace App\Entities;

use Carbon\Carbon;
use \Datetime;
use App\Entities\Traits\JsonSerializableModel;

class Transaction
{
    use JsonSerializableModel;

    private string $merchant;

    private int $amount;

    private DateTime $time;

    public function __construct(
        string $merchant,
        int $amount,
        string $time,
    )
    {
        $this->merchant = $merchant;
        $this->amount = $amount;
        $this->time = $this->getDateTime($time);
    }

    private function getDateTime(string $time): DateTime
    {
        return Carbon::createFromTimeString($time);
    }

    public function getMerchant(): string
    {
        return $this->merchant;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTime(): Datetime
    {
        return $this->time;
    }
}