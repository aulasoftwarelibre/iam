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

namespace Tests\Services;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection\ScopeProjection;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection\ScopeReadModel;
use Prooph\EventStore\Projection\ProjectionManager;

class ScopeReadModelProjector
{
    /**
     * @var ProjectionManager
     */
    private $projectionManager;
    /**
     * @var ScopeReadModel
     */
    private $scopeReadModel;
    /**
     * @var ScopeProjection
     */
    private $scopeProjection;

    public function __construct(ProjectionManager $projectionManager, ScopeReadModel $scopeReadModel, ScopeProjection $scopeProjection)
    {
        $this->projectionManager = $projectionManager;
        $this->scopeReadModel = $scopeReadModel;
        $this->scopeProjection = $scopeProjection;
    }

    /**
     * @param string $name
     *
     * @return \Prooph\EventStore\Projection\ReadModelProjector
     */
    public function projector(string $name): \Prooph\EventStore\Projection\ReadModelProjector
    {
        $projector = $this->projectionManager->createReadModelProjection($name, $this->scopeReadModel);

        return $this->scopeProjection->project($projector);
    }
}
