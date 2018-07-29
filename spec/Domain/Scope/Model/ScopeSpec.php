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

use AulaSoftwareLibre\DDD\Tests\Service\Prooph\Spec\AggregateAsserter;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;

class ScopeSpec extends ObjectBehavior
{
    const SCOPE_ID = '5cd2a872-d88d-45a2-a5d2-5daa71f0d685';
    const NAME = 'Identity and Access Management';
    const SHORT_NAME = 'iam';

    public function let(): void
    {
        $this->beConstructedThrough('add', [
            ScopeId::fromString(self::SCOPE_ID),
            Name::fromString(self::NAME),
            ShortName::fromString(self::SHORT_NAME),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasCreated::with(
                ScopeId::fromString(self::SCOPE_ID),
                Name::fromString(self::NAME),
                ShortName::fromString(self::SHORT_NAME)
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

    public function it_has_a_scope_id(): void
    {
        $this->scopeId()->shouldBeLike(ScopeId::fromString(self::SCOPE_ID));
    }

    public function it_has_a_name(): void
    {
        $this->name()->shouldBeLike(Name::fromString(self::NAME));
    }

    public function it_has_a_short_name(): void
    {
        $this->shortName()->shouldBeLike(ShortName::fromString(self::SHORT_NAME));
    }

    public function it_can_be_renamed(): void
    {
        $this->rename(Name::fromString('AulaSL Identity and Access Management'));

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRenamed::with(
                ScopeId::fromString(self::SCOPE_ID),
                Name::fromString('AulaSL Identity and Access Management')
            )
        );
    }

    public function it_can_removed(): void
    {
        $this->remove();

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            ScopeWasRemoved::with(
                ScopeId::fromString(self::SCOPE_ID)
            )
        );
    }
}
