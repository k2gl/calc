<?php

declare(strict_types=1);

namespace AppTests\App\Service\ProductPriceCalculator;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxSystem;
use App\Model\PurchaseProduct\PurchaseProductContext;
use App\Reference\CouponDiscountType;
use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\ProductPriceCalculator\ProductPriceCalculator
 */
class ProductPriceCalculatorTest extends TestCase
{
    /**
     * @dataProvider priceIncreasedByAmountOfTaxProvider
     */
    public function testPriceIncreasedByAmountOfTax(
        float $productPrice,
        float $taxSize,
        float $expected,
    ): void {
        // arrange
        $product = new Product();
        $product->setPrice($productPrice);

        $taxSystem = new TaxSystem();
        $taxSystem->setAmount($taxSize);

        // act
        $sut = $this->getProductPriceCalculator()->getPrice(
            context: new PurchaseProductContext(
                product: $product,
                taxSystem: $taxSystem,
            ),
        );

        // assert
        $this->assertEquals($expected, $sut);
    }

    /** @return list<array{productPrice: float, taxSize: float, expected: float}> */
    public function priceIncreasedByAmountOfTaxProvider(): array
    {
        return [
            ['productPrice' => 100, 'taxSize' => 24, 'expected' => 124],
            ['productPrice' => 123.45, 'taxSize' => 67.89, 'expected' => 207.260205],
            ['productPrice' => 555, 'taxSize' => 0, 'expected' => 555.0],
        ];
    }

    /**
     * @dataProvider fixedDiscountProvider
     */
    public function testFixedDiscount(
        float $productPrice,
        float $fixedDiscount,
        float $taxSize,
        float $expected,
    ): void {
        // arrange
        $product = new Product();
        $product->setPrice($productPrice);

        $taxSystem = new TaxSystem();
        $taxSystem->setAmount($taxSize);

        $coupon = new Coupon();
        $coupon->setDiscountType(CouponDiscountType::FIXED);
        $coupon->setDiscountAmount($fixedDiscount);

        // act
        $sut = $this->getProductPriceCalculator()->getPrice(
            context: new PurchaseProductContext(
                product: $product,
                taxSystem: $taxSystem,
                coupon: $coupon,
            ),
        );

        // assert
        $this->assertEquals($expected, $sut);
    }

    /** @return list<array{productPrice: float, fixedDiscount: float, taxSize: float, expected: float}> */
    public function fixedDiscountProvider(): array
    {
        return [
            ['productPrice' => 200, 'fixedDiscount' => 50, 'taxSize' => 10, 'expected' => 165.0],
        ];
    }

    /**
     * @dataProvider percentageDiscountProvider
     */
    public function testPercentageDiscount(
        float $productPrice,
        float $percentageDiscount,
        float $taxSize,
        float $expected,
    ): void {
        // arrange
        $product = new Product();
        $product->setPrice($productPrice);

        $taxSystem = new TaxSystem();
        $taxSystem->setAmount($taxSize);

        $coupon = new Coupon();
        $coupon->setDiscountType(CouponDiscountType::PERCENTAGE);
        $coupon->setDiscountAmount($percentageDiscount);

        // act
        $sut = $this->getProductPriceCalculator()->getPrice(
            context: new PurchaseProductContext(
                product: $product,
                taxSystem: $taxSystem,
                coupon: $coupon,
            ),
        );

        // assert
        $this->assertEquals($expected, $sut);
    }

    /** @return list<array{productPrice: float, percentageDiscount: float, taxSize: float, expected: float}> */
    public function percentageDiscountProvider(): array
    {
        return [
            ['productPrice' => 200, 'percentageDiscount' => 50, 'taxSize' => 10, 'expected' => 110.0],
        ];
    }

    protected function getProductPriceCalculator(): ProductPriceCalculator
    {
        return (new ProductPriceCalculator());
    }
}
