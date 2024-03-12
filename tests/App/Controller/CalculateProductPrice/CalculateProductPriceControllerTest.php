<?php

declare(strict_types=1);

namespace AppTests\App\Controller\CalculateProductPrice;

use App\Model\TaxNumber\Validator\AssertTaxNumber;
use App\Reference\CouponDiscountType;
use AppTests\Core\Factory\CouponFactory;
use AppTests\Core\Factory\ProductFactory;
use AppTests\Core\Factory\TaxSystemFactory;
use AppTests\Core\TestCase\ApiControllerTestCase;
use K2gl\Component\Validator\Constraint\EntityExist\AssertEntityExist;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotNull;

use function K2gl\PHPUnitFluentAssertions\fact;

/** @covers \App\Controller\CalculateProductPrice\CalculateProductPriceController::__invoke */
class CalculateProductPriceControllerTest extends ApiControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ProductFactory::truncate();
        TaxSystemFactory::truncate();
        CouponFactory::truncate();
    }

    public function testCanCalculatePriceWithFixedAmountCoupon(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 10]);
        $product = ProductFactory::createOne(['price' => 200]);
        $coupon = CouponFactory::createOne(['discountType' => CouponDiscountType::FIXED, 'discountAmount' => 50]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => $product->getId(),
                        'couponCode' => $coupon->getCode(),
                        'taxNumber' => 'ZX8F9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        fact($this->getResponse()->getContent())->is('165');
    }

    public function testCanCalculatePriceWithPercentageCoupon(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 10]);
        $product = ProductFactory::createOne(['price' => 200]);
        $coupon = CouponFactory::createOne(['discountType' => CouponDiscountType::PERCENTAGE, 'discountAmount' => 50]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => $product->getId(),
                        'couponCode' => $coupon->getCode(),
                        'taxNumber' => 'ZX8F9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        fact($this->getResponse()->getContent())->is('110');
    }

    public function testCanCalculatePriceWithoutCoupon(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'ZX', 'taxNumberMasks' => ['XYX'], 'amount' => 10]);
        $product = ProductFactory::createOne(['price' => 200]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => $product->getId(),
                        'taxNumber' => 'ZX8F9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        fact($this->getResponse()->getContent())->is('220');
    }

    public function testFailWhenTaxNumberNotPassed(): void
    {
        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => ProductFactory::createOne()->getId(),
                        'couponCode' => CouponFactory::createOne()->getCode(),
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'taxNumber',
            code:         NotNull::IS_NULL_ERROR,
        );
    }

    public function testFailWhenTaxSystemNotFound(): void
    {
        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => ProductFactory::createOne()->getId(),
                        'couponCode' => CouponFactory::createOne()->getCode(),
                        'taxNumber' => 'anything',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'taxNumber',
            code:         AssertTaxNumber::WRONG_COUNTRY_CODE,
        );
    }

    public function testFailWhenWrongTaxNumber(): void
    {
        // arrange
        $product = ProductFactory::createOne();
        $coupon = CouponFactory::createOne();
        TaxSystemFactory::createOne(
            [
                'countryCode' => 'QW',
                'taxNumberMasks' => ['XXX', 'XYX'],
            ]
        );

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => $product->getId(),
                        'couponCode' => $coupon->getCode(),
                        'taxNumber' => 'QW1234',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'taxNumber',
            code:         AssertTaxNumber::WRONG_TAX_NUMBER,
        );
    }

    public function testFailWhenProductNotPassed(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'QW', 'taxNumberMasks' => [ 'XYX']]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'couponCode' => CouponFactory::createOne()->getCode(),
                        'taxNumber' => 'QW8S9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'product',
            code:         NotNull::IS_NULL_ERROR,
        );
    }

    public function testFailWhenProductNotFound(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'QW', 'taxNumberMasks' => [ 'XYX']]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => 9999,
                        'couponCode' => CouponFactory::createOne()->getCode(),
                        'taxNumber' => 'QW8S9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'product',
            code:         AssertEntityExist::NOT_EXIST,
        );
    }

    public function testFailWhenCouponNotFound(): void
    {
        // arrange
        TaxSystemFactory::createOne(['countryCode' => 'QW', 'taxNumberMasks' => [ 'XYX']]);

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => ProductFactory::createOne()->getId(),
                        'couponCode' => 'D15',
                        'taxNumber' => 'QW8S9',
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'couponCode',
            code:         AssertEntityExist::NOT_EXIST,
        );
    }
}
