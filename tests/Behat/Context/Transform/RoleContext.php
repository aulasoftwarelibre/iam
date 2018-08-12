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
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use Behat\Behat\Context\Context;

class RoleContext implements Context
{
    /**
     * @var SharedStorage
     */
    private $sharedStorage;
    /**
     * @var Roles
     */
    private $roles;

    public function __construct(SharedStorage $sharedStorage, Roles $roles)
    {
        $this->sharedStorage = $sharedStorage;
        $this->roles = $roles;
    }

    /**
     * @Transform
     */
    public function getRoleIdFromSharedStorage(): RoleId
    {
        return $this->sharedStorage->get('roleId');
    }

    /**
     * @Transform
     */
    public function getRoleFromSharedStorage(): Role
    {
        $roleId = $this->sharedStorage->get('roleId');

        return $this->roles->get($roleId);
    }
}
