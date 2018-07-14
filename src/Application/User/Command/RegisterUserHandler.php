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

use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;

class RegisterUserHandler
{
    /**
     * @var Users
     */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(RegisterUser $registerUser): void
    {
        $user = User::add(
            $registerUser->userId(),
            $registerUser->username(),
            $registerUser->email()
        );

        $this->users->save($user);
    }
}
