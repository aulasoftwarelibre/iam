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

use AulaSoftwareLibre\Iam\Application\User\Exception\UserNotFoundException;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;

class UserViewsInMemoryRepository extends AbstractInMemoryRepository implements UserViews
{
    protected static $stack = [];

    public function add(UserView $userView): void
    {
        $this->_add($userView->getId(), $userView);
    }

    public function get(string $userId): UserView
    {
        $user = $this->ofId($userId);

        if (!$user instanceof UserView) {
            throw UserNotFoundException::withUserId(UserId::fromString($userId));
        }

        return $user;
    }

    public function ofId(string $userId): ?UserView
    {
        return $this->_ofId($userId);
    }

    public function ofUsername(string $username): ?UserView
    {
        return $this->findOneBy('getUsername', $username);
    }

    public function save(UserView $userView): void
    {
        $this->_add($userView->getId(), $userView);
    }
}
