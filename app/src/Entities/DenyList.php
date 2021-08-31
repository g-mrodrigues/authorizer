<?php

namespace App\Entities;

class DenyList extends Entity
{
    protected array $merchants;

    public function __construct()
    {
        $this->merchants = [];
    }

    public function getMerchants(): array
    {
        return $this->merchants;
    }

    public function setMerchants(array $merchants): self
    {
        $this->merchants = $merchants;
        return $this;
    }
}