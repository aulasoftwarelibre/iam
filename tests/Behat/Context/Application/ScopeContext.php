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
use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RemoveScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RenameScope;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasCreated;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRemoved;
use AulaSoftwareLibre\Iam\Domain\Scope\Event\ScopeWasRenamed;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;
use Webmozart\Assert\Assert;

class ScopeContext implements Context
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
     * @var Scopes
     */
    private $scopes;

    public function __construct(
        CommandBus $commandBus,
        EventsRecorder $eventsRecorder,
        Scopes $scopes
    ) {
        $this->commandBus = $commandBus;
        $this->eventsRecorder = $eventsRecorder;
        $this->scopes = $scopes;
    }

    /**
     * @When /^I register an scope with name "([^"]*)" and short name "([^"]*)"$/
     */
    public function iRegisterAnScopeWithNameAndShortname(string $name, string $shortName): void
    {
        $this->commandBus->dispatch(
            CreateScope::with(
                $this->scopes->nextIdentity(),
                Name::fromString($name),
                ShortName::fromString($shortName)
            )
        );
    }

    /**
     * @Then /^the scope "([^"]*)" with name "([^"]*)" should be available$/
     */
    public function theScopeWithNameShouldBeAvailable(string $shortName, string $name): void
    {
        /** @var ScopeWasCreated $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, ScopeWasCreated::class, sprintf(
            'Event has to be of class %s, but %s given',
            ScopeWasCreated::class,
            \get_class($event)
        ));

        Assert::true($event->name()->equals(Name::fromString($name)));
        Assert::true($event->shortName()->equals(ShortName::fromString($shortName)));
    }

    /**
     * @When /^I rename (it) to "([^"]*)"$/
     */
    public function iRenameItTo(ScopeId $scopeId, string $name): void
    {
        $this->commandBus->dispatch(RenameScope::with(
            $scopeId,
            Name::fromString($name)
        ));
    }

    /**
     * @Then /^(it) should be renamed to "([^"]*)"$/
     */
    public function itShouldBeRenamedTo(ScopeId $scopeId, string $name): void
    {
        /** @var ScopeWasRenamed $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, ScopeWasRenamed::class, sprintf(
            'Event has to be of class %s, but %s given',
            ScopeWasRenamed::class,
            \get_class($event)
        ));
        Assert::true($event->scopeId()->equals($scopeId));
        Assert::true($event->name()->equals(Name::fromString($name)));
    }

    /**
     * @When /^I remove (it)$/
     */
    public function iRemoveIt(ScopeId $scopeId)
    {
        $this->commandBus->dispatch(RemoveScope::with(
            $scopeId
        ));
    }

    /**
     * @Then /^(the scope) should not be available$/
     */
    public function theScopeShouldNotBeAvailable(ScopeId $scopeId)
    {
        /** @var ScopeWasRemoved $event */
        $event = $this->eventsRecorder->getLastMessage()->event();

        Assert::isInstanceOf($event, ScopeWasRemoved::class, sprintf(
            'Event has to be of class %s, but %s given',
            ScopeWasRemoved::class,
            \get_class($event)
        ));
        Assert::true($event->scopeId()->equals($scopeId));
    }
}
