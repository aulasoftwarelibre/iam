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
use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Domain\User\Model\Email;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\User\View\UserView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\CommandDispatchException;

class UserDataPersister implements DataPersisterInterface
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
        return $data instanceof UserView;
    }

    /**
     * @param UserView $data
     *
     * @throws \Exception
     *
     * @return UserView
     */
    public function persist($data)
    {
        try {
            $this->commandBus->dispatch(
                RegisterUser::with(
                    UserId::fromString($data->id()),
                    Username::fromString($data->username()),
                    Email::fromString($data->email())
                )
            );
        } catch (CommandDispatchException $e) {
            throw $e->getPrevious();
        }

        return $data;
    }

    public function remove($data)
    {
        // TODO: Implement remove() method.
    }
}
