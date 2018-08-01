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
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasDescribed;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Description;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;

class RoleSpec extends ObjectBehavior
{
    private const ROLE_ID = 'e408bda0-5bed-4c50-85a8-662fa596aebf';
    private const SCOPE_ID = '5cd2a872-d88d-45a2-a5d2-5daa71f0d685';
    private const NAME = 'ROLE_USER';
    private const DESCRIPTION = 'Role user';

    public function let()
    {
        $this->beConstructedThrough('add', [
            RoleId::fromString(self::ROLE_ID),
            ScopeId::fromString(self::SCOPE_ID),
            Name::fromString(self::NAME),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            RoleWasAdded::with(
                RoleId::fromString(self::ROLE_ID),
                ScopeId::fromString(self::SCOPE_ID),
                Name::fromString(self::NAME)
            )
        );
    }

    public function it_is_an_aggregate(): void
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    public function it_can_be_a_string(): void
    {
        $this->__toString()->shouldBe(self::NAME);
    }

    public function it_has_a_role_id(): void
    {
        $this->roleId()->shouldBeLike(RoleId::fromString(self::ROLE_ID));
    }

    public function it_has_a_scope_id(): void
    {
        $this->scopeId()->shouldBeLike(ScopeId::fromString(self::SCOPE_ID));
    }

    public function it_has_a_name(): void
    {
        $this->name()->shouldBeLike(Name::fromString(self::NAME));
    }

    public function it_has_no_description_by_default(): void
    {
        $this->description()->shouldBeLike(Description::fromString(''));
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
                RoleId::fromString(self::ROLE_ID)
            )
        );
    }

    public function it_can_be_described(): void
    {
        $this->describe(Description::fromString(self::DESCRIPTION));

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            RoleWasDescribed::with(
                RoleId::fromString(self::ROLE_ID),
                Description::fromString(self::DESCRIPTION)
            )
        );
    }
}
