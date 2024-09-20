<?php

namespace Tiagogomes\Supermarket;

use Tiagogomes\Supermarket\Contract\CalculateRulesInterface;
use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\PricingRules\BuyNGetNStrategy;
use Tiagogomes\Supermarket\PricingRules\BuyMultipleGetPriceStrategy;

class PricingRules implements CalculateRulesInterface
{
    private array $items = [];

    private array $rules = [];

    public function __construct(array $items)
    {
        $this->updateItems($items);
        $this->loadRules();
    }

    private function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    private function updateItems(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function loadRules(): void
    {
        $this->rules = [
            [
                "class" => BuyNGetNStrategy::class,
                "params" => [
                    "sku" => "B",
                    "description" => "Buy one item B, get another one free.",
                    "free_quantity" => 1,
                    "required_quantity" => 1,
                ]
            ],
            [
                "class" => BuyNGetNStrategy::class,
                "params" => [
                    "sku" => "C",
                    "description" => "Buy 3 item B, get another one free.",
                    "free_quantity" => 1,
                    "required_quantity" => 3,
                ]
            ],
            [
                "class" => BuyMultipleGetPriceStrategy::class,
                "params" => [
                    "sku" => ["D","E"],
                    "description" => "Buy D and E for 3 euros",
                    "required_quantity" => 1,
                    "price" => 3.0,
                ]
            ],
        ];
    }

    public function apply(
        Item $item
    ): Item
    {
       foreach($this->rules as $rule) {

            // if sku is not available we can not continue.
            if (!isset($rule['params']['sku'])) {
                break;
            }

            // depending on the value of sku: string or array
            // we run the correct class.
            if (
                $rule['params']['sku'] == $item->getSku() ||
                is_array($rule['params']['sku']) &&
                in_array($item->getSku(), $rule['params']['sku']) 
            ) {

                $strategyClass = $rule['class'];

                if (!class_exists($strategyClass)) {
                    throw new \Exception("Strategy class {$strategyClass} does not exist.");
                }

                $strategy = new $strategyClass();

                if (!$strategy instanceof RuleStrategyInterface) {
                    throw new \Exception("Class {$strategyClass} must implement RuleStrategyInterface.");
                }

                $item = $strategy->apply($item, $this->getItems(), $rule['params']);
                
                break;
            }
       }

       // mostly used when we have multiple items to update
        if (
            isset($strategy) &&
            !empty($strategy->getItems())
        ) {
            $this->updateItems($strategy->getItems());
        }

        $this->addItem($item);

        return $item;
    }
}
