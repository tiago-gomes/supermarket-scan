<?php

interface CalculateRulesInterface
{
    public function loadRules(): void;

    public function apply(Item $item, array $items = [], string $promoCode = null): void;
}
