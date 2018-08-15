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

namespace AulaSoftwareLibre\Iam\Application\Role\Command;

use AulaSoftwareLibre\DDD\BaseBundle\Handlers\CommandHandler;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Role\Exception\RoleNameAlreadyExistsException;
use AulaSoftwareLibre\Iam\Application\Role\Repository\Roles;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\RoleViews;

final class AddRoleHandler implements CommandHandler
{
    /**
     * @var Roles
     */
    private $roles;
    /**
     * @var RoleViews
     */
    private $roleViews;
    /**
     * @var Scopes
     */
    private $scopes;

    public function __construct(Roles $roles, RoleViews $roleViews, Scopes $scopes)
    {
        $this->roles = $roles;
        $this->roleViews = $roleViews;
        $this->scopes = $scopes;
    }

    public function __invoke(AddRole $addRole)
    {
        $roleId = $addRole->roleId();
        $scopeId = $addRole->scopeId();
        $roleName = $addRole->name();

        if ($this->roles->find($roleId)) {
            throw RoleIdAlreadyRegisteredException::withRoleId($roleId);
        }

        $scope = $this->scopes->find($scopeId);
        if (!$scope instanceof Scope) {
            throw ScopeNotFoundException::withScopeId($scopeId);
        }

        if ($this->roleViews->ofScopeIdAndRoleName($scopeId->toString(), $roleName->toString())) {
            throw RoleNameAlreadyExistsException::withScopeAliasAndRoleName($scope->alias(), $roleName);
        }

        $role = $scope->addRole($roleId, $roleName);

        $this->roles->save($role);
    }
}
