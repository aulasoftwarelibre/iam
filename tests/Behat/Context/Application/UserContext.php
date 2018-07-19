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

use AulaSoftwareLibre\DDD\Tests\Service\Prooph\Plugin\EventsRecorder;
use AulaSoftwareLibre\Iam\Application\User\Command\RegisterUser;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Event\UserWasCreated;
use AulaSoftwareLibre\Iam\Domain\User\Model\Email;
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
     * @When /^I register an account with the "([^"]*)" username and "([^"]*)" email$/
     */
    public function iRegisterAnAccountWithTheUsernameAndEmail($username, $email)
    {
        $this->commandBus->dispatch(
            RegisterUser::with(
                $this->users->nextIdentity(),
                Username::fromString($username),
                Email::fromString($email)
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
            get_class($event)
        ));

        Assert::true($event->username()->equals(Username::fromString($username)));
    }
}
