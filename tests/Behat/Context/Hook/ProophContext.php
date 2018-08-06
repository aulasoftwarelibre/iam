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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Projection\RoleReadModel;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Projection\ScopeReadModel;
use Behat\Behat\Context\Context;
use Prooph\EventStore\Projection\ProjectionManager;

class ProophContext implements Context
{
    private $runner;
    /**
     * @var ScopeReadModel
     */
    private $scopeReadModel;
    /**
     * @var RoleReadModel
     */
    private $roleReadModel;

    public function __construct(
        ProjectionManager $projectionManager,
        ScopeReadModel $scopeReadModel,
        RoleReadModel $roleReadModel
    ) {
        $this->runner = $projectionManager
            ->createProjection('$all')
            ->fromAll()
            ->whenAny(function ($state, $event) use ($scopeReadModel, $roleReadModel): void {
                $scopeReadModel->stack('apply', $event);
                $roleReadModel->stack('apply', $event);
            })
        ;
        $this->scopeReadModel = $scopeReadModel;
        $this->roleReadModel = $roleReadModel;
    }

    /**
     * @AfterStep
     */
    public function runProjection(): void
    {
        $this->runner->run(false);
        $this->scopeReadModel->persist();
        $this->roleReadModel->persist();
    }
}
