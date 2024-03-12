<?php

declare(strict_types=1);

namespace AppTests\App\Model\TaxNumber\Validator;

use App\Entity\TaxSystem;
use App\Model\TaxNumber\Validator\AssertTaxNumber;
use App\Model\TaxNumber\Validator\AssertTaxNumberValidator;
use App\Repository\TaxSystemRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @template-extends ConstraintValidatorTestCase<AssertTaxNumberValidator>
 *
 * @covers \App\Model\TaxNumber\Validator\AssertTaxNumberValidator
 */
class AssertTaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    /** @var MockObject&TaxSystemRepository */
    private MockObject $taxSystemRepository;

    protected function setUp(): void
    {
        $this->taxSystemRepository = $this->createMock(TaxSystemRepository::class);

        parent::setUp();
    }


    #[\Override]
    protected function createValidator(): AssertTaxNumberValidator
    {
        return new AssertTaxNumberValidator(
            taxSystemRepository: $this->taxSystemRepository,
        );
    }

    public function testWhenCorrect(): void
    {
        // arrange
        $taxSystem = new TaxSystem();
        $taxSystem->setTaxNumberMasks(['XX', 'XYX', 'YXXY']);

        $this->taxSystemRepository
            ->method('findOneByCountryCode')
            ->willReturn($taxSystem);

        // act
        $this->validator->validate(
            value:      'AB2W2',
            constraint: new AssertTaxNumber()
        );

        // assert
        $this->assertNoViolation();
    }

    public function testTaxNumberMaskNotExist(): void
    {
        // arrange
        $taxSystem = new TaxSystem();
        $taxSystem->setTaxNumberMasks(['XX', 'XYX', 'YXXY']);

        $this->taxSystemRepository
            ->method('findOneByCountryCode')
            ->willReturn($taxSystem);

        // act
        $this->validator->validate(
            value:      'AB123',
            constraint: new AssertTaxNumber()
        );

        // assert
        $this->buildViolation('Wrong tax number')
            ->setCode(AssertTaxNumber::WRONG_TAX_NUMBER)
            ->assertRaised();
    }

    public function testTaxSystemNotExist(): void
    {
        // arrange
        $this->taxSystemRepository
            ->method('findOneByCountryCode')
            ->willReturn(null);

        // act
        $this->validator->validate(
            value:      'anything',
            constraint: new AssertTaxNumber()
        );

        // assert
        $this->buildViolation('Wrong tax number')
            ->setCode(AssertTaxNumber::WRONG_COUNTRY_CODE)
            ->assertRaised();
    }

    /** @dataProvider emptyOrNullProvider */
    public function testEmptyOrNull(?string $value): void
    {
        // act
        $this->validator->validate(
            value:      $value,
            constraint: new AssertTaxNumber()
        );

        // assert
        $this->assertNoViolation();
    }

    /** @return array<mixed> */
    public function emptyOrNullProvider(): array
    {
        return [
            [''],
            [null],
        ];
    }
}
