<?php 

namespace Tiagogomes\Supermarket;

class Item
{
    private string $sku;
    private string $name;
    private float $price;
    private int $quantity;
    private array $changes = [];

    public function __construct(
        string $sku,
        string $name,
        float $price,
        int $quantity,
        array $changes = []
    ) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->changes = $changes;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    // Getter for name
    public function getName(): string
    {
        return $this->name;
    }

    // Getter for price
    public function getPrice(): string
    {
        return $this->price;
    }

    // Getter for quantity
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    // Getter for changes
    public function getChanges(): array
    {
        return $this->changes;
    }
}