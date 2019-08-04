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
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use Behat\Behat\Context\Context;
use Symfony\Component\Messenger\MessageBusInterface;

class ScopeContext implements Context
{
    /**
     * @var MessageBusInterface
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
        MessageBusInterface $commandBus,
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
    public function theScopeWithName(string $alias, string $name): void
    {
        $scopeId = $this->scopes->nextIdentity();

        $this->commandBus->dispatch(CreateScope::with(
            $scopeId,
            ScopeName::fromString($name),
            ScopeAlias::fromString($alias)
        ));

        $this->sharedStorage->set('scopeId', $scopeId);
    }
}
