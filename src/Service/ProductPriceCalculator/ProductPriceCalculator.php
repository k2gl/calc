<?php

declare(strict_types=1);

namespace App\Service\ProductPriceCalculator;

class ProductPriceCalculator
{
    public function getPrice(ProductPriceCalculatorContext $context): float
    {
        $result = $context->productPrice;

        if ($context->percentageDiscount > 0) {
            $result -= $result * $context->percentageDiscount / 100;
        }

        if ($context->fixedDiscount > 0) {
            $result -= $context->fixedDiscount;
        }

        if ($context->taxSize > 0) {
            $result += $result * $context->taxSize / 100;
        }

        return $result;
    }
}
