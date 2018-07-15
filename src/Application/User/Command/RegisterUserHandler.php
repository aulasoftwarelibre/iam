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

namespace AulaSoftwareLibre\Iam\Application\User\Command;

use AulaSoftwareLibre\Iam\Application\User\Exception\EmailAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Exception\UsernameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Infrastructure\Repository\UserViews;

class RegisterUserHandler
{
    /**
     * @var Users
     */
    private $users;
    /**
     * @var UserViews
     */
    private $userViews;

    public function __construct(Users $users, UserViews $userViews)
    {
        $this->users = $users;
        $this->userViews = $userViews;
    }

    public function __invoke(RegisterUser $registerUser): void
    {
        $userId = $registerUser->userId();
        $username = $registerUser->username();
        $email = $registerUser->email();

        if ($this->userViews->findByUsername($username->toString())) {
            throw new UsernameAlreadyRegisteredException();
        }

        if ($this->userViews->findByEmail($email->toString())) {
            throw new EmailAlreadyRegisteredException();
        }

        $user = User::add(
            $userId,
            $username,
            $email
        );

        $this->users->save($user);
    }
}
