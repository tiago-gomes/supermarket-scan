<?php

namespace Tiagogomes\Supermarket\PricingRules;


use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\Item;

class BuyMultipleGetPriceStrategy implements RuleStrategyInterface
{
    const SKU = ["A", "E"];

    const PRICE = 3.0;

    public function apply(Item $item = null, array $items = null, array $params = []): Item
    {

        if (empty($item) || empty($params) || empty($items)) {
            return $item;
        }
        
        $sku = $params['sku'] ?? self::SKU;
        $price = $params['price'] ?? self::PRICE;

        if ($price<=0) {
            return $item;
        }

        if (!is_array($sku)) {
            return $item;
        }

        // current item sku must be in sku[]
        if (!in_array($item->getSku(), $sku)) {
            return $item;
        }

        $newPrice = $price / count($sku);

        return new Item(
            $item->getSku(),
            $item->getName(),
            $newPrice,
            $item->getQuantity(),
            $params,
        );
    }
}
