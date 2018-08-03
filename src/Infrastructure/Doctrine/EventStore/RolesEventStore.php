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

namespace AulaSoftwareLibre\Iam\Infrastructure\Doctrine\EventStore;

use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNotFoundException;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class RolesEventStore extends AggregateRepository implements Roles
{
    public function save(Role $role): void
    {
        $this->saveAggregateRoot($role);
    }

    public function get(RoleId $roleId): Role
    {
        $role = $this->find($roleId);

        if (!$role instanceof Role) {
            throw RoleNotFoundException::withRoleId($roleId);
        }

        return $role;
    }

    public function find(RoleId $roleId): ?Role
    {
        return $this->getAggregateRoot($roleId->toString());
    }

    public function nextIdentity(): RoleId
    {
        return RoleId::generate();
    }
}
