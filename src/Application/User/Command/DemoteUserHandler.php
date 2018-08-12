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

namespace AulaSoftwareLibre\Iam\Application\User\Command;

use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;

class DemoteUserHandler
{
    /**
     * @var Users
     */
    private $users;
    /**
     * @var Roles
     */
    private $roles;

    public function __construct(Users $users, Roles $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function __invoke(DemoteUser $demoteUser)
    {
        $userId = $demoteUser->userId();
        $roleId = $demoteUser->roleId();

        $user = $this->users->get($userId);
        $this->roles->get($roleId);

        $user->demote($roleId);

        $this->users->save($user);
    }
}
