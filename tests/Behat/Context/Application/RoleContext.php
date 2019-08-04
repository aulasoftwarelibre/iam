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
use AulaSoftwareLibre\Iam\Application\Role\Command\AddRole;
use AulaSoftwareLibre\Iam\Application\Role\Command\RemoveRole;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasAdded;
use AulaSoftwareLibre\Iam\Domain\Role\Event\RoleWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use Behat\Behat\Context\Context;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

class RoleContext implements Context
{
    /**
     * @var MessageBusInterface
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
        MessageBusInterface $commandBus,
        EventsRecorder $eventsRecorder,
        Roles $roles
    ) {
        $this->commandBus = $commandBus;
        $this->eventsRecorder = $eventsRecorder;
        $this->roles = $roles;
    }

    /**
     * @When /^I add a "([^"]*)" to (it)$/
     */
    public function iAddAToIt(string $roleName, ScopeId $scopeId)
    {
        $roleId = $this->roles->nextIdentity();

        $this->commandBus->dispatch(AddRole::with(
            $roleId,
            $scopeId,
            RoleName::fromString($roleName)
        ));
    }

    /**
     * @Then /^the role "([^"]*)" in (this scope) should be available$/
     */
    public function theRoleInThisScopeShouldBeAvailable(string $roleName, ScopeId $scopeId)
    {
        /** @var RoleWasAdded $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, RoleWasAdded::class, sprintf(
            'Event has to be of class %s, but %s given',
            RoleWasAdded::class,
            \get_class($event)
        ));

        Assert::true($event->name()->equals(RoleName::fromString($roleName)));
        Assert::true($event->scopeId()->equals($scopeId));
    }

    /**
     * @When /^I remove (the role)$/
     */
    public function iRemoveIt(RoleId $roleId)
    {
        $this->commandBus->dispatch(RemoveRole::with(
            $roleId
        ));
    }

    /**
     * @Then /^(the role) should not be available$/
     */
    public function theRoleShouldNotBeAvailable(RoleId $roleId)
    {
        /** @var RoleWasRemoved $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, RoleWasRemoved::class, sprintf(
            'Event has to be of class %s, but %s given',
            RoleWasRemoved::class,
            \get_class($event)
        ));
        Assert::true($event->roleId()->equals($roleId));
    }
}
