<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Aux\FixtureReader;
use Tests\Feature\TestCase;

class AuthorizeCommandDenyListTest extends TestCase
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

    public function test_shouldReturnWithDenyListViolation()
    {
        $this->command->execute([
            'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'deny_list')
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertNotEmpty($output[2]->violations);
        $this->assertEquals(AccountViolationsEnum::MERCHANT_DENIED, $output[2]->violations[0]);
    }

    public function test_shouldReturnWithoutViolationWhenThereIsNoMerchantDenied()
    {
        $this->command->execute([
            'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'transactions')
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEmpty($output[1]->violations);
        $this->assertEquals(200, $output[1]->account->{'available-limit'});
    }

    public function test_shouldReplaceDenyList()
    {
        $this->command->execute([
            'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'replace_deny_list')
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertNotEmpty($output[2]->violations);
        $this->assertEquals(AccountViolationsEnum::MERCHANT_DENIED, $output[2]->violations[0]);
        $this->assertEmpty($output[4]->violations);
    }
}