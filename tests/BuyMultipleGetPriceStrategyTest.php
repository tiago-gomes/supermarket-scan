<?php

use PHPUnit\Framework\TestCase;

use Tiagogomes\Supermarket\PricingRules\BuyMultipleGetPriceStrategy;
use Tiagogomes\Supermarket\Item;

class BuyMultipleGetPriceStrategyTest extends TestCase
{
    public function testApplyBuyMultipleGetPrice()
    {
        $item = new Item('E', 'Test Item A', 10.00, 1);
        $params = ['sku' => ["D","E"], 'price' => 4];

        $items = [
            new Item('A', 'Test Item A', 10.00, 1),
            new Item('D', 'Test Item D', 10.00, 1),
        ];

        $strategy = new BuyMultipleGetPriceStrategy();

        $result = $strategy->apply($item, $items, $params);

        $this->assertEquals(2, $result->getPrice());
    }

    public function testApplyNoneExistingMultipleCombination()
    {
        $item = new Item('E', 'Test Item A', 10.00, 1);
        $params = ['sku' => ["Z","F"], 'price' => 4];

        $items = [
            new Item('A', 'Test Item A', 10.00, 1),
            new Item('D', 'Test Item D', 10.00, 1),
        ];

        $strategy = new BuyMultipleGetPriceStrategy();

        $result = $strategy->apply($item, $items, $params);

        $this->assertEquals(10, $result->getPrice());
    }

    public function testApplyValidMultipleCombination()
    {
        $item = new Item('D', 'Test Item D', 15.00, 1);
        $params = ['sku' => ["C", "D"], 'price' => 5];

        $items = [
            new Item('B', 'Test Item B', 12.00, 1),
            new Item('C', 'Test Item C', 12.00, 1),
            new Item('D', 'Test Item D', 15.00, 1),
        ];

        $strategy = new BuyMultipleGetPriceStrategy();

        $result = $strategy->apply($item, $items, $params);

        // Price should be adjusted because 'C' and 'D' are both present
        $this->assertEquals(2.50, $result->getPrice());
    }

    public function testApplyPartialMatchMultipleCombination()
    {
        $item = new Item('D', 'Test Item D', 15.00, 1);
        $params = ['sku' => ["C", "D"], 'price' => 5];

        $items = [
            new Item('B', 'Test Item B', 12.00, 1),
            new Item('D', 'Test Item D', 15.00, 1), // 'C' is missing
        ];

        $strategy = new BuyMultipleGetPriceStrategy();

        $result = $strategy->apply($item, $items, $params);

        // Since 'C' is missing, the price should not change
        $this->assertEquals(15.00, $result->getPrice());
    }

    public function testApplyWithExtraSkusInItems()
    {
        $item = new Item('D', 'Test Item D', 15.00, 1);
        $params = ['sku' => ["C", "D"], 'price' => 5];

        $items = [
            new Item('A', 'Test Item A', 12.00, 1),
            new Item('B', 'Test Item B', 12.00, 1),
            new Item('C', 'Test Item C', 12.00, 1),
            new Item('D', 'Test Item D', 15.00, 1),
        ];

        $strategy = new BuyMultipleGetPriceStrategy();

        $result = $strategy->apply($item, $items, $params);

        // 'C' and 'D' are present, extra SKUs in items should not affect
        $this->assertEquals(2.50, $result->getPrice());
    }

}
