<?php

require 'vendor/autoload.php';

use Tiagogomes\Supermarket\Item;
use Tiagogomes\Supermarket\PricingRules;

$item = new Item(
    "A",
    "A",
    12,
    1
);

$items = [
    $item,
];

$pricingRule = new PricingRules($items);

$pricingRule->loadRules();

$item = $pricingRule->apply($item);
