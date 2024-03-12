<?php

declare(strict_types=1);

namespace App\Controller\CalculateProductPrice\Adapter;

use App\Controller\CalculateProductPrice\Request\CalculateProductPriceRequest;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxSystem;
use App\Model\TaxNumber\TaxNumber;
use App\Reference\CouponDiscountType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxSystemRepository;
use App\Service\ProductPriceCalculator\ProductPriceCalculatorContext;

final readonly class ProductPriceCalculatorContextAdapter
{
    public function __construct(
        private ProductRepository $productRepository,
        private TaxSystemRepository $taxSystemRepository,
        private CouponRepository $couponRepository,
    ) {
    }

    public function create(CalculateProductPriceRequest $input): ProductPriceCalculatorContext
    {
        /** @var Product $product */
        $product = $this->productRepository->find($input->product);
        $coupon = $input->couponCode ? $this->couponRepository->findOneByCode($input->couponCode) : null;
        $taxNumber = new TaxNumber($input->taxNumber);
        /** @var TaxSystem $taxSystem */
        $taxSystem = $this->taxSystemRepository->findOneByCountryCode($taxNumber->countryCode);

        return new ProductPriceCalculatorContext(
            productPrice:       $product->getPrice(),
            taxSize:            $taxSystem->getAmount(),
            fixedDiscount:      $this->getFixedDiscount($coupon),
            percentageDiscount: $this->getPercentageDiscount($coupon),
        );
    }

    private function getFixedDiscount(?Coupon $coupon): float
    {
        if (!$coupon || !$coupon->getDiscountType()->is(CouponDiscountType::FIXED)) {
            return 0.0;
        }

        return $coupon->getDiscountAmount();
    }

    private function getPercentageDiscount(?Coupon $coupon): float
    {
        if (!$coupon || !$coupon->getDiscountType()->is(CouponDiscountType::PERCENTAGE)) {
            return 0.0;
        }

        return $coupon->getDiscountAmount();
    }
}
