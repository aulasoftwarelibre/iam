<?php

declare(strict_types=1);

/*
 * This file is part of the `iam` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Behat\Repository;

class AbstractInMemoryRepository
{
    protected static $stack = [];

    public function reset(): void
    {
        static::$stack = [];
    }

    protected function _add($id, $object)
    {
        static::$stack[$id] = $object;
    }

    protected function _get(string $id)
    {
        return static::$stack[$id] ?? null;
    }

    protected function _remove(string $id)
    {
        if (\array_key_exists($id, static::$stack)) {
            unset(static::$stack[$id]);
        }
    }

    public function findAll(): array
    {
        return \array_values(static::$stack);
    }

    protected function findBy($field, $value): array
    {
        return \array_values(\array_filter(static::$stack, function ($item) use ($field, $value) {
            return $item->$field() === $value;
        }));
    }

    protected function findOneBy($field, $value)
    {
        $instance = current($this->findBy($field, $value));

        if (false === $instance) {
            return null;
        }

        return $instance;
    }
}
