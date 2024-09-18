<?php

use PHPUnit\Framework\TestCase;

use Tiagogomes\Supermarket\PricingRules\BuyOneGetOneStrategy;
use Tiagogomes\Supermarket\Item;

class BuyOneGetOneStrategyTest extends TestCase
{
    public function testApplyAddsFreeItem()
    {
        $item = new Item('A', 'Test Item A', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 1];

        $strategy = new BuyOneGetOneStrategy();
        $result = $strategy->apply($item, $params);

        $this->assertEquals(2, $result->getQuantity());
    }

    public function testApplyReturnsOriginalItemForDifferentSku()
    {
        $item = new Item('B', 'Test Item B', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 1];

        $strategy = new BuyOneGetOneStrategy();
        $result = $strategy->apply($item, $params);

        $this->assertEquals(1, $result->getQuantity());
    }

    public function testApplyReturnsOriginalItemIfNoFreeQuantity()
    {
        $item = new Item('A', 'Test Item A', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 0];

        $strategy = new BuyOneGetOneStrategy();
        $result = $strategy->apply($item, $params);

        $this->assertEquals(1, $result->getQuantity());
    }
}
