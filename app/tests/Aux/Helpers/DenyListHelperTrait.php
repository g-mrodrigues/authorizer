<?php

namespace Tests\Aux\Helpers;

use App\Entities\DenyList;

trait DenyListHelperTrait
{
    public function createDenyListWithMerchants(array $merchants): DenyList
    {
        return (new DenyList())->setMerchants($merchants);
    }

    public function getMerchantDenyInputExample(): string
    {
        return '{"deny-list": ["merchant-A", "merchant-B"]}';
    }
}
