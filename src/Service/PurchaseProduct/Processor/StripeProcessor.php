<?php

declare(strict_types=1);

namespace App\Service\PurchaseProduct\Processor;

use App\Model\PurchaseProduct\PurchaseProductContext;
use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use App\Service\PurchaseProduct\Exception\PurchaseProductException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

use function dd;

#[AsTaggedItem(index: 'handler_one', priority: 10)]
class StripeProcessor implements PurchaseProductProcessor
{
    private readonly StripePaymentProcessor $processor;

    public function __construct(
        private readonly ProductPriceCalculator $productPriceCalculator
    ) {
        $this->processor = new StripePaymentProcessor();
    }

    public function process(PurchaseProductContext $context): void
    {
        $toPay = $this->productPriceCalculator->getPrice($context);

        if (!$this->processor->processPayment($toPay)) {
            throw new PurchaseProductException('Payment failed. Total to pay less than minimal.');
        }
    }
}
