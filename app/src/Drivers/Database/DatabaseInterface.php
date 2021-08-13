<?php

namespace App\Drivers\Database;

interface DatabaseInterface
{
    public function insert(string $index, $content);
    public function select(string $index);
    public function update(string $index, $content);
    public function delete(string $index);
}