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
    protected $stack = [];

    public function init(): void
    {
    }

    public function isInitialized(): bool
    {
        return true;
    }

    public function reset(): void
    {
        $this->users = [];
    }

    public function delete(): void
    {
        $this->users = [];
    }

    public function findAll(): array
    {
        return \array_values($this->stack);
    }

    protected function _add($id, $object)
    {
        $this->stack[$id] = $object;
    }

    protected function _get(string $id)
    {
        return $this->stack[$id] ?? null;
    }

    protected function _remove(string $id)
    {
        if (\array_key_exists($id, $this->stack)) {
            unset($this->stack[$id]);
        }
    }

    protected function findBy($field, $value)
    {
        $instance = current(\array_filter($this->stack, function ($item) use ($field, $value) {
            return $item->$field() === $value;
        }));

        if (false === $instance) {
            return null;
        }

        return $instance;
    }
}
