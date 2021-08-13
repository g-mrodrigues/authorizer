<?php

namespace App\Adapters\Gateways;

use App\Adapters\Presenters\PresenterInterface;
use App\UseCase\CaseMapper;

class AuthorizeGateway implements GatewayInterface
{
    public function __construct(
        private PresenterInterface $presenter,
        private CaseMapper $caseMapper
    )
    {
    }

    public function process(string $input): string
    {
        $response = [];
        foreach (explode(PHP_EOL, $input) as $content) {
            $result = $this->caseMapper->map(json_decode($content));
            array_push($response, $result);
        }

        return $this->presenter->stdout($response);
    }
}
