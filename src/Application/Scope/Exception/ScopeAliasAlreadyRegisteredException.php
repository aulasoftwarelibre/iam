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

use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;

class ScopeAliasAlreadyRegisteredException extends \InvalidArgumentException
{
    public static function withAlias(ScopeAlias $alias): ScopeAliasAlreadyRegisteredException
    {
        return new self(sprintf('Scope alias %s already taken', $alias->toString()));
    }
}
