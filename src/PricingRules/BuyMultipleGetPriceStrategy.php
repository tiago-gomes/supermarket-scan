<?php

namespace Tiagogomes\Supermarket\PricingRules;


use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\Item;

class BuyMultipleGetPriceStrategy implements RuleStrategyInterface
{
    const SKU = ["A", "E"];

    const PRICE = 3.0;

    private array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    private function setItems(array $items) {
        $this->items = $items;
    }

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

        // refresh the items
        $this->setItems($items);

        // get sku[] from items
        $itemSkus = array_map(fn($itm) => $itm->getSku(), $items);

        // merge itemSkus with current item sku
        $mergedSkus = array_unique(array_merge($itemSkus, [$item->getSku()]));

        // intersect if sku[] exist in mergedSkus[]
        $combinedSkus = array_intersect($sku, $mergedSkus);
        if ($combinedSkus != $sku) {
            return $item;
        }

        $newPrice = $price / count($sku);

        // update sku[] items with new data
        $items = $this->updateSkuItems(
            $items,
            $newPrice,
            $sku,
            $params
        );

        // update items
        $this->setItems($items);

        $item = new Item(
            $item->getSku(),
            $item->getName(),
            $newPrice,
            $item->getQuantity(),
            $params,
        );

        return $item;
    }

    protected function updateSkuItems(
        array $items,
        float $newPrice,
        array $sku,
        array $params
    ) : array
    {
        foreach($items as $key => $itm) {
            if (
                in_array($itm->getSku(), $sku)
            ) {
                $items[$key] = new Item(
                    $itm->getSku(),
                    $itm->getName(),
                    $newPrice,
                    $itm->getQuantity(),
                    $params,
                );
            }
        }

        return $items;
    }
}
