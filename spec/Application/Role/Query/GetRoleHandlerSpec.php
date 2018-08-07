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

use AulaSoftwareLibre\Iam\Application\Role\Query\GetRole;
use AulaSoftwareLibre\Iam\Application\Role\Query\GetRoleHandler;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use PhpSpec\ObjectBehavior;
use React\Promise\Deferred;
use Tests\Spec\Fixtures;

class GetRoleHandlerSpec extends ObjectBehavior
{
    public function let(RoleViews $roleViews)
    {
        $this->beConstructedWith($roleViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetRoleHandler::class);
    }

    public function it_gets_role_by_id(RoleViews $roleViews, RoleView $roleView)
    {
        $roleViews->get(Fixtures\Role::ROLE_ID)->shouldBeCalled()->willReturn($roleView);

        $this(GetRole::with(RoleId::fromString(Fixtures\Role::ROLE_ID)))->shouldBe($roleView);
    }

    public function it_gets_role_by_id_deferred(RoleViews $roleViews, RoleView $roleView, Deferred $deferred)
    {
        $roleViews->get(Fixtures\Role::ROLE_ID)->shouldBeCalled()->willReturn($roleView);
        $deferred->resolve($roleView)->shouldBeCalled();

        $this(GetRole::with(RoleId::fromString(Fixtures\Role::ROLE_ID)), $deferred);
    }
}
