<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Feature\TestCase;

class AuthorizeCommandTransactionTest extends TestCase
{
    protected CommandTester $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapApplication();
        $this->command = $this->getCommandTester(AuthorizeCommand::getDefaultName());
    }

    public function test_shouldReturnAccountOutputWithCorrectAvailableLimitAndNoViolations()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                                {"transaction": {"merchant": "Nike", "amount": 800, "time": "2019-02-13T11:01:01.000Z"}}
                                {"transaction": {"merchant": "Uber", "amount": 80, "time": "2019-02-13T11:01:31.000Z"}}'
            ]
        );

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(120, $output[2]->account->{'available-limit'});
        $this->assertEmpty($output[1]->violations);
        $this->assertEmpty($output[2]->violations);
    }

    public function test_shouldReturnAccountOutputWithInsufficientLimitViolation()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": true, "available-limit": 100}}
                                {"transaction": {"merchant": "Nike", "amount": 800, "time": "2019-02-13T11:01:01.000Z"}}'
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(100, $output[1]->account->{'available-limit'});
        $this->assertEquals([AccountViolationsEnum::INSUFFICIENT_LIMIT], $output[1]->violations);
    }

    public function test_shouldReturnAccountOutputWithCardNotActiveViolation()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": false, "available-limit": 100}}
                            {"transaction": {"merchant": "Uber", "amount": 80, "time": "2019-02-13T11:01:01.000Z"}}'
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(false, $output[1]->account->{'active-card'});
        $this->assertEquals([AccountViolationsEnum::CARD_NOT_ACTIVE], $output[1]->violations);
    }

    public function test_shouldReturnAccountOutputWithHighFrequencySmallIntervalViolation()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                                {"transaction": {"merchant": "Nike", "amount": 500, "time": "2019-02-13T11:01:01.000Z"}}
                                {"transaction": {"merchant": "Uber", "amount": 80, "time": "2019-02-13T11:01:31.000Z"}}
                                {"transaction": {"merchant": "Uber", "amount": 30, "time": "2019-02-13T11:02:00.000Z"}}
                                {"transaction": {"merchant": "Uber", "amount": 55, "time": "2019-02-13T11:02:31.000Z"}}'
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(390, $output[4]->account->{'available-limit'});
        $this->assertEquals([AccountViolationsEnum::HIGH_FREQUENCY_SMALL_INTERVAL], $output[4]->violations);
    }

    public function test_shouldReturnAccountOutputWithIsDoubledTransactionViolation()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                                {"transaction": {"merchant": "Nike", "amount": 500, "time": "2019-02-13T11:01:01.000Z"}}
                                {"transaction": {"merchant": "Nike", "amount": 500, "time": "2019-02-13T11:02:01.000Z"}}
                                {"transaction": {"merchant": "Nike", "amount": 500, "time": "2019-02-13T11:03:02.000Z"}}'
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals([AccountViolationsEnum::DOUBLED_TRANSACTIONS], $output[2]->violations);
        $this->assertEmpty($output[3]->violations);
        $this->assertEquals(0, $output[3]->account->{'available-limit'});
    }

    public function test_shouldReturnAccountOutputWithMultipleViolations()
    {
        $this->command->execute(
            [
                'operations' => '{"account": {"active-card": false, "available-limit": 100}}
                                {"transaction": {"merchant": "Nike", "amount": 500, "time": "2019-02-13T11:01:01.000Z"}}'
            ]
        );
        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());

        $this->assertEquals(
            [AccountViolationsEnum::CARD_NOT_ACTIVE, AccountViolationsEnum::INSUFFICIENT_LIMIT],
            $output[1]->violations);
        $this->assertEquals(100, $output[1]->account->{'available-limit'});
    }
}