Supermarket
-------------------------------------

## Usage:
$checkout = new Checkout();

$checkout->scan("A", 3);

$checkout->scan("B", 3);

$checkout->scan("C", 3);

echo "The total price is: " . $checkout->getTotal();

## What you need to know
This code is not perfect neither it was meant to be, I did it for fun.

