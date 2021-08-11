<?php

namespace App\Drivers\Database;

class InMemDatabase extends Singleton implements DatabaseInterface
{
    protected array $storage = [];

    protected int $increment = 1;

    public function insert(string $index, $content): int
    {
        $this->storage[$index][$this->increment] = $content;
        return $this->increment++;
    }

    public function select(string $index, int|null $id = null)
    {
        if (!isset($this->storage[$index])) {
            return null;
        }

        if (is_null($id)) {
            return $this->storage[$index];
        }

        return $this->storage[$index][$id];
    }

    public function update(string $index, int $id, $content)
    {
        $this->storage[$index][$id] = $content;
    }

    public function delete(string $index, int $id)
    {
        unset($this->storage[$index][$id]);
    }

    public function getIncrement(): int
    {
        return $this->increment;
    }
}