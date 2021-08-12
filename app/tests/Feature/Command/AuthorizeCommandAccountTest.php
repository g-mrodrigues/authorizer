<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Feature\TestCase;

class AuthorizeCommandAccountTest extends TestCase
{
    private CommandTester $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapApplication();
        $this->command = $this->getCommandTester(AuthorizeCommand::getDefaultName());
    }

    public function test_shouldReturnEmptyWhenArgumentIsEmpty()
    {
        $this->command->execute([
            'operations' => '',
        ]);

        $output = $this->command->getDisplay();
        $this->assertStringContainsString('', $output);
    }

    public function test_shouldReturnAccountOutputSuccessfully()
    {
        $this->command->execute([
            'operations' => '{"account": {"active-card": true, "available-limit": 1000}}',
        ]);

        $output = $this->command->getDisplay();
        $this->assertEquals(
            '{"account":{"active-card":true,"available-limit":1000},"violations":[]}' . PHP_EOL . PHP_EOL,
            $output
        );
    }

    public function test_shouldReturnAccountOutputWithViolationAccountAlreadyInitialized()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                                {"account": {"active-card": true, "available-limit": 1000}}',
            ]
        );

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEquals(AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED, $output[1]->violations[0]);
    }

    public function test_shouldReturnAccountOutputWithViolationAccountNotInitialized()
    {
        $this->command->execute([
            'operations' => '{"transaction": {"merchant": "Uber", "amount": 80, "time": "2019-02-13T11:01:31.000Z"}}',
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEquals(AccountViolationsEnum::ACCOUNT_NOT_INITIALIZED, $output[0]->violations[0]);
    }
}