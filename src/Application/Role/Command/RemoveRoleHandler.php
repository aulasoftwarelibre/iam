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

namespace AulaSoftwareLibre\Iam\Application\Role\Command;

use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;

class RemoveRoleHandler
{
    /**
     * @var Roles
     */
    private $roles;

    public function __construct(Roles $roles)
    {
        $this->roles = $roles;
    }

    public function __invoke(RemoveRole $removeRole)
    {
        $role = $this->roles->get($removeRole->roleId());
        $role->remove();

        $this->roles->save($role);
    }
}
