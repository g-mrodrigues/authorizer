<?php

namespace App\Entities;

use Carbon\Carbon;

class Transaction extends Entity
{
    private Carbon $time;

    public function __construct(
        private string $merchant,
        private int $amount,
        string $time,
    )
    {
        $this->time = $this->getDateTime($time);
    }

    private function getDateTime(string $time): Carbon
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

    public function getTime(): Carbon
    {
        return $this->time;
    }

    public function isDoubledTransaction(Transaction $transaction): bool
    {
        return $this->getAmount() === $transaction->getAmount() &&
            $this->getMerchant() === $transaction->getMerchant() &&
            $this->getTime()->gte($transaction->getTime()->subMinutes(2));
    }
}
