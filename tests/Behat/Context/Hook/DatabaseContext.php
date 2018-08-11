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

namespace Tests\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Tests\Behat\Repository\RoleViewsInMemoryRepository;
use Tests\Behat\Repository\ScopeViewsInMemoryRepository;
use Tests\Behat\Repository\UserViewsInMemoryRepository;

class DatabaseContext implements Context
{
    /**
     * @var RoleViewsInMemoryRepository
     */
    private $roles;
    /**
     * @var ScopeViewsInMemoryRepository
     */
    private $scopes;
    /**
     * @var UserViewsInMemoryRepository
     */
    private $users;

    public function __construct(
        RoleViewsInMemoryRepository $roles,
        ScopeViewsInMemoryRepository $scopes,
        UserViewsInMemoryRepository $users
    ) {
        $this->roles = $roles;
        $this->scopes = $scopes;
        $this->users = $users;
    }

    /**
     * @AfterScenario
     */
    public function reset()
    {
        $this->roles->reset();
        $this->scopes->reset();
        $this->users->reset();
    }
}
