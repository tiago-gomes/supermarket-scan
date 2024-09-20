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
                "price" => 25,
            ],
            [
                "sku" => "D",
                "price" => 150,
            ],
            [
                "sku" => "E",
                "price" => 200,
            ],
        ];
    }

    public function getTotal() {

        $total = 0.0;

        foreach ($this->items as $item) {

            $price = $item->getPrice();
            $quantity = $item->getQuantity();

            // Check if there are changes applied to the item
            $changes = $item->getChanges();
            if (!empty($changes) && is_string($changes['sku'])) {
                $quantity -= $changes['free_quantity'];
            }

            $total += $price * $quantity;
        }

        return $total;
    }

    private function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function getProducts(): array
    {
        return $this->products;
    }
}