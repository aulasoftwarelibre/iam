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
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNameAlreadyExistsException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeRoleNameInvalid;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @throws \Exception|HttpException
     *
     * @return RoleView
     */
    public function persist($data): RoleView
    {
        try {
            try {
                $this->commandBus->dispatch(
                    AddRole::with(
                        RoleId::fromString($data->getId()),
                        ScopeId::fromString($data->getScopeId()),
                        RoleName::fromString($data->getName())
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }
        } catch (
            \InvalidArgumentException |
            RoleIdAlreadyRegisteredException |
            RoleNameAlreadyExistsException |
            ScopeNotFoundException |
            ScopeRoleNameInvalid $e
        ) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }

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
