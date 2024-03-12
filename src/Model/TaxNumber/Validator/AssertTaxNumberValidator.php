<?php

declare(strict_types=1);

namespace App\Model\TaxNumber\Validator;

use App\Model\TaxNumber\TaxNumber;
use App\Repository\TaxSystemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class AssertTaxNumberValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TaxSystemRepository $taxSystemRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AssertTaxNumber) {
            throw new UnexpectedTypeException(
                $constraint,
                expectedType: AssertTaxNumber::class,
            );
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // check if tax system exist
        $valueTaxNumber = new TaxNumber($value);

        if (null === $taxSystem = $this->taxSystemRepository->findOneByCountryCode($valueTaxNumber->countryCode)) {
            $this->context->buildViolation($constraint->message)
                ->setCode(AssertTaxNumber::WRONG_COUNTRY_CODE)
                ->addViolation();

            return;
        }

        // check if tax number mask exist
        $valueTaxNumberMask = $valueTaxNumber->getNumberMask();
        $taxNumberMaskMatch = false;

        foreach ($taxSystem->getTaxNumberMasks() as $taxSystemNumberMask) {
            if ($taxSystemNumberMask === $valueTaxNumberMask) {
                $taxNumberMaskMatch = true;
                break;
            }
        }

        if (!$taxNumberMaskMatch) {
            $this->context->buildViolation($constraint->message)
                ->setCode(AssertTaxNumber::WRONG_TAX_NUMBER)
                ->addViolation();
        }
    }
}
