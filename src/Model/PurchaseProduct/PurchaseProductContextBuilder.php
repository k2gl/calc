<?php

declare(strict_types=1);

namespace App\Model\PurchaseProduct;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxSystem;
use App\Model\TaxNumber\TaxNumber;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxSystemRepository;
use InvalidArgumentException;

final readonly class PurchaseProductContextBuilder
{
    public function __construct(
        private ProductRepository $productRepository,
        private TaxSystemRepository $taxSystemRepository,
        private CouponRepository $couponRepository,
    ) {
    }

    public function create(int $productId, string $taxNumber, ?string $couponCode): PurchaseProductContext
    {
        return new PurchaseProductContext(
            product: $this->getProduct($productId),
            taxSystem: $this->getTaxSystem($taxNumber),
            coupon: $this->getCoupon($couponCode),
        );
    }

    private function getProduct(int $productId): Product
    {
        if (!$product = $this->productRepository->find($productId)) {
            throw new InvalidArgumentException('Product does not exist');
        }

        return $product;
    }

    private function getCoupon(?string $couponCode): ?Coupon
    {
        if (!$couponCode) {
            return null;
        }

        if (!$coupon = $this->couponRepository->findOneByCode($couponCode)) {
            throw new InvalidArgumentException('Coupon does not exist');
        }

        return $coupon;
    }

    private function getTaxSystem(string $taxNumber): TaxSystem
    {
        $taxNumberType = new TaxNumber($taxNumber);

        if (!$taxSystem = $this->taxSystemRepository->findOneByCountryCode($taxNumberType->countryCode)) {
            throw new InvalidArgumentException('Tax system does not exist');
        }

        return $taxSystem;
    }
}
