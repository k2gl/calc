<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Reference\CouponDiscountType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function random_int;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->seedProducts($manager);
        $this->seedCoupons($manager);

        $manager->flush();
    }

    private function seedProducts(ObjectManager $manager): void
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

    private function seedCoupons(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 35; $i++) {
            $coupon = new Coupon();

            $coupon->setDiscountType(CouponDiscountType::any());
            $coupon->setCode('D' . $i);
            $coupon->setDiscountAmount(random_int(2, 25));

            $manager->persist($coupon);
        }
    }
}
