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

namespace AulaSoftwareLibre\Iam\Infrastructure\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use AulaSoftwareLibre\Iam\Application\Role\Command\AddRole;
use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRole;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;
use Prooph\ServiceBus\CommandBus;

class RoleDataPersister implements DataPersisterInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function supports($data): bool
    {
        return $data instanceof RoleView;
    }

    /**
     * @param RoleView $data
     *
     * @return RoleView
     */
    public function persist($data): RoleView
    {
        $this->commandBus->dispatch(
            AddRole::with(
                RoleId::fromString($data->getId()),
                ScopeId::fromString($data->getScopeId()),
                RoleName::fromString($data->getName())
            )
        );

        return $data;
    }

    /**
     * @param RoleView $data
     */
    public function remove($data): void
    {
        $this->commandBus->dispatch(
            RemoveRole::with(
                RoleId::fromString($data->getId())
            )
        );
    }
}
