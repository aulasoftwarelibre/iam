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

namespace Tests\Service\Prooph\Spec;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\EventStoreIntegration\ClosureAggregateTranslator;
use Webmozart\Assert\Assert;

final class AggregateAsserter
{
    /** @var ClosureAggregateTranslator */
    private $closureAggregateTranslator;

    /** @var MessageMatcher */
    private $messageMatcher;

    public function __construct()
    {
        $this->closureAggregateTranslator = new ClosureAggregateTranslator();
        $this->messageMatcher = new MessageMatcher();
    }

    public function assertAggregateHasProducedEvent($aggregateRoot, AggregateChanged $event): void
    {
        Assert::eq(
            (new ClosureAggregateTranslator())->extractAggregateId($aggregateRoot),
            $event->aggregateId(),
            'Expected an aggregate id.'
        );

        $producedEvents = $this->closureAggregateTranslator->extractPendingStreamEvents($aggregateRoot);

        Assert::true(
            $this->messageMatcher->isOneOf($event, $producedEvents),
            'Expected one of the aggregate events to match the provided event.'
        );
    }

    public function assertAggregateHasNotProducedEvent($aggregateRoot, AggregateChanged $event): void
    {
        $producedEvents = $this->closureAggregateTranslator->extractPendingStreamEvents($aggregateRoot);

        Assert::false(
            $this->messageMatcher->isOneOf($event, $producedEvents),
            'Not expected one of the aggregate events to match the provided event.'
        );
    }
}
