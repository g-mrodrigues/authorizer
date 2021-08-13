<?php

namespace Tests\Unit\Adapters\Presenters;

use App\Adapters\Presenters\AccountStdout;
use App\Adapters\Presenters\AuthorizePresenter;
use App\Adapters\Presenters\StdoutInterface;
use App\Entities\Enum\AccountViolationsEnum;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\AccountTestHelperTrait;

class AuthorizePresenterTest extends TestCase
{
    use AccountTestHelperTrait;

    public function test_shouldReturnFormattedStdout()
    {
        $mock = \Mockery::mock(StdoutInterface::class)->makePartial();
        $mock->shouldReceive('format')->withAnyArgs()->andReturnUsing(function ($input) {
            return json_encode($input);
        });
        $presenter = new AuthorizePresenter($mock);
        $account = $this->createAccount();
        $response = $presenter->stdout([$account]);

        self::assertIsString($response);
    }
}