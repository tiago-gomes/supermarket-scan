<?php

require 'vendor/autoload.php';

use Tiagogomes\Supermarket\Checkout;

$checkout = new Checkout();

// scan product 
$checkout->scan("A", 3);

// get calculations
echo "The total price is: " . $checkout->getTotal();
