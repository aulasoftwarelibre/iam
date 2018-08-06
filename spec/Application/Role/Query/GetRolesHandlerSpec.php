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

namespace spec\AulaSoftwareLibre\Iam\Application\Role\Query;

use AulaSoftwareLibre\Iam\Application\Role\Query\GetRoles;
use AulaSoftwareLibre\Iam\Application\Role\Query\GetRolesHandler;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;
use PhpSpec\ObjectBehavior;
use React\Promise\Deferred;
use Tests\Spec\Fixtures;

class GetRolesHandlerSpec extends ObjectBehavior
{
    public function let(RoleViews $roleViews)
    {
        $this->beConstructedWith($roleViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetRolesHandler::class);
    }

    public function it_get_roles_by_scope_id(RoleViews $roleViews, RoleView $roleView)
    {
        $roleViews->findByScopeId(Fixtures\Scope::SCOPE_ID)->shouldBeCalled()->willReturn([$roleView]);

        $this(GetRoles::with(ScopeId::fromString(Fixtures\Scope::SCOPE_ID)))->shouldBe([$roleView]);
    }

    public function it_get_roles_by_scope_id_deferred(RoleViews $roleViews, RoleView $roleView, Deferred $deferred)
    {
        $roleViews->findByScopeId(Fixtures\Scope::SCOPE_ID)->shouldBeCalled()->willReturn([$roleView]);
        $deferred->resolve([$roleView])->shouldBeCalled();

        $this(GetRoles::with(ScopeId::fromString(Fixtures\Scope::SCOPE_ID)), $deferred);
    }
}
