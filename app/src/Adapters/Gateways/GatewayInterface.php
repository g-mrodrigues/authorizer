<?php

namespace App\Adapters\Gateways;

interface GatewayInterface
{
    public function process(string $input);
}