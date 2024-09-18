<?php

namespace Tiagogomes\Supermarket\Contract;

use Tiagogomes\Supermarket\Item;

interface CalculateRulesInterface
{
    public function loadRules(): void;

    public function apply(Item $item, string $promoCode = null): Item;
}
