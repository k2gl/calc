<?php

declare(strict_types=1);

namespace App\Service\PurchaseProduct\Processor;

use App\Model\PurchaseProduct\PurchaseProductContext;
use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use App\Service\PurchaseProduct\Exception\PurchaseProductException;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalProcessor implements PurchaseProductProcessor
{
    private readonly PaypalPaymentProcessor $processor;

    public function __construct(
        private readonly ProductPriceCalculator $productPriceCalculator
    ) {
        $this->processor = new PaypalPaymentProcessor();
    }

    public function process(PurchaseProductContext $context): void
    {
        $toPay = (int) $this->productPriceCalculator->getPrice($context) * 100;

        try {
            $this->processor->pay($toPay);
        } catch (Exception) {
            throw new PurchaseProductException('Payment failed. Total pay more than the maximum.');
        }
    }
}
