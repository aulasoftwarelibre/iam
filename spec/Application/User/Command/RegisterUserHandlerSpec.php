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

namespace spec\App\Application\User\Command;

use App\Application\User\Command\RegisterUser;
use App\Application\User\Repository\Users;
use App\Domain\User\Model\Email;
use App\Domain\User\Model\User;
use App\Domain\User\Model\UserId;
use App\Domain\User\Model\Username;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegisterUserHandlerSpec extends ObjectBehavior
{
    const USER_ID = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
    const USERNAME = 'johndoe';
    const EMAIL = 'john@doe.com';

    public function let(Users $users): void
    {
        $this->beConstructedWith($users);
    }

    public function it_creates_an_user(Users $users): void
    {
        $users->save(Argument::that(
            function (User $user) {
                return $user->userId()->equals(UserId::fromString(self::USER_ID))
                    && $user->username()->equals(Username::fromString(self::USERNAME))
                    && $user->email()->equals(Email::fromString(self::EMAIL))
                ;
            }
        ))->shouldBeCalled();

        $this(RegisterUser::with(
            UserId::fromString(self::USER_ID),
            Username::fromString(self::USERNAME),
            Email::fromString(self::EMAIL)
        ));
    }
}
