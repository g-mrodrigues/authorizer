<?php

namespace Tests\Unit\Drivers;

use App\Drivers\Database\DatabaseInterface;
use App\Drivers\Database\InMemDatabase;
use PHPUnit\Framework\TestCase;

class InMemDatabaseTest extends TestCase
{
    const INDEX_IDENTIFIER = 'test';

    public function test_shouldGetSameInstance()
    {
        $instance = $this->getDatabaseInstance();
        $instance2 = $this->getDatabaseInstance();

        self::assertEquals($instance, $instance2);
    }

    public function test_shouldThrowExceptionOnTryToSerialize()
    {
        self::expectExceptionMessage('Cannot unserialize singleton');
        $this->getDatabaseInstance()->__wakeup();
    }

    public function test_shouldThrowExceptionOnTryToClone()
    {
        self::expectExceptionMessage('Cannot clone singleton');
        clone $this->getDatabaseInstance();
    }

    public function test_shouldInsertSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $response = $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());

        self::assertEquals($response, $this->getFakeContent());
    }

    public function test_shouldSelectSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $expected = $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());
        $response = $instance->select(self::INDEX_IDENTIFIER);

        self::assertEquals($expected, $response);
    }

    public function test_shouldUpdateSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $content = $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());
        $content['a'] = 3;
        $instance->update(self::INDEX_IDENTIFIER, $content);
        $response = $instance->select(self::INDEX_IDENTIFIER);

        self::assertEquals($content, $response);
    }

    public function test_shouldDeleteSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());
        $instance->delete(self::INDEX_IDENTIFIER);

        self::assertNull($instance->select(self::INDEX_IDENTIFIER));
    }

    private function getFakeContent(): array
    {
        return ['a' => 1, 'b' => 2];
    }

    private function getDatabaseInstance(): DatabaseInterface
    {
        return InMemDatabase::getInstance();
    }
}