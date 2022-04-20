<?php

namespace Tests\Feature;

use App\Drivers\Database\InMemDatabase;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class TestCase extends KernelTestCase
{
    protected Application $application;

    protected function bootstrapApplication()
    {
        $kernel = static::createKernel();
        $this->application = new Application($kernel);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        InMemDatabase::reset();
    }

    protected function getCommandTester(string $commandName): CommandTester
    {
        $command = $this->application->find($commandName);
        return new CommandTester($command);
    }

    protected function commandAuthorizeOutputToArray(string $output): array
    {
        $response = [];
        foreach (explode(PHP_EOL, $output) as $item) {
            $response[] = json_decode($item);
        }

        return $response;
    }
}
