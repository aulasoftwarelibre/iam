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
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;
use Prooph\ServiceBus\CommandBus;

class ScopeDataPersister implements DataPersisterInterface
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
        return $data instanceof ScopeView;
    }

    /**
     * @param ScopeView $data
     *
     * @return ScopeView
     */
    public function persist($data): ScopeView
    {
        $this->commandBus->dispatch(
            CreateScope::with(
                ScopeId::fromString($data->getId()),
                Name::fromString($data->getName()),
                ShortName::fromString($data->getShortName())
            )
        );

        return $data;
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
