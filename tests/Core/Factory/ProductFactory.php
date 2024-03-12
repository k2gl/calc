<?php

declare(strict_types=1);

namespace AppTests\Core\Factory;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Reference\CouponDiscountType;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

use function random_int;

/**
 * @extends AbstractEntityFactory<Product>
 *
 * @method static Proxy<Product> createOne(array $attributes = [])
 * @method static Proxy<Product> find(object|array|mixed $criteria)
 * @method static Proxy<Product> findOrCreate(array $attributes)
 * @method static Proxy<Product> first(string $sortedField = 'id')
 * @method static Proxy<Product> last(string $sortedField = 'id')
 * @method static Proxy<Product> random(array $attributes = [])
 * @method static Proxy<Product> randomOrCreate(array $attributes = [])
 * @method static RepositoryProxy<Product> repository()
 * @method static Product get(string $id)
 */
final class ProductFactory extends AbstractEntityFactory
{
    protected function getDefaults(): array
    {
        return [
            'id' => time(),
            'name' => 'Product',
            'price' => (float) random_int(0, 99),
        ];
    }

    protected static function getClass(): string
    {
        return Product::class;
    }
}
