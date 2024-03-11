<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function mt_rand;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->seedProducts($manager);

        $manager->flush();
    }

    private function seedProducts(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 35; $i++) {
            $product = new Product();
            $product->setId($i);
            $product->setName('Product ' . $i);
            $product->setPrice(
                (float) random_int(100, 10000) / random_int(11, 19)
            );

            $manager->persist($product);
        }
    }
}
