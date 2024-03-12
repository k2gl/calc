<?php

declare(strict_types=1);

namespace App\Model\TaxNumber\Validator;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class AssertTaxNumber extends Constraint
{
    public const string WRONG_COUNTRY_CODE = 'fd90d694-207d-418a-80fc-32275af1444c';
    public const string WRONG_TAX_NUMBER = 'f66fd6f0-7d03-4548-95cb-2737794998da';

    protected const array ERROR_NAMES = [
        self::WRONG_COUNTRY_CODE => 'WRONG_COUNTRY_CODE',
        self::WRONG_TAX_NUMBER => 'WRONG_TAX_NUMBER',
    ];

    #[HasNamedArguments]
    public function __construct(
        public string $message = 'Wrong tax number',
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
