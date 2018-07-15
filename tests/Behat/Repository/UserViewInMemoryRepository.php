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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\User\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\User\View\UserView;

class UserViewInMemoryRepository implements UserViews
{
    private $users = [];

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

    public function add(UserView $userView): void
    {
        $this->users[$userView->id()] = $userView;
    }

    public function get(string $userId): UserView
    {
        return $this->users[$userId];
    }

    public function findByUsername(string $username): ?UserView
    {
        $userView = current(\array_filter($this->users, function (UserView $userView) use ($username) {
            return $userView->username() === $username;
        }));

        if (false === $userView) {
            return null;
        }

        return $userView;
    }

    public function findByEmail(string $email): ?UserView
    {
        $userView = current(\array_filter($this->users, function (UserView $userView) use ($email) {
            return $userView->email() === $email;
        }));

        if (false === $userView) {
            return null;
        }

        return $userView;
    }
}
