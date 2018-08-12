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

namespace AulaSoftwareLibre\Iam\Application\Scope\Exception;

use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleName;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;

class ScopeRoleNameInvalid extends \InvalidArgumentException
{
    public static function withRoleName(ScopeAlias $alias, RoleName $roleName): self
    {
        $prefix = 'ROLE_'.mb_strtoupper($alias->toString());

        return new self(sprintf('Role name %s is not valid, must begin with \'%s\'', $roleName->toString(), $prefix));
    }
}
