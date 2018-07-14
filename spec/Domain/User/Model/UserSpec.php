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

namespace spec\App\Domain\User\Model;

use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Model\Email;
use App\Domain\User\Model\UserId;
use App\Domain\User\Model\Username;
use AulaSoftwareLibre\DDD\Service\Prooph\Spec\AggregateAsserter;
use PhpSpec\ObjectBehavior;
use Prooph\EventSourcing\AggregateRoot;

class UserSpec extends ObjectBehavior
{
    const USER_ID = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
    const USERNAME = 'johndoe';
    const EMAIL = 'john@doe.com';

    public function let(): void
    {
        $this->beConstructedThrough('add', [
            UserId::fromString(self::USER_ID),
            Username::fromString(self::USERNAME),
            Email::fromString(self::EMAIL),
        ]);

        (new AggregateAsserter())->assertAggregateHasProducedEvent(
            $this->getWrappedObject(),
            UserWasCreated::with(
                UserId::fromString(self::USER_ID),
                Username::fromString(self::USERNAME),
                Email::fromString(self::EMAIL)
            )
        );
    }

    public function it_is_an_aggregate(): void
    {
        $this->shouldHaveType(AggregateRoot::class);
    }

    public function it_can_be_a_string(): void
    {
        $this->__toString()->shouldBe(self::USERNAME);
    }

    public function it_has_a_user_id(): void
    {
        $this->userId()->shouldBeLike(UserId::fromString(self::USER_ID));
    }

    public function it_has_an_username(): void
    {
        $this->username()->shouldBeLike(Username::fromString(self::USERNAME));
    }

    public function it_has_an_email(): void
    {
        $this->email()->shouldBeLike(Email::fromString(self::EMAIL));
    }
}
