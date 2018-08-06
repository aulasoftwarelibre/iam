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

namespace AulaSoftwareLibre\Iam\Application\Role\Query;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository\RoleViews;
use React\Promise\Deferred;

class GetRolesHandler
{
    /**
     * @var RoleViews
     */
    private $roleViews;

    public function __construct(RoleViews $roleViews)
    {
        $this->roleViews = $roleViews;
    }

    public function __invoke(GetRoles $getRoles, Deferred $deferred = null): ?array
    {
        $roles = $this->roleViews->findByScopeId($getRoles->scopeId()->toString());

        if (null === $deferred) {
            return $roles;
        }

        $deferred->resolve($roles);

        return null;
    }
}
