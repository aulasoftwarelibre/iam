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

namespace spec\AulaSoftwareLibre\Iam\Application\User\Query;

use AulaSoftwareLibre\Iam\Application\User\Query\GetUser;
use AulaSoftwareLibre\Iam\Application\User\Query\GetUserHandler;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\UserViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\UserView;
use PhpSpec\ObjectBehavior;
use React\Promise\Deferred;
use Tests\Spec\Fixtures\User;

class GetUserHandlerSpec extends ObjectBehavior
{
    public function let(UserViews $userViews)
    {
        $this->beConstructedWith($userViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetUserHandler::class);
    }

    public function it_gets_user_by_id(UserViews $userViews, UserView $userView)
    {
        $userViews->get(User::USER_ID)->shouldBeCalled()->willReturn($userView);

        $this(GetUser::with(UserId::fromString(User::USER_ID)))->shouldBe($userView);
    }

    public function it_gets_user_by_id_deferred(UserViews $userViews, UserView $userView, Deferred $deferred)
    {
        $userViews->get(User::USER_ID)->shouldBeCalled()->willReturn($userView);
        $deferred->resolve($userView)->shouldBeCalled();

        $this(GetUser::with(UserId::fromString(User::USER_ID)), $deferred);
    }
}
