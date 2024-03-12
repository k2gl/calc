<?php

declare(strict_types=1);

namespace AppTests\Core\Factory;

use App\Entity\Coupon;
use App\Entity\TaxSystem;
use App\Reference\CouponDiscountType;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

use function random_int;

/**
 * @extends AbstractEntityFactory<TaxSystem>
 *
 * @method static Proxy<TaxSystem> createOne(array $attributes = [])
 * @method static Proxy<TaxSystem> find(object|array|mixed $criteria)
 * @method static Proxy<TaxSystem> findOrCreate(array $attributes)
 * @method static Proxy<TaxSystem> first(string $sortedField = 'id')
 * @method static Proxy<TaxSystem> last(string $sortedField = 'id')
 * @method static Proxy<TaxSystem> random(array $attributes = [])
 * @method static Proxy<TaxSystem> randomOrCreate(array $attributes = [])
 * @method static RepositoryProxy<TaxSystem> repository()
 * @method static TaxSystem get(string $id)
 */
final class TaxSystemFactory extends AbstractEntityFactory
{
    protected function getDefaults(): array
    {
        return [
            'countryCode' => 'ZZ',
            'taxNumberMasks' => ['XXXXXXXXX'],
            'amount' => random_int(0, 99),
        ];
    }

    protected static function getClass(): string
    {
        return TaxSystem::class;
    }
}
