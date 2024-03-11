<?php

declare(strict_types=1);

namespace App\Service\ProductPriceCalculator;

final readonly class ProductPriceCalculatorContext
{
    public function __construct(
        public float $productPrice,
        public float $taxSize = 0,
        public float $fixedDiscount = 0,
        public float $percentageDiscount = 0,
    ) {
    }
}
