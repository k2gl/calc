<?php

declare(strict_types=1);

namespace App\Reference;

use K2gl\Enum\ExtendedBackedEnum;
use K2gl\Enum\ExtendedBackedEnumInterface;

enum CouponDiscountType: string implements ExtendedBackedEnumInterface
{
    use ExtendedBackedEnum;

    case FIXED = 'FIXED';
    case PERCENTAGE = 'PERCENTAGE';
}
