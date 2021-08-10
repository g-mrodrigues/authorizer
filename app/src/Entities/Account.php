<?php

namespace App\Entities;

use App\Entities\Enum\AccountViolationsEnum;
use App\Entities\Traits\JsonSerializableModel;

class Account
{
    use JsonSerializableModel;

    private array $violations;

    private int $availableLimit;

    private bool $activeCard;

    public function __construct(
        int $availableLimit,
        bool $activeCard,
    )
    {
        $this->availableLimit = $availableLimit;
        $this->activeCard = $activeCard;
        $this->violations = [];
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

    public function getAvailableLimit(): int
    {
        return $this->availableLimit;
    }

    public function isActiveCard(): bool
    {
        return $this->activeCard;
    }

    public function checkIfHasAvailableLimit(int $limit): bool
    {
        if ($this->availableLimit < $limit) {
            return false;
        }

        return true;
    }

    public function debit(int $value): void
    {
        if (!$this->activeCard) {
            $this->addViolation(AccountViolationsEnum::CARD_NOT_ACTIVE);
        }

        if (!$this->checkIfHasAvailableLimit($value)) {
            $this->addViolation(AccountViolationsEnum::INSUFFICIENT_LIMIT);
            return;
        }

        $this->availableLimit -= $value;
    }
}
