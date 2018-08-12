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
use AulaSoftwareLibre\Iam\Application\User\Exception\UserIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Exception\UsernameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Exception\MessageDispatchException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            try {
                $this->commandBus->dispatch(
                    RegisterUser::with(
                        UserId::fromString($data->getId()),
                        Username::fromString($data->getUsername())
                    )
                );
            } catch (MessageDispatchException $e) {
                throw $e->getPrevious();
            }
        } catch (UserIdAlreadyRegisteredException | UsernameAlreadyRegisteredException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $data;
    }

    public function remove($data)
    {
        throw new HttpException(Response::HTTP_NOT_IMPLEMENTED);
    }
}
