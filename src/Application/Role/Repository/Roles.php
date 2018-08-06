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

namespace AulaSoftwareLibre\Iam\Application\Role\Repository;

use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;

interface Roles
{
    public function save(Role $role): void;

    public function get(RoleId $roleId): Role;

    public function find(RoleId $roleId): ?Role;

    public function nextIdentity(): RoleId;
}
