<?php

declare(strict_types=1);

namespace App\Service\PurchaseProduct;

use App\Model\PurchaseProduct\PurchaseProductContext;
use App\Reference\PaymentSystem;
use App\Service\PurchaseProduct\Exception\PurchaseProductException;
use App\Service\PurchaseProduct\Processor\PaypalProcessor;
use App\Service\PurchaseProduct\Processor\PurchaseProductProcessor;
use App\Service\PurchaseProduct\Processor\StripeProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

readonly class PurchaseProductService
{
    public function __construct(
        #[AutowireLocator([
            PaymentSystem::PAYPAL->value => PaypalProcessor::class,
            PaymentSystem::STRIPE->value => StripeProcessor::class,
        ])]
        private ContainerInterface $processor,
    ) {
    }

    /** @throws PurchaseProductException */
    public function process(PaymentSystem $paymentSystem, PurchaseProductContext $context): void
    {
        try {
            /** @var PurchaseProductProcessor $processor */
            $processor = $this->processor->get($paymentSystem->value);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            throw new PurchaseProductException($e->getMessage());
        }

        $processor->process($context);
    }
}
