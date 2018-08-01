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

namespace Tests\Behat\Context\Setup;

use AulaSoftwareLibre\DDD\TestsBundle\Service\SharedStorage;
use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;

class ScopeContext implements Context
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var SharedStorage
     */
    private $sharedStorage;
    /**
     * @var Scopes
     */
    private $scopes;

    public function __construct(
        CommandBus $commandBus,
        SharedStorage $sharedStorage,
        Scopes $scopes
    ) {
        $this->commandBus = $commandBus;
        $this->sharedStorage = $sharedStorage;
        $this->scopes = $scopes;
    }

    /**
     * @Given /^the scope "([^"]*)" with name "([^"]*)"$/
     */
    public function theScopeWithName(string $shortName, string $name): void
    {
        $scopeId = $this->scopes->nextIdentity();

        $this->commandBus->dispatch(CreateScope::with(
            $scopeId,
            Name::fromString($name),
            ShortName::fromString($shortName)
        ));

        $this->sharedStorage->set('scopeId', $scopeId);
    }
}
