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

namespace spec\AulaSoftwareLibre\Iam\Application\Role\Command;

use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRole;
use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRoleHandler;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use PhpSpec\ObjectBehavior;
use Tests\Spec\Fixtures;

class RemoveRoleHandlerSpec extends ObjectBehavior
{
    public function let(Roles $roles): void
    {
        $this->beConstructedWith($roles);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoveRoleHandler::class);
    }

    public function it_removes_a_role(Roles $roles, Role $role): void
    {
        $roles->get(RoleId::fromString(Fixtures\Role::ROLE_ID))->shouldBeCalled()->willReturn($role);
        $role->remove()->shouldBeCalled();
        $roles->save($role)->shouldBeCalled();

        $this(RemoveRole::with(
            RoleId::fromString(Fixtures\Role::ROLE_ID)
        ));
    }
}
