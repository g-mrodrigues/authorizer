<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Aux\FixtureReader;
use Tests\Feature\TestCase;

class AuthorizeCommandAccountTest extends TestCase
{
    private CommandTester $command;
    private FixtureReader $fixtureReader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapApplication();
        $this->command = $this->getCommandTester(AuthorizeCommand::getDefaultName());
        $this->fixtureReader = new FixtureReader();
    }

    public function test_shouldReturnEmptyWhenArgumentIsEmpty()
    {
        $this->command->execute([
            'operations' => '',
        ]);

        $output = $this->command->getDisplay();
        $this->assertStringContainsString('', $output);
    }

    public function test_shouldInitializeAccountSuccessfully()
    {
        $this->command->execute([
            'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'initialize_account'),
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertTrue($output[0]->account->{"active-card"});
        $this->assertEquals(1000, $output[0]->account->{"available-limit"});
        $this->assertEmpty($output[0]->account->{"deny-list"});
        $this->assertEmpty($output[0]->violations);

    }

    public function test_shouldReturnViolationAccountAlreadyInitialized()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'account_already_initialized'),
            ]
        );

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEquals(AccountViolationsEnum::ACCOUNT_ALREADY_INITIALIZED, $output[1]->violations[0]);
    }

    public function test_shouldReturnViolationAccountNotInitialized()
    {
        $this->command->execute([
            'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'account_not_initialized'),
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEquals(AccountViolationsEnum::ACCOUNT_NOT_INITIALIZED, $output[0]->violations[0]);
    }
}
