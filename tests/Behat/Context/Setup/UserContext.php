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

namespace Tests\Behat\Context\Setup;

use AulaSoftwareLibre\DDD\TestsBundle\Service\SharedStorage;
use AulaSoftwareLibre\Iam\Application\User\Command\PromoteUser;
use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;

class UserContext implements Context
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var SharedStorage
     */
    private $sharedStorage;
    /**
     * @var Users
     */
    private $users;

    public function __construct(
        CommandBus $commandBus,
        SharedStorage $sharedStorage,
        Users $users
    ) {
        $this->commandBus = $commandBus;
        $this->sharedStorage = $sharedStorage;
        $this->users = $users;
    }

    /**
     * @Given /^an account with username "([^"]*)"$/
     */
    public function anAccountWithUsername(string $username)
    {
        $userId = $this->users->nextIdentity();

        $this->commandBus->dispatch(
            RegisterUser::with(
                $userId,
                Username::fromString($username)
            )
        );

        $this->sharedStorage->set('userId', $userId);

        return $userId;
    }

    /**
     * @Given /^an account with username "([^"]*)" and (this role)$/
     */
    public function anAccountWithUsernameAndThisRole(string $username, RoleId $roleId)
    {
        $userId = $this->anAccountWithUsername($username);

        $this->commandBus->dispatch(
            PromoteUser::with(
                $userId,
                $roleId
            )
        );
    }
}
