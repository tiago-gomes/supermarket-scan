<?php

use PHPUnit\Framework\TestCase;
use Tiagogomes\Supermarket\Checkout;
use Tiagogomes\Supermarket\Item;
use Tiagogomes\Supermarket\PricingRules;

class CheckoutTest extends TestCase
{
    private function getPrivateProperty($object, $property)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    public function testScanValidItem()
    {
        $checkout = new Checkout();
        
        // Assuming PricingRules is properly mocked or instantiated
        $checkout->scan('A', 2);

        $items = $this->getPrivateProperty($checkout, 'items');
        $this->assertCount(1, $items);

        $item = $items[0];
        $this->assertEquals('A', $item->getSku());
        $this->assertEquals(2, $item->getQuantity());
    }

    public function testScanInvalidSKU()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("SKU not found in products");

        $checkout = new Checkout();
        $checkout->scan('Z', 2);
    }

    public function testScanZeroQuantity()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Quantity must be greater than 0");

        $checkout = new Checkout();
        $checkout->scan('A', 0);
    }

    public function testScanNegativeQuantity()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Quantity must be greater than 0");

        $checkout = new Checkout();
        $checkout->scan('A', -1);
    }

    public function testScanBuyNgetNQuantity()
    {
        $checkout = new Checkout();
        $checkout->scan('B', 1);

        $items = $this->getPrivateProperty($checkout, 'items');
        $this->assertEquals(2, $items[0]->getQuantity());
    }

    public function testScanBuyMultipleGetPrice()
    {
        $checkout = new Checkout();

        $checkout->scan('A', 1);
        $checkout->scan('B', 1);
        $checkout->scan('C', 1);
        $checkout->scan('D', 1);
        $checkout->scan('E', 1);

        $items = $this->getPrivateProperty($checkout, 'items');

        $this->assertEquals(1.5, $items[3]->getPrice());
        $this->assertEquals(1.5, $items[4]->getPrice());
    }
}
