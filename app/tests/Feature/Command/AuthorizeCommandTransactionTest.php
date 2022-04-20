<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Aux\FixtureReader;
use Tests\Feature\TestCase;

class AuthorizeCommandTransactionTest extends TestCase
{
    protected CommandTester $command;
    private FixtureReader $fixtureReader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapApplication();
        $this->command = $this->getCommandTester(AuthorizeCommand::getDefaultName());
        $this->fixtureReader = new FixtureReader();
    }

    public function test_shouldReturnAccountOutputWithCorrectAvailableLimitAndNoViolations()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader
                    ->read(FixtureReader::OPERATIONS_TYPE, 'transactions')
            ]
        );

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(120, $output[2]->account->{'available-limit'});
        $this->assertEmpty($output[1]->violations);
        $this->assertEmpty($output[2]->violations);
    }

    public function test_shouldReturnWithInsufficientLimitViolation()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader
                    ->read(FixtureReader::OPERATIONS_TYPE, 'transaction_with_insufficient_limit')
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(100, $output[1]->account->{'available-limit'});
        $this->assertEquals([AccountViolationsEnum::INSUFFICIENT_LIMIT], $output[1]->violations);
    }

    public function test_shouldReturnWithCardNotActiveViolation()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader->read(FixtureReader::OPERATIONS_TYPE, 'card_not_active')
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(false, $output[1]->account->{'active-card'});
        $this->assertEquals([AccountViolationsEnum::CARD_NOT_ACTIVE], $output[1]->violations);
    }

    public function test_shouldReturnWithHighFrequencySmallIntervalViolation()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader
                    ->read(FixtureReader::OPERATIONS_TYPE, 'high_frequency_interval')
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(390, $output[4]->account->{'available-limit'});
        $this->assertEquals([AccountViolationsEnum::HIGH_FREQUENCY_SMALL_INTERVAL], $output[4]->violations);
    }

    public function test_shouldReturnWithIsDoubledTransactionViolation()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader
                    ->read(FixtureReader::OPERATIONS_TYPE, 'doubled_transaction')
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals([AccountViolationsEnum::DOUBLED_TRANSACTIONS], $output[2]->violations);
        $this->assertEmpty($output[3]->violations);
        $this->assertEquals(0, $output[3]->account->{'available-limit'});
    }

    public function test_shouldReturnWithMultipleViolations()
    {
        $this->command->execute(
            [
                'operations' => $this->fixtureReader
                    ->read(FixtureReader::OPERATIONS_TYPE, 'multiple_violations')
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(
            [AccountViolationsEnum::CARD_NOT_ACTIVE, AccountViolationsEnum::INSUFFICIENT_LIMIT],
            $output[1]->violations);
        $this->assertEquals(100, $output[1]->account->{'available-limit'});
    }
}
