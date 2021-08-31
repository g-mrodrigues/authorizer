<?php

namespace Tests\Feature\Command;

use App\Drivers\Commands\AuthorizeCommand;
use App\Entities\Enum\AccountViolationsEnum;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Feature\TestCase;

class AuthorizeCommandDenyListTest extends TestCase
{
    private CommandTester $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootstrapApplication();
        $this->command = $this->getCommandTester(AuthorizeCommand::getDefaultName());
    }

    public function test_shouldReturnAccountWithDenyListViolation()
    {
        $this->command->execute([
            'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                            {"deny-list": ["merchant-A", "merchant-B"]}
                            {"transaction": {"merchant": "merchant-A", "amount": 800, "time": "2019-02-13T11:01:01.000Z"}}'
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertNotEmpty($output[2]->violations);
        $this->assertEquals(AccountViolationsEnum::MERCHANT_DENIED, $output[2]->violations[0]);
    }

    public function test_shouldReturnAccountWithoutViolationWhenThereIsNoMerchantDenied()
    {
        $this->command->execute([
            'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                            {"transaction": {"merchant": "merchant-A", "amount": 800, "time": "2019-02-13T11:01:01.000Z"}}'
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertEmpty($output[1]->violations);
        $this->assertEquals(200, $output[1]->account->{'available-limit'});
    }

    public function test_shouldReplaceDenyList()
    {
        $this->command->execute([
            'operations' => '{"account": {"active-card": true, "available-limit": 1000}}
                            {"deny-list": ["merchant-A", "merchant-B"]}
                            {"transaction": {"merchant": "merchant-A", "amount": 100, "time": "2019-02-13T11:01:01.000Z"}}
                            {"deny-list": ["merchant-C"]}
                            {"transaction": {"merchant": "merchant-A", "amount": 100, "time": "2019-02-13T11:05:01.000Z"}}'
        ]);

        $output = $this->commandAuthorizeOutputToArray($this->command->getDisplay());
        $this->assertNotEmpty($output[2]->violations);
        $this->assertEquals(AccountViolationsEnum::MERCHANT_DENIED, $output[2]->violations[0]);
        $this->assertEmpty($output[4]->violations);
    }
}