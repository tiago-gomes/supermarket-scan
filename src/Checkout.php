<?php 

namespace Tiagogomes\Supermarket;

use Tiagogomes\Supermarket\Item;
use Tiagogomes\Supermarket\PricingRules;
use Exception;

class Checkout 
{
    private Item $item;

    private array $items = [];

    private array $products = [];

    private PricingRules $pricingRule;

    public function __construct()
    {
        $this->loadProducts();
        $this->pricingRule = new PricingRules($this->getItems());
    }

    public function scan(
        string $sku,
        int $quantity,
    ) : void
    {
        // check quantity greater then zero
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }

        // check if sku is a valid product
        $products = array_map(fn($item) => $item['sku'], $this->getProducts());
        if (!in_array($sku, $products)) {
            throw new Exception("SKU not found in products");
        }

        $price = $this->getPriceBySku($sku);

        // Apply rule in item
        $item = $this->pricingRule->apply(
            new Item(
                $sku,
                $sku,
                $price,
                $quantity,
            )
        );

        $this->setItems($this->pricingRule->getItems());
    }

    protected function getPriceBySku(string $sku): ?float
    {
        // Search through the products array
        foreach ($this->products as $product) {
            if ($product['sku'] === $sku) {
                return $product['price'];
            }
        }

        // Return null if SKU is not found
        return null;
    }

    private function loadProducts() : void
    {
        $this->products = [
            [
                "sku" => "A",
                "price" => 50,
            ],
            [
                "sku" => "B",
                "price" => 75,
            ],
            [
                "sku" => "C",
                "price" => 75,
            ],
            [
                "sku" => "D",
                "price" => 25,
            ],
            [
                "sku" => "E",
                "price" => 150,
            ],
            [
                "sku" => "F",
                "price" => 200,
            ],
        ];
    }

    private function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    private function setItems(array $items): void
    {
        $this->items = $items;
    }

    private function getItems(): array
    {
        return $this->items;
    }

    private function getProducts(): array
    {
        return $this->products;
    }
}