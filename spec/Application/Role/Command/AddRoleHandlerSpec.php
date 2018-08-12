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

use AulaSoftwareLibre\Iam\Application\Role\Command\AddRole;
use AulaSoftwareLibre\Iam\Application\Role\Command\AddRoleHandler;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNameAlreadyExistsException;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use PhpSpec\ObjectBehavior;
use Tests\Spec\Fixtures;

class AddRoleHandlerSpec extends ObjectBehavior
{
    public function let(Roles $roles, RoleViews $roleViews, Scopes $scopes, Scope $scope): void
    {
        $this->beConstructedWith($roles, $roleViews, $scopes);

        $roles->find(RoleId::fromString(Fixtures\Role::ROLE_ID))->willReturn(null);
        $roleViews->findOneByScopeIdAndRoleName(Fixtures\Scope::SCOPE_ID, Fixtures\Role::NAME)->willReturn(null);
        $scopes->find(ScopeId::fromString(Fixtures\Scope::SCOPE_ID))->willReturn($scope);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AddRoleHandler::class);
    }

    public function it_adds_a_role_to_a_scope(
        Roles $roles,
        Scope $scope,
        Role $role
    ): void {
        $scope->alias()->shouldBeCalled()->willReturn(ScopeAlias::fromString('iam'));
        $scope->addRole(
            RoleId::fromString(Fixtures\Role::ROLE_ID),
            RoleName::fromString(Fixtures\Role::NAME)
            )
            ->shouldBeCalled()
            ->willReturn($role)
        ;
        $roles->save($role)->shouldBeCalled();

        $this(AddRole::with(
            RoleId::fromString(Fixtures\Role::ROLE_ID),
            ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
            RoleName::fromString(Fixtures\Role::NAME)
        ));
    }

    public function it_checks_role_id_does_not_exist(Roles $roles, Role $role): void
    {
        $roles->find(RoleId::fromString(Fixtures\Role::ROLE_ID))->willReturn($role);

        $this->shouldThrow(RoleIdAlreadyRegisteredException::class)->during('__invoke', [
            AddRole::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID),
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                RoleName::fromString(Fixtures\Role::NAME)
            ),
        ]);
    }

    public function it_checks_role_name_is_unique_by_scope(RoleViews $roleViews): void
    {
        $roleViews->findOneByScopeIdAndRoleName(Fixtures\Scope::SCOPE_ID, Fixtures\Role::NAME)->shouldBeCalled()->willReturn(
            new RoleView(Fixtures\Role::ROLE_ID, Fixtures\Scope::SCOPE_ID, Fixtures\Role::NAME)
        );

        $this->shouldThrow(RoleNameAlreadyExistsException::class)->during('__invoke', [
            AddRole::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID),
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                RoleName::fromString(Fixtures\Role::NAME)
            ),
        ]);
    }

    public function it_checks_scope_id_exists(Scopes $scopes): void
    {
        $scopes->find(ScopeId::fromString(Fixtures\Scope::SCOPE_ID))->willReturn(null);

        $this->shouldThrow(ScopeNotFoundException::class)->during('__invoke', [
            AddRole::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID),
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                RoleName::fromString(Fixtures\Role::NAME)
            ),
        ]);
    }
}
