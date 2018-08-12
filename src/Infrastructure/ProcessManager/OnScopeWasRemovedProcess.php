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
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use Prooph\ServiceBus\CommandBus;

final class OnScopeWasRemovedProcess
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var RoleViews
     */
    private $roleViews;

    public function __construct(CommandBus $commandBus, RoleViews $roleViews)
    {
        $this->commandBus = $commandBus;
        $this->roleViews = $roleViews;
    }

    public function __invoke(ScopeWasRemoved $event): void
    {
        $roles = $this->roleViews->ofScopeId($event->scopeId()->toString());

        $commandBus = $this->commandBus;
        array_walk($roles, function (RoleView $roleView) use ($commandBus) {
            $commandBus->dispatch(
                RemoveRole::with(RoleId::fromString($roleView->getId()))
            );
        });
    }
}
