<?php

namespace Tiagogomes\Supermarket;

use Tiagogomes\Supermarket\Contract\CalculateRulesInterface;
use Tiagogomes\Supermarket\PricingRules\Contract\RuleStrategyInterface;
use Tiagogomes\Supermarket\PricingRules\BuyOneGetOneStrategy;
use Tiagogomes\Supermarket\PricingRules\BuyThreeGetOneStrategy;

class PricingRules implements CalculateRulesInterface
{
    private array $items = [];

    private array $rules = [];

    public function __construct(array $items)
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
                "class" => BuyOneGetOneStrategy::class,
                "params" => [
                    "sku" => "A",
                    "description" => "Buy one item A, get another one free.",
                    "free_quantity" => 1,
                ]
            ],
            [
                "class" => BuyThreeGetOneStrategy::class,
                "params" => [
                    "sku" => "B",
                    "description" => "Buy 3 item B, get another one free.",
                    "free_quantity" => 1,
                    "required_quantity" => 3,
                ]
            ],

        ];
    }

    public function apply(
        Item $item
    ): Item
    {
       foreach($this->rules as $rule) {
            if (
                isset($rule['params']['sku']) and 
                $rule['params']['sku'] == $item->getSku()
            ) {

                $strategyClass = $rule['class'];

                if (!class_exists($strategyClass)) {
                    throw new \Exception("Strategy class {$strategyClass} does not exist.");
                }

                $strategy = new $strategyClass();

                if (!$strategy instanceof RuleStrategyInterface) {
                    throw new \Exception("Class {$strategyClass} must implement RuleStrategyInterface.");
                }

                $item = $strategy->apply($item, $rule['params']);
                break;
            }
       }

       return $item;
    }
}
