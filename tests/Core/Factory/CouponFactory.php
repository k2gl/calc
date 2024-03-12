<?php

declare(strict_types=1);

namespace AppTests\Core\Factory;

use App\Entity\Coupon;
use App\Reference\CouponDiscountType;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

use function random_int;

/**
 * @extends AbstractEntityFactory<Coupon>
 *
 * @method static Proxy<Coupon> createOne(array $attributes = [])
 * @method static Proxy<Coupon> find(object|array|mixed $criteria)
 * @method static Proxy<Coupon> findOrCreate(array $attributes)
 * @method static Proxy<Coupon> first(string $sortedField = 'id')
 * @method static Proxy<Coupon> last(string $sortedField = 'id')
 * @method static Proxy<Coupon> random(array $attributes = [])
 * @method static Proxy<Coupon> randomOrCreate(array $attributes = [])
 * @method static RepositoryProxy<Coupon> repository()
 * @method static Coupon get(string $id)
 */
final class CouponFactory extends AbstractEntityFactory
{
    protected function getDefaults(): array
    {
        return [
            'code' => 'D' . time(),
            'discountType' => CouponDiscountType::any(),
            'discountAmount' => random_int(0, 99),
        ];
    }

    protected static function getClass(): string
    {
        return Coupon::class;
    }
}
