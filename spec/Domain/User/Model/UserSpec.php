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

namespace spec\AulaSoftwareLibre\Iam\Domain\User\Model;

use AulaSoftwareLibre\DDD\TestsBundle\Service\Prooph\Spec\AggregateAsserter;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasDemoted;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasPromoted;
use AulaSoftwareLibre\Iam\Domain\User\Model\Email;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;
use Tests\Spec\Fixtures;

class UserSpec extends ObjectBehavior
{
    const USER_ID = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
    const USERNAME = 'johndoe';
    const EMAIL = 'john@doe.com';

    public function let(): void
    {
        $this->beConstructedThrough('add', [
            UserId::fromString(Fixtures\User::USER_ID),
            Username::fromString(Fixtures\User::USERNAME),
            Email::fromString(Fixtures\User::EMAIL),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            UserWasCreated::with(
                UserId::fromString(Fixtures\User::USER_ID),
                Username::fromString(Fixtures\User::USERNAME),
                Email::fromString(Fixtures\User::EMAIL)
            )
        );
    }

    public function it_is_an_aggregate(): void
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    public function it_can_be_a_string(): void
    {
        $this->__toString()->shouldBe(Fixtures\User::USERNAME);
    }

    public function it_has_a_user_id(): void
    {
        $this->userId()->shouldBeLike(UserId::fromString(Fixtures\User::USER_ID));
    }

    public function it_has_an_username(): void
    {
        $this->username()->shouldBeLike(Username::fromString(Fixtures\User::USERNAME));
    }

    public function it_has_an_email(): void
    {
        $this->email()->shouldBeLike(Email::fromString(Fixtures\User::EMAIL));
    }

    public function it_can_promote_user(): void
    {
        $roleId = RoleId::fromString(Fixtures\Role::ROLE_ID);

        $this->promote($roleId);
        $this->hasRole($roleId)->shouldBe(true);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            UserWasPromoted::with(
                UserId::fromString(Fixtures\User::USER_ID),
                RoleId::fromString(Fixtures\Role::ROLE_ID)
            )
        );
    }

    public function it_can_demote_user(): void
    {
        $roleId = RoleId::fromString(Fixtures\Role::ROLE_ID);

        $this->promote($roleId);
        $this->demote($roleId);
        $this->hasRole($roleId)->shouldBe(false);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            UserWasDemoted::with(
                UserId::fromString(Fixtures\User::USER_ID),
                RoleId::fromString(Fixtures\Role::ROLE_ID)
            )
        );
    }

    public function it_cannot_remove_roles_twice(): void
    {
        $this->demote(RoleId::fromString(Fixtures\Role::ROLE_ID));

        (new AggregateAsserter())->assertAggregateHasNotProducedEvent(
            $this->getWrappedObject(),
            UserWasDemoted::with(
                UserId::fromString(Fixtures\User::USER_ID),
                RoleId::fromString(Fixtures\Role::ROLE_ID)
            )
        );
    }
}
