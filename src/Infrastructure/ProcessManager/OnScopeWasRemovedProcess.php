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

namespace AulaSoftwareLibre\Iam\Infrastructure\ProcessManager;

use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRole;
use AulaSoftwareLibre\Iam\Application\Role\Query\GetRoles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;

final class OnScopeWasRemovedProcess
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(ScopeWasRemoved $event): void
    {
        $promise = $this->queryBus->dispatch(
            GetRoles::with($event->scopeId())
        );

        $commandBus = $this->commandBus;
        $promise->then(function ($result) use ($commandBus) {
            /** @var RoleView $role */
            foreach ($result as $role) {
                $commandBus->dispatch(
                    RemoveRole::with(RoleId::fromString($role->getId()))
                );
            }
        });
    }
}
