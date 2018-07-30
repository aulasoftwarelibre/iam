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

namespace Tests\Behat\Context\Transform;

use AulaSoftwareLibre\DDD\TestsBundle\Service\SharedStorage;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class UserContext implements Context
{
    /**
     * @var SharedStorage
     */
    private $sharedStorage;

    public function __construct(SharedStorage $sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Then /^the user should be available$/
     */
    public function theUserShouldBeAvailable()
    {
        throw new PendingException();
    }
}
