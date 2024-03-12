<?php

declare(strict_types=1);

namespace AppTests\App\Controller\CalculatePrice;

use AppTests\Core\ApiControllerTestCase;
use AppTests\Core\Factory\CouponFactory;
use AppTests\Core\Factory\ProductFactory;
use K2gl\Component\Validator\Constraint\EntityExist\AssertEntityExist;
use Symfony\Component\HttpFoundation\Response;

/** @covers \App\Controller\CalculatePrice\CalculatePriceController::__invoke */
class CalculatePriceControllerTest extends ApiControllerTestCase
{
    public function testKek(): void
    {
        // arrange
        $product = ProductFactory::createOne();
        $coupon = CouponFactory::createOne();

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => $product->getId(),
                        'couponCode' => $coupon->getCode(),
                    ],
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseContainsViolation(
            propertyPath: 'product',
            code:         AssertEntityExist::NOT_EXIST,
        );
    }

    public function testFailWhenProductNotFound(): void
    {
        // arrange
        ProductFactory::truncate();

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => 9999,
                        'couponCode' => CouponFactory::createOne()->getCode(),
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
        CouponFactory::truncate();

        // act
        $this->sendJsonRequest(
            method: 'POST',
            url:    '/calculate-price',
            json:   [
                        'product' => ProductFactory::createOne()->getId(),
                        'couponCode' => 'D15',
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
