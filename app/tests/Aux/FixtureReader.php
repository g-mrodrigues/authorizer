<?php

namespace Tests\Aux;

class FixtureReader
{
    const FIXTURE_PATH = __DIR__ . '/Fixtures/';
    const OPERATIONS_TYPE = 'Operations';

    public function read(string $type, string $file): false|string
    {
        return file_get_contents(self::FIXTURE_PATH . $type . '/' . $file);
    }
}
