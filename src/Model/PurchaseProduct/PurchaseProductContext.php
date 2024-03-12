<?php

declare(strict_types=1);

namespace App\Model\PurchaseProduct;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxSystem;

final readonly class PurchaseProductContext
{
    public function __construct(
        public Product $product,
        public TaxSystem $taxSystem,
        public ?Coupon $coupon = null,
    ) {
    }
}
