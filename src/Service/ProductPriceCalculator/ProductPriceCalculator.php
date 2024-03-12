<?php

declare(strict_types=1);

namespace App\Service\ProductPriceCalculator;

use App\Model\PurchaseProduct\PurchaseProductContext;

class ProductPriceCalculator
{
    public function getPrice(PurchaseProductContext $context): float
    {
        $result = $context->product->getPrice();

        if ($context->coupon?->getPercentageDiscountAmount()) {
            $result -= $result * $context->coupon->getPercentageDiscountAmount() / 100;
        }

        if ($context->coupon?->getFixedDiscountAmount()) {
            $result -= $context->coupon->getFixedDiscountAmount();
        }

        if ($context->taxSystem->getAmount() > 0) {
            $result += $result * $context->taxSystem->getAmount() / 100;
        }

        return $result;
    }
}
