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

namespace spec\AulaSoftwareLibre\Iam\Application\User\Command;

use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Application\User\Command\DemoteUser;
use AulaSoftwareLibre\Iam\Application\User\Command\DemoteUserHandler;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use PhpSpec\ObjectBehavior;
use Tests\Spec\Fixtures;

class DemoteUserHandlerSpec extends ObjectBehavior
{
    public function let(Users $users, Roles $roles)
    {
        $this->beConstructedWith($users, $roles);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DemoteUserHandler::class);
    }

    public function it_demotes_an_user(
        Users $users,
        User $user,
        Roles $roles,
        Role $role
    ) {
        $users->get(UserId::fromString(Fixtures\User::USER_ID))->shouldBeCalled()->willReturn($user);
        $roles->get(RoleId::fromString(Fixtures\Role::ROLE_ID))->shouldBeCalled();

        $user->demote(RoleId::fromString(Fixtures\Role::ROLE_ID))->shouldBeCalled();
        $users->save($user)->shouldBeCalled();

        $this(DemoteUser::with(
            UserId::fromString(Fixtures\User::USER_ID),
            RoleId::fromString(Fixtures\Role::ROLE_ID)
        ));
    }
}
