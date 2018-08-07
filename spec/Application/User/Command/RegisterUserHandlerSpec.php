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

namespace spec\AulaSoftwareLibre\Iam\Application\User\Command;

use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Application\User\Exception\EmailAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Exception\UsernameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\Email;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\User\View\UserView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegisterUserHandlerSpec extends ObjectBehavior
{
    const USER_ID = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
    const USERNAME = 'johndoe';
    const EMAIL = 'john@doe.com';

    public function let(Users $users, UserViews $userViews): void
    {
        $this->beConstructedWith($users, $userViews);

        $users->find(self::USER_ID)->willReturn(null);
        $userViews->findByUsername(self::USERNAME)->willReturn(null);
        $userViews->findByEmail(self::EMAIL)->willReturn(null);
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

    public function it_checks_username_is_free(UserViews $userViews): void
    {
        $userViews->findByUsername(self::USERNAME)->shouldBeCalled()->willReturn(
            new UserView(self::USER_ID, self::USERNAME, self::EMAIL)
        );

        $this->shouldThrow(UsernameAlreadyRegisteredException::class)->during('__invoke', [RegisterUser::with(
            UserId::fromString(self::USER_ID),
            Username::fromString(self::USERNAME),
            Email::fromString(self::EMAIL)
        )]);
    }

    public function it_checks_email_is_free(UserViews $userViews): void
    {
        $userViews->findByEmail(self::EMAIL)->shouldBeCalled()->willReturn(
            new UserView(self::USER_ID, self::EMAIL, self::EMAIL)
        );

        $this->shouldThrow(EmailAlreadyRegisteredException::class)->during('__invoke', [RegisterUser::with(
            UserId::fromString(self::USER_ID),
            Username::fromString(self::USERNAME),
            Email::fromString(self::EMAIL)
        )]);
    }
}
