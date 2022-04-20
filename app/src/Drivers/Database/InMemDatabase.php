<?php

namespace App\Drivers\Database;

class InMemDatabase extends Singleton implements DatabaseInterface
{
    protected array $storage = [];

    public function insert(string $index, $content)
    {
        $this->storage[$index] = $content;
        return $content;
    }

    public function select(string $index)
    {
        if (!isset($this->storage[$index])) {
            return null;
        }

        return $this->storage[$index];
    }

    public function update(string $index, $content)
    {
        $this->storage[$index] = $content;
        return $content;
    }

    public function delete(string $index)
    {
        unset($this->storage[$index]);
    }
}
