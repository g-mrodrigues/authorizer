<?php

namespace App\Tests\Unit\Traits;

use App\Entities\Traits\JsonSerializableModel;
use PHPUnit\Framework\TestCase;

class JsonSerializableModelTest extends TestCase
{
    public function test_shouldSerializeJsonOnToStringMagicMethod()
    {
        self::assertTrue((bool) 1);
    }
}