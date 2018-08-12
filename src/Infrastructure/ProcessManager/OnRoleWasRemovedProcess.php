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

use AulaSoftwareLibre\Iam\Application\User\Command\DemoteUser;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;
use Prooph\ServiceBus\CommandBus;

class OnRoleWasRemovedProcess
{
    /**
     * @var GrantViews
     */
    private $grantViews;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(GrantViews $grantViews, CommandBus $commandBus)
    {
        $this->grantViews = $grantViews;
        $this->commandBus = $commandBus;
    }

    public function __invoke(RoleWasRemoved $event): void
    {
        /** @var GrantView[] $grantViews */
        $grantViews = $this->grantViews->findByRoleId($event->roleId()->toString());

        foreach ($grantViews as $grantView) {
            $this->commandBus->dispatch(
                DemoteUser::with(
                    UserId::fromString($grantView->getUserId()),
                    RoleId::fromString($grantView->getRoleId())
                )
            );
        }
    }
}
