<?php

use PHPUnit\Framework\TestCase;

use Tiagogomes\Supermarket\PricingRules\BuyNGetNStrategy;
use Tiagogomes\Supermarket\Item;

class BuyNGetNStrategyTest extends TestCase
{
    public function testApplyBuy1Get1Free()
    {
        $item = new Item('A', 'Test Item A', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 1];

        $strategy = new BuyNGetNStrategy();
        $result = $strategy->apply($item, null, $params);

        $this->assertEquals(2, $result->getQuantity());
    }

    public function testApplyBuy3Get1Free()
    {
        $item = new Item('B', 'Test Item A', 10.00, 3);
        $params = ['sku' => 'B', 'free_quantity' => 1, 'required_quantity' => 3];

        $strategy = new BuyNGetNStrategy();
        $result = $strategy->apply($item, null, $params);

        $this->assertEquals(4, $result->getQuantity());
    }

    public function testApplyReturnsOriginalItemForDifferentSku()
    {
        $item = new Item('B', 'Test Item B', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 1];

        $strategy = new BuyNGetNStrategy();
        $result = $strategy->apply($item, null, $params);

        $this->assertEquals(1, $result->getQuantity());
    }

    public function testApplyReturnsOriginalItemIfNoFreeQuantity()
    {
        $item = new Item('C', 'Test Item A', 10.00, 1);
        $params = ['sku' => 'A', 'free_quantity' => 0];

        $strategy = new BuyNGetNStrategy();
        $result = $strategy->apply($item, null, $params);

        $this->assertEquals(1, $result->getQuantity());
    }
}
