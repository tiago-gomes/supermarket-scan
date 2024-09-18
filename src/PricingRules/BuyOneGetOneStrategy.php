<?php

namespace Tiagogomes\Supermarket\PricingRules;


use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\Item;

class BuyOneGetOneStrategy implements RuleStrategyInterface
{
    public function apply(Item $item = null, array $items = null, array $params = []): Item
    {

        if (empty($item) || empty($params)) {
            return $item;
        }

        $sku = $params['sku'] ?? null;
        $freeQuantity = $params['free_quantity'] ?? 0;

        if ($item->getSku() != $sku) {
            return $item;
        }

        if ($freeQuantity <= 0) {
            return $item;
        }

        $newQuantity = $item->getQuantity() + $params['free_quantity'];

        return new Item(
            $item->getSku(),
            $item->getName(),
            $item->getPrice(),
            $newQuantity,
            $params,
        );
    }
}
