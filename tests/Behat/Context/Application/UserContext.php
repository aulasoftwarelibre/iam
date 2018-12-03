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

namespace Tests\Behat\Context\Application;

use AulaSoftwareLibre\DDD\TestsBundle\Service\Prooph\Plugin\EventsRecorder;
use AulaSoftwareLibre\Iam\Application\User\Command\DemoteUser;
use AulaSoftwareLibre\Iam\Application\User\Command\PromoteUser;
use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasDemoted;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasPromoted;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use AulaSoftwareLibre\Iam\Domain\User\Model\Username;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;
use Webmozart\Assert\Assert;

class UserContext implements Context
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var EventsRecorder
     */
    private $eventsRecorder;
    /**
     * @var Users
     */
    private $users;

    public function __construct(
        CommandBus $commandBus,
        EventsRecorder $eventsRecorder,
        Users $users
    ) {
        $this->commandBus = $commandBus;
        $this->eventsRecorder = $eventsRecorder;
        $this->users = $users;
    }

    /**
     * @When /^I register an account with username "([^"]*)"$/
     */
    public function iRegisterAnAccountWithUsername($username)
    {
        $this->commandBus->dispatch(
            RegisterUser::with(
                $this->users->nextIdentity(),
                Username::fromString($username)
            )
        );
    }

    /**
     * @Then /^the user "([^"]*)" should be available$/
     */
    public function theUserShouldBeAvailable($username)
    {
        /** @var UserWasCreated $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, UserWasCreated::class, sprintf(
            'Event has to be of class %s, but %s given',
            UserWasCreated::class,
            \get_class($event)
        ));

        Assert::true($event->username()->equals(Username::fromString($username)));
    }

    /**
     * @When /^I assign (the role) to (the user)$/
     */
    public function iAssignTheRoleToTheUser(RoleId $roleId, UserId $userId)
    {
        $this->commandBus->dispatch(
            PromoteUser::with(
                $userId,
                $roleId
            )
        );
    }

    /**
     * @Then /^I should see that (the user) has (the role)$/
     */
    public function iShouldSeeThatTheUserHasTheRole(UserId $userId, RoleId $roleId)
    {
        /** @var UserWasPromoted $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, UserWasPromoted::class, sprintf(
            'Event has to be of class %s, but %s given',
            UserWasPromoted::class,
            \get_class($event)
        ));

        Assert::true($event->userId()->equals($userId));
        Assert::true($event->roleId()->equals($roleId));
    }

    /**
     * @When /^I remove (the role) to (the user)$/
     */
    public function iRemoveTheRoleToTheUser(RoleId $roleId, UserId $userId)
    {
        $this->commandBus->dispatch(
            DemoteUser::with(
                $userId,
                $roleId
            )
        );
    }

    /**
     * @Then /^I shouldn't see that (the user) has (the role)$/
     */
    public function iShouldnTSeeThatTheUserHasTheRole(UserId $userId, RoleId $roleId)
    {
        /** @var UserWasDemoted $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, UserWasDemoted::class, sprintf(
            'Event has to be of class %s, but %s given',
            UserWasDemoted::class,
            \get_class($event)
        ));

        Assert::true($event->userId()->equals($userId));
        Assert::true($event->roleId()->equals($roleId));
    }
}
