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

namespace AulaSoftwareLibre\Iam\Infrastructure\Controller;

use AulaSoftwareLibre\Iam\Application\User\Command\PromoteUser;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use Prooph\ServiceBus\CommandBus;

class UserPromote
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(string $id, string $roleId)
    {
        $this->commandBus->dispatch(
            PromoteUser::with(
                UserId::fromString($id),
                RoleId::fromString($roleId)
            )
        );
    }
}
