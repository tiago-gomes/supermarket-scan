<?php

namespace Tiagogomes\Supermarket\PricingRules\Contract;

use Tiagogomes\Supermarket\Item;

interface RuleStrategyInterface
{
    public function apply(Item $item = null, array $items = null, array $params = []): Item;

    public function getItems(): array;
}