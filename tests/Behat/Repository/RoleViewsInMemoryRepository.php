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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;

class RoleViewsInMemoryRepository extends AbstractInMemoryRepository implements RoleViews
{
    protected static $stack = [];

    public function add(RoleView $roleView): void
    {
        $this->_add($roleView->getId(), $roleView);
    }

    public function get(string $roleId): ?RoleView
    {
        return $this->_get($roleId);
    }

    public function remove(string $roleId): void
    {
        $this->_remove($roleId);
    }

    public function findByScopeId(string $scopeId): array
    {
        return $this->findBy('getScopeId', $scopeId);
    }

    public function findOneByScopeIdAndRoleName(string $scopeId, string $roleName): ?RoleView
    {
        $found = \array_reduce(
            static::$stack,
            function (?RoleView $found, RoleView $roleView) use ($scopeId, $roleName) {
                return (
                        $roleView->getScopeId() === $scopeId
                        && $roleView->getName() === $roleName
                    ) ? $roleView : $found;
            },
            null
        );

        return $found;
    }
}
