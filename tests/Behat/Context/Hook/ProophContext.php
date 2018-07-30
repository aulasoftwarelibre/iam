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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection\ScopeProjection;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection\ScopeReadModel;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Prooph\EventStore\InMemoryEventStore;
use Prooph\EventStore\Projection\ProjectionManager;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;

class ProophContext implements Context
{
    /**
     * @var ScopeProjection
     */
    private $scopeProjection;
    /**
     * @var ProjectionManager
     */
    private $projectionManager;
    /**
     * @var ScopeReadModel
     */
    private $scopeReadModel;
    /**
     * @var InMemoryEventStore
     */
    private $inMemoryEventStore;

    public function __construct(
        InMemoryEventStore $inMemoryEventStore,
        ProjectionManager $projectionManager,
        ScopeReadModel $scopeReadModel,
        ScopeProjection $scopeProjection
    ) {
        $this->scopeProjection = $scopeProjection;
        $this->projectionManager = $projectionManager;
        $this->scopeReadModel = $scopeReadModel;
        $this->inMemoryEventStore = $inMemoryEventStore;
    }

    /**
     * @BeforeScenario
     */
    public function createStream(): void
    {
        $this->inMemoryEventStore->create(new Stream(new StreamName('event_stream'), new \ArrayIterator([])));
    }

    /**
     * @AfterStep
     */
    public function runProjection(AfterStepScope $scope): void
    {
        if (!$scope->getFeature()->hasTag('api')) {
            return;
        }

        $projector = $this->projectionManager->createReadModelProjection('scope_projection', $this->scopeReadModel);
        $this->scopeProjection->project($projector)->run(false);
    }
}
