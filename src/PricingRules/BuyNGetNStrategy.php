<?php

namespace Tiagogomes\Supermarket\PricingRules;


use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\Item;

class BuyNGetNStrategy implements RuleStrategyInterface
{
    const SKU = "A";

    const FREE_QUANTITY = 1;

    const REQUIRED_QUANTITY = 1;

    public function apply(Item $item = null, array $items = null, array $params = []): Item
    {

        if (empty($item) and empty($params)) {
            return $item;
        }

        $sku = $params['sku'] ?? self::SKU;
        $freeQuantity = $params['free_quantity'] ?? self::FREE_QUANTITY;
        $requiredQuantity = $params['required_quantity'] ?? self::FREE_QUANTITY;

        if ($sku != $item->getSku()) {
            return $item;
        }

        if ($item->getQuantity() == $requiredQuantity) {
            $newQuantity = $item->getQuantity() + $freeQuantity;
            return new Item(
                $item->getSku(),
                $item->getName(),
                $item->getPrice(),
                $newQuantity,
                $params,
            );
        }

        return $item;
    }
}
