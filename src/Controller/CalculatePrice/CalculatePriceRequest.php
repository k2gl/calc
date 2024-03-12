<?php

declare(strict_types=1);

namespace App\Controller\CalculatePrice;

use App\Entity\Coupon;
use App\Entity\Product;
use Fusonic\HttpKernelBundle\Attribute\FromRequest;
use K2gl\Component\Validator\Constraint\EntityExist\AssertEntityExist;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use Symfony\Component\Validator\Constraints as Assert;

#[FromRequest]
#[RequestBody]
final readonly class CalculatePriceRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[AssertEntityExist(
            entity: Product::class,
            property: 'id',
            message: 'Product does not exist',
        )]
        #[Property(example: '1')]
        public int $product,
        #[Assert\NotBlank]
        #[AssertEntityExist(
            entity: Coupon::class,
            property: 'code',
            message: 'Coupon does not exist',
        )]
        #[Property(example: 'D10')]
        public string $couponCode,
    ) {
    }
}
