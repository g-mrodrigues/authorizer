<?php

namespace App\Tests\Drivers;

use App\Drivers\Database\DatabaseInterface;
use App\Drivers\Database\InMemDatabase;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class InMemDatabaseTest extends TestCase
{
    const INDEX_IDENTIFIER = 'test';

    /**
     * @error
     */
    public function test_shouldThrowErrorOnTryingToCreateClassByConstructor()
    {
        self::expectError();
        new InMemDatabase();
    }

    public function test_shouldGetSameInstance()
    {
        $instance = $this->getDatabaseInstance();
        $instance2 = $this->getDatabaseInstance();

        self::assertEquals($instance, $instance2);
    }

    public function test_shouldInsertSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $response = $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());

        self::assertIsInt($response);
        self::assertEquals($response + 1, $instance->getIncrement());
    }

    public function test_shouldSelectSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $content = $this->getFakeContent();
        $id = $instance->insert(self::INDEX_IDENTIFIER, $content);
        $response = $instance->select(self::INDEX_IDENTIFIER, $id);

        self::assertEquals($content, $response);
    }

    public function test_shouldUpdateSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $content = $this->getFakeContent();
        $id = $instance->insert(self::INDEX_IDENTIFIER, $content);
        $content['a'] = 3;
        $instance->update(self::INDEX_IDENTIFIER, $id, $content);
        $response = $instance->select(self::INDEX_IDENTIFIER, $id);

        self::assertEquals($content, $response);
    }

    public function test_shouldDeleteSuccessfully()
    {
        $instance = $this->getDatabaseInstance();
        $id = $instance->insert(self::INDEX_IDENTIFIER, $this->getFakeContent());
        $instance->delete(self::INDEX_IDENTIFIER, $id);

        self::expectError();
        $instance->select(self::INDEX_IDENTIFIER, $id);
    }

    #[ArrayShape(['a' => "int", 'b' => "int"])]
    private function getFakeContent(): array
    {
        return ['a' => 1, 'b' => 2];
    }

    private function getDatabaseInstance(): DatabaseInterface
    {
        return InMemDatabase::getInstance();
    }
}