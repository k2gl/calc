<?php

declare(strict_types=1);

namespace AppTests\App\Service\ProductPriceCalculator;

use App\Service\ProductPriceCalculator\ProductPriceCalculator;
use App\Service\ProductPriceCalculator\ProductPriceCalculatorContext;
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
        // act
        $sut = (new ProductPriceCalculator())->getPrice(
            context: new ProductPriceCalculatorContext(
                productPrice: $productPrice,
                taxSize:      $taxSize,
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
        // act
        $sut = (new ProductPriceCalculator())->getPrice(
            context: new ProductPriceCalculatorContext(
                productPrice:  $productPrice,
                taxSize:       $taxSize,
                fixedDiscount: $fixedDiscount,
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
        // act
        $sut = (new ProductPriceCalculator())->getPrice(
            context: new ProductPriceCalculatorContext(
                productPrice:       $productPrice,
                taxSize:            $taxSize,
                percentageDiscount: $percentageDiscount,
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
}
