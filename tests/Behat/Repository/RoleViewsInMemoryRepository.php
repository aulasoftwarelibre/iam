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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;

class RoleViewsInMemoryRepository extends AbstractInMemoryRepository implements RoleViews
{
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

    public function describe(string $roleId, ?string $description): void
    {
        /** @var RoleView $roleView */
        $roleView = $this->get($roleId);
        $roleView->setDescription($description);

        $this->_add($roleId, $roleView);
    }

    public function findByScopeId(string $scopeId): array
    {
        return $this->findBy('scopeId', $scopeId);
    }

    public function findOneByRoleName(string $scopeId, string $roleName): ?RoleView
    {
        $found = \array_reduce(
            $this->stack,
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