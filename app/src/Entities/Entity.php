<?php

namespace App\Entities;

abstract class Entity
{
    public function toSave(): self
    {
        return $this;
    }
}