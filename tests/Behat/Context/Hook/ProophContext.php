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

namespace Tests\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Tests\Services\ScopeReadModelProjector;

class ProophContext implements Context
{
    /**
     * @var EventStore
     */
    private $eventStore;
    private $projector;

    public function __construct(
        EventStore $eventStore,
        ScopeReadModelProjector $scopeReadModelProjector
    ) {
        $this->eventStore = $eventStore;
        $this->projector = $scopeReadModelProjector->projector('scope_projection');
    }

    /**
     * @BeforeScenario
     */
    public function createStream(): void
    {
        $this->eventStore->create(new Stream(new StreamName('event_stream'), new \ArrayIterator([])));
        $this->projector->reset();
    }

    /**
     * @BeforeStep
     */
    public function runProjection(BeforeStepScope $scope): void
    {
        if (!$scope->getFeature()->hasTag('api')) {
            return;
        }

        $this->projector->run(false);
    }
}
