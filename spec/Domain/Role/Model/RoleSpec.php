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

namespace spec\AulaSoftwareLibre\Iam\Domain\Role\Model;

use AulaSoftwareLibre\DDD\TestsBundle\Service\Prooph\Spec\AggregateAsserter;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;
use Tests\Spec\Fixtures;

class RoleSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('add', [
            RoleId::fromString(Fixtures\Role::ROLE_ID),
            ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
            RoleName::fromString(Fixtures\Role::NAME),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            RoleWasAdded::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID),
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                RoleName::fromString(Fixtures\Role::NAME)
            )
        );
    }

    public function it_is_an_aggregate(): void
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    public function it_can_be_a_string(): void
    {
        $this->__toString()->shouldBe(Fixtures\Role::NAME);
    }

    public function it_has_a_role_id(): void
    {
        $this->roleId()->shouldBeLike(RoleId::fromString(Fixtures\Role::ROLE_ID));
    }

    public function it_has_a_scope_id(): void
    {
        $this->scopeId()->shouldBeLike(ScopeId::fromString(Fixtures\Scope::SCOPE_ID));
    }

    public function it_has_a_name(): void
    {
        $this->name()->shouldBeLike(RoleName::fromString(Fixtures\Role::NAME));
    }

    public function it_is_not_removed_by_default(): void
    {
        $this->isRemoved()->shouldBe(false);
    }

    public function it_can_be_removed(): void
    {
        $this->remove();

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            RoleWasRemoved::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID)
            )
        );
    }
}
