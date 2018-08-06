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
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;
use React\Promise\Deferred;

class GetRoleHandler
{
    /**
     * @var RoleViews
     */
    private $roleViews;

    public function __construct(RoleViews $roleViews)
    {
        $this->roleViews = $roleViews;
    }

    public function __invoke(GetRole $getRole, Deferred $deferred = null): ?RoleView
    {
        $role = $this->roleViews->get($getRole->roleId()->toString());

        if (null === $deferred) {
            return $role;
        }

        $deferred->resolve($role);

        return null;
    }
}
