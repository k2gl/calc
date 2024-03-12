<?php

declare(strict_types=1);

namespace App\Model\TaxNumber;

use function preg_replace;

final readonly class TaxNumber
{
    public string $countryCode;
    public string $number;

    public function __construct(
        public string $value,
    ) {
        $this->countryCode = mb_substr($value, 0, 2);
        $this->number = mb_substr($value, 2);
    }

    public function getNumberMask(): string
    {
        return (string) preg_replace(
            pattern:     ['/[a-zA-Z]/', '/[0-9]/'],
            replacement: ['Y', 'X'],
            subject:     $this->number
        );
    }
}
