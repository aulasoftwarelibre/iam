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

use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use Behat\Behat\Context\Context;

class RoleContext implements Context
{
    /**
     * @When /^I add a "([^"]*)" to (it)$/
     */
    public function iAddAToIt(string $roleName, ScopeId $scopeId)
    {
        throw new PendingException();
    }
}
