<?php

namespace App\Drivers\Database;

interface DatabaseInterface
{
    public function insert(string $index, $content);
    public function select(string $index, int $id);
    public function update(string $index, int $id, $content);
    public function delete(string $index, int $id);
}