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
        return $this->_get($userId);
    }

    public function findByUsername(string $username): ?UserView
    {
        return $this->findOneBy('getUsername', $username);
    }

    public function findByEmail(string $email): ?UserView
    {
        return $this->findOneBy('getEmail', $email);
    }

    public function save(UserView $userView): void
    {
        $this->_add($userView->getId(), $userView);
    }
}
