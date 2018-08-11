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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;

class GrantViewsInMemoryRepository extends AbstractInMemoryRepository implements GrantViews
{
    protected static $stack = [];

    public function add(GrantView $grantView): void
    {
        $this->_add($grantView->getId(), $grantView);
    }

    public function remove(string $userId, string $roleId): void
    {
        $found = \array_reduce(
            static::$stack,
            function (?GrantView $found, GrantView $grantView) use ($userId, $roleId) {
                return (
                    $grantView->getUserId() === $userId
                    && $grantView->getRoleId() === $roleId
                ) ? $grantView : $found;
            },
            null
        );

        if (!$found instanceof GrantView) {
            return;
        }

        $this->_remove($found->getId());
    }

    public function findByRoleId(string $roleId): array
    {
        return $this->findBy('getRoleId', $roleId);
    }
}
