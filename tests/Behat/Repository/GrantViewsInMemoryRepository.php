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
use Ramsey\Uuid\Uuid;

class GrantViewsInMemoryRepository extends AbstractInMemoryRepository implements GrantViews
{
    protected static $stack = [];

    public function add(GrantView $grantView): void
    {
        $this->_add(Uuid::uuid4()->toString(), $grantView);
    }

    public function remove(string $userId, string $roleId): void
    {
        $found = false;

        /**
         * @var string
         * @var GrantView $grantView
         */
        foreach (static::$stack as $key => $grantView) {
            if ($grantView->getUserId() === $userId
                && $grantView->getRoleId() === $roleId
            ) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return;
        }

        $this->_remove($key);
    }

    public function ofRoleId(string $roleId): array
    {
        return $this->findBy('getRoleId', $roleId);
    }

    public function distinctUsersOfScopeId(string $scopeId): array
    {
        $users = [];
        /** @var GrantView $grantView */
        foreach (static::$stack as $grantView) {
            $users[$grantView->getUserId()] = [
                'userId' => $grantView->getUserId(),
                'username' => $grantView->getUsername(),
            ];
        }

        return \array_values($users);
    }

    public function ofScopeIdAndUserId(string $scopeId, string $userId): array
    {
        return \array_values(\array_filter(static::$stack, function (GrantView $grantView) use ($scopeId, $userId) {
            return $grantView->getScopeId() === $scopeId
                && $grantView->getUserId() === $userId;
        }));
    }
}
