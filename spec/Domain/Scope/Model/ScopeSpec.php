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

namespace spec\AulaSoftwareLibre\Iam\Domain\Scope\Model;

use AulaSoftwareLibre\DDD\TestsBundle\Service\Prooph\Spec\AggregateAsserter;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;
use Tests\Spec\Fixtures;

class ScopeSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedThrough('add', [
            ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
            ScopeName::fromString(Fixtures\Scope::NAME),
            ScopeAlias::fromString(Fixtures\Scope::ALIAS),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasCreated::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                ScopeName::fromString(Fixtures\Scope::NAME),
                ScopeAlias::fromString(Fixtures\Scope::ALIAS)
            )
        );
    }

    public function it_is_an_aggregate(): void
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    public function it_can_be_a_string(): void
    {
        $this->__toString()->shouldBe(Fixtures\Scope::NAME);
    }

    public function it_has_a_scope_id(): void
    {
        $this->scopeId()->shouldBeLike(ScopeId::fromString(Fixtures\Scope::SCOPE_ID));
    }

    public function it_has_a_name(): void
    {
        $this->name()->shouldBeLike(ScopeName::fromString(Fixtures\Scope::NAME));
    }

    public function it_has_an_alias(): void
    {
        $this->alias()->shouldBeLike(ScopeAlias::fromString(Fixtures\Scope::ALIAS));
    }

    public function it_is_not_removed_by_defautl(): void
    {
        $this->isRemoved()->shouldBe(false);
    }

    public function it_can_be_renamed(): void
    {
        $this->rename(ScopeName::fromString('AulaSL Identity and Access Management'));

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRenamed::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                ScopeName::fromString('AulaSL Identity and Access Management')
            )
        );
    }

    public function it_ignores_if_name_does_not_change()
    {
        $this->rename(ScopeName::fromString(Fixtures\Scope::NAME));

        (new AggregateAsserter())->assertAggregateHasNotProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRenamed::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                ScopeName::fromString('AulaSL Identity and Access Management')
            )
        );
    }

    public function it_can_be_removed(): void
    {
        $this->remove();

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRemoved::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID)
            )
        );

        $this->remove();

        (new AggregateAsserter())->assertAggregateHasNotProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRemoved::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID)
            )
        );
    }

    public function it_can_add_roles(): void
    {
        $role = $this->addRole(
            RoleId::fromString(Fixtures\Role::ROLE_ID),
            RoleName::fromString(Fixtures\Role::NAME)
        );

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $role->getWrappedObject(),
            RoleWasAdded::with(
                RoleId::fromString(Fixtures\Role::ROLE_ID),
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                RoleName::fromString(Fixtures\Role::NAME)
            )
        );
    }
}
