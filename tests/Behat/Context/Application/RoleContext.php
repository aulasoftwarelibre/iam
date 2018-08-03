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
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;
use Webmozart\Assert\Assert;

class RoleContext implements Context
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
     * @var Roles
     */
    private $roles;

    public function __construct(
        CommandBus $commandBus,
        EventsRecorder $eventsRecorder,
        Roles $roles
    ) {
        $this->commandBus = $commandBus;
        $this->eventsRecorder = $eventsRecorder;
        $this->roles = $roles;
    }

    /**
     * @Then /^the role "([^"]*)" in this scope should be available$/
     */
    public function theRoleInThisScopeShouldBeAvailable(string $roleName)
    {
        /** @var RoleWasAdded $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, RoleWasAdded::class, sprintf(
            'Event has to be of class %s, but %s given',
            RoleWasAdded::class,
            \get_class($event)
        ));

        Assert::true($event->name()->equals(RoleName::fromString($roleName)));
    }
}
