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

namespace AulaSoftwareLibre\Iam\Application\Role\Exception;

use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;

class RoleIdAlreadyRegisteredException extends \InvalidArgumentException
{
    public static function withRoleId(RoleId $roleId): RoleIdAlreadyRegisteredException
    {
        return new self(sprintf('RoleId "%s" already taken', $roleId->toString()));
    }
}
