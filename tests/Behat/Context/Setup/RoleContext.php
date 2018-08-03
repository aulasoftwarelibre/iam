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
use AulaSoftwareLibre\Iam\Application\Role\Command\AddRole;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use Behat\Behat\Context\Context;
use Prooph\ServiceBus\CommandBus;

class RoleContext implements Context
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
     * @var Roles
     */
    private $roles;

    public function __construct(
        CommandBus $commandBus,
        SharedStorage $sharedStorage,
        Roles $roles
    ) {
        $this->commandBus = $commandBus;
        $this->sharedStorage = $sharedStorage;
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

        $this->sharedStorage->set('roleId', $roleId);
    }
}
