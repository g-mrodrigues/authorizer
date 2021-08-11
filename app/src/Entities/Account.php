<?php

namespace App\Entities;

use App\Entities\Enum\AccountViolationsEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use \Datetime;

class Account extends Entity
{
    private array $violations;

    private Collection $transactions;

    public function __construct(
        private int $availableLimit,
        private bool $activeCard,
    )
    {
        $this->violations = [];
        $this->transactions = collect();
    }

    public function addViolation(string $violations): self
    {
        $this->violations[] = $violations;
        return $this;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    public function hasViolations(): bool
    {
        return $this->violations != [];
    }

    public function eraseViolations(): self
    {
        $this->violations = [];
        return $this;
    }

    public function getAvailableLimit(): int
    {
        return $this->availableLimit;
    }

    public function getTransactions(): array
    {
        return $this->transactions->toArray();
    }

    public function isActiveCard(): self
    {
        return $this->activeCard ? $this :
            $this->addViolation(AccountViolationsEnum::CARD_NOT_ACTIVE);
    }

    public function isSufficientLimit(int $amount): self
    {
        return $this->availableLimit >= $amount ? $this :
            $this->addViolation(AccountViolationsEnum::INSUFFICIENT_LIMIT);
    }

    public function addTransaction(Transaction $transaction): self
    {
        if ($this->hasViolations()) {
            return $this;
        }

        $this->availableLimit -= $transaction->getAmount();
        $this->transactions->add($transaction);
        return $this;
    }

    public function isHighFrequencySmallInterval(Datetime $time): self
    {
        $count = $this->transactions->count();
        $timeInterval = (new Carbon($time))->subMinutes(2);

        return $this->transactions->slice($count - 3, $count)
            ->filter(function (Transaction $transaction) use ($timeInterval) {
                return $transaction->getTime()->isAfter($timeInterval);
            })->count() > 2 ? $this->addViolation(AccountViolationsEnum::HIGH_FREQUENCY_SMALL_INTERVAL) :
            $this;
    }

    public function isDoubleTransaction(Transaction $transaction): self
    {
        return $this->transactions->filter(function (Transaction $item) use ($transaction) {
            return $item->isDoubledTransaction($transaction);
        })->count() !== 0 ? $this->addViolation(AccountViolationsEnum::DOUBLE_TRANSACTIONS) :
            $this;
    }

    public function toArray(): array
    {
        return [
            'active-card' => $this->activeCard,
            'available-limit' => $this->availableLimit,
        ];
    }

    public function processTransaction(Transaction $transaction): self
    {
        return $this->eraseViolations()
            ->isActiveCard()
            ->isSufficientLimit($transaction->getAmount())
            ->isHighFrequencySmallInterval($transaction->getTime())
            ->isDoubleTransaction($transaction)
            ->addTransaction($transaction);
    }
}
