<?php

declare(strict_types=1);

namespace AppTests\App\Model\TaxNumber;

use App\Model\TaxNumber\TaxNumber;
use PHPUnit\Framework\TestCase;

use function K2gl\PHPUnitFluentAssertions\fact;

/** @covers \App\Model\TaxNumber\TaxNumber */
class TaxNumberTest extends TestCase
{
    /** @dataProvider specificationExpectedValuesProvider */
    public function testSpecification(
        string $value,
        string $expectedCountryCode,
        string $expectedNumber,
        string $expectedNumberMask,
    ): void {
        $taxNumber = new TaxNumber($value);

        fact($taxNumber->value)->is($value);
        fact($taxNumber->countryCode)->is($expectedCountryCode);
        fact($taxNumber->number)->is($expectedNumber);
        fact($taxNumber->getNumberMask())->is($expectedNumberMask);
    }

    /** @return array<array{value: string, expectedCountryCode: string, expectedNumber: string, expectedNumberMask: string}> */
    public function specificationExpectedValuesProvider(): array
    {
        return [
            [
                'value' => 'AB12345EF6',
                'expectedCountryCode' => 'AB',
                'expectedNumber' => '12345EF6',
                'expectedNumberMask' => 'XXXXXYYX',
            ],
            [
                'value' => 'CD',
                'expectedCountryCode' => 'CD',
                'expectedNumber' => '',
                'expectedNumberMask' => '',
            ],
            [
                'value' => '',
                'expectedCountryCode' => '',
                'expectedNumber' => '',
                'expectedNumberMask' => '',
            ],
            [
                'value' => 'DF_+1\яблоко',
                'expectedCountryCode' => 'DF',
                'expectedNumber' => '_+1\яблоко',
                'expectedNumberMask' => '_+X\яблоко',
            ],
        ];
    }
}
