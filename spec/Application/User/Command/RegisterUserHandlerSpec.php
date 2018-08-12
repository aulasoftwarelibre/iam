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
use AulaSoftwareLibre\Iam\Application\User\Exception\UsernameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tests\Spec\Fixtures;

class RegisterUserHandlerSpec extends ObjectBehavior
{
    public function let(Users $users, UserViews $userViews): void
    {
        $this->beConstructedWith($users, $userViews);

        $users->find(Fixtures\User::USER_ID)->willReturn(null);
        $userViews->ofUsername(Fixtures\User::USERNAME)->willReturn(null);
    }

    public function it_creates_an_user(Users $users): void
    {
        $users->save(Argument::that(
            function (User $user) {
                return $user->userId()->equals(UserId::fromString(Fixtures\User::USER_ID))
                    && $user->username()->equals(Username::fromString(Fixtures\User::USERNAME))
                ;
            }
        ))->shouldBeCalled();

        $this(RegisterUser::with(
            UserId::fromString(Fixtures\User::USER_ID),
            Username::fromString(Fixtures\User::USERNAME)
        ));
    }

    public function it_checks_username_is_free(UserViews $userViews): void
    {
        $userViews->ofUsername(Fixtures\User::USERNAME)->shouldBeCalled()->willReturn(
            new UserView(
                Fixtures\User::USER_ID,
                Fixtures\User::USERNAME
            )
        );

        $this->shouldThrow(UsernameAlreadyRegisteredException::class)->during('__invoke', [RegisterUser::with(
            UserId::fromString(Fixtures\User::USER_ID),
            Username::fromString(Fixtures\User::USERNAME)
        )]);
    }
}
