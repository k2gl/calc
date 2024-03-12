<?php

declare(strict_types=1);

namespace AppTests\Core\Factory;

use RuntimeException;
use Zenstruck\Foundry\ModelFactory;

use function sprintf;

/**
 * @template Entity of object
 * @template-extends ModelFactory<Entity>
 */
abstract class AbstractEntityFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [];
    }

    abstract protected static function getClass(): string;

    /**
     * @phpstan-return Entity
     */
    public static function get(string $id): object
    {
        if (!$entity = static::repository()->find(['id' => $id])) {
            throw new RuntimeException(
                sprintf('Could not get "%s" with id "%s".', static::getClass(), $id)
            );
        }

        return $entity->object();
    }

    /**
     * @phpstan-return Entity
     * @return object<Entity>
     */
    public static function getLast(string $sortedField = 'id'): object
    {
        if (!$entity = static::repository()->last($sortedField)) {
            throw new RuntimeException(
                sprintf('Could not get last "%s".', static::getClass())
            );
        }

        return $entity->object();
    }
}
