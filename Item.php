<?php 

class Item
{
    private string $sku;

    private string $name;

    private int $quantity;

    public function __construct(
        string  $sku,
        string $name,
        int $quantity
    ) 
    {
       $this->sku = $sku;
       $this->name = $name ;
       $this->quantity = $quantity;
    }
}