<?php

declare(strict_types=1);

namespace AppTests\App\Controller\PurchaseProduct;

use App\Reference\PaymentSystem;
use AppTests\Core\Factory\CouponFactory;
use AppTests\Core\Factory\ProductFactory;
use AppTests\Core\Factory\TaxSystemFactory;
use AppTests\Core\TestCase\ApiControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

use function K2gl\PHPUnitFluentAssertions\fact;

/** @covers \App\Controller\PurchaseProduct\PurchaseProductController::__invoke */
class PurchaseProductControllerTest extends ApiControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ProductFactory::truncate();
        TaxSystemFactory::truncate();
        CouponFactory::truncate();
    }

    public function testStripeWhenTotalCostLessThanMinimal(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 50]);

        $product = ProductFactory::createOne(['price' => 10]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/purchase',
            json:   [
                        'product' => $product->getId(),
                        'taxNumber' => 'ZX8F9',
                        'paymentProcessor' => PaymentSystem::STRIPE->value,
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        fact($this->getResponse()->getContent())->is('"Payment failed. Total to pay less than minimal."');
    }

    public function testStripeWhenTotalCostMoreThanMinimal(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 50]);

        $product = ProductFactory::createOne(['price' => 100]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/purchase',
            json:   [
                        'product' => $product->getId(),
                        'taxNumber' => 'ZX8F9',
                        'paymentProcessor' => PaymentSystem::STRIPE->value,
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        fact($this->getResponse()->getContent())->is('"Purchased"');
    }

    public function testPaypalWhenTotalCostMoreThanMaximum(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 50]);

        $product = ProductFactory::createOne(['price' => 1000]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/purchase',
            json:   [
                        'product' => $product->getId(),
                        'taxNumber' => 'ZX8F9',
                        'paymentProcessor' => PaymentSystem::PAYPAL->value,
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        fact($this->getResponse()->getContent())->is('"Payment failed. Total pay more than the maximum."');
    }

    public function testPaypalWhenTotalCostLessThanMaximum(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 50]);

        $product = ProductFactory::createOne(['price' => 100]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/purchase',
            json:   [
                        'product' => $product->getId(),
                        'taxNumber' => 'ZX8F9',
                        'paymentProcessor' => PaymentSystem::PAYPAL->value,
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        fact($this->getResponse()->getContent())->is('"Purchased"');
    }
}
