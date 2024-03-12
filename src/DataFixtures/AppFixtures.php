<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxSystem;
use App\Reference\CouponDiscountType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function random_int;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->seedProduct($manager);
        $this->seedCoupon($manager);
        $this->seedTax($manager);

        $manager->flush();
    }

    private function seedProduct(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();

            $product->setId($i);
            $product->setName('Product ' . $i);
            $product->setPrice(
                (float) random_int(100, 10000) / random_int(11, 19)
            );

            $manager->persist($product);
        }
    }

    private function seedCoupon(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $coupon = new Coupon();

            $coupon->setDiscountType(CouponDiscountType::any());
            $coupon->setCode('D' . $i);
            $coupon->setDiscountAmount(random_int(2, 25));

            $manager->persist($coupon);
        }
    }

    private function seedTax(ObjectManager $manager): void
    {
        /** @var list<array{countryCode: string, masks: list<string>, amount: float}> $data */
        $data = [
            [
                'countryCode' => 'DE',
                'masks' => ['XXXXXXXXX'],
                'amount' => 19,
            ],
            [
                'countryCode' => 'IT',
                'masks' => ['XXXXXXXXXXX'],
                'amount' => 22,
            ],
            [
                'countryCode' => 'GR',
                'masks' => ['XXXXXXXXX'],
                'amount' => 24,
            ],
            [
                'countryCode' => 'FR',
                'masks' => ['YYXXXXXXXXX'],
                'amount' => 20,
            ],
        ];

        foreach ($data as $item) {
            $tax = new TaxSystem();

            $tax->setCountryCode($item['countryCode']);
            $tax->setTaxNumberMasks($item['masks']);
            $tax->setAmount($item['amount']);

            $manager->persist($tax);
        }
    }
}
