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
use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RemoveScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RenameScope;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeAliasAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ScopeDataPersister implements DataPersisterInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var ScopeViews
     */
    private $scopeViews;

    public function __construct(CommandBus $commandBus, ScopeViews $scopeViews)
    {
        $this->commandBus = $commandBus;
        $this->scopeViews = $scopeViews;
    }

    public function supports($data): bool
    {
        return $data instanceof ScopeView;
    }

    /**
     * @param ScopeView $data
     *
     * @throws HttpException|\Exception
     *
     * @return ScopeView
     */
    public function persist($data): ScopeView
    {
        $scopeView = $this->scopeViews->ofId($data->getId());

        if ($scopeView instanceof ScopeView) {
            return $this->update($data);
        }

        try {
            try {
                $this->commandBus->dispatch(
                    CreateScope::with(
                        ScopeId::fromString($data->getId()),
                        ScopeName::fromString($data->getName()),
                        ScopeAlias::fromString($data->getAlias())
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }
        } catch (ScopeIdAlreadyRegisteredException | ScopeAliasAlreadyRegisteredException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return new ScopeView($data->getId(), $data->getName(), $data->getAlias());
    }

    /**
     * @param ScopeView $data
     *
     * @return ScopeView
     */
    public function update($data): ScopeView
    {
        $this->commandBus->dispatch(
            RenameScope::with(
                ScopeId::fromString($data->getId()),
                ScopeName::fromString($data->getName())
            )
        );

        return new ScopeView($data->getId(), $data->getName(), $data->getAlias());
    }

    /**
     * @param ScopeView $data
     */
    public function remove($data): void
    {
        $this->commandBus->dispatch(
            RemoveScope::with(
                ScopeId::fromString($data->getId())
            )
        );
    }
}
