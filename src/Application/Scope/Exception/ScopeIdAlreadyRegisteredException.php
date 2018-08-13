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

use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;

class ScopeIdAlreadyRegisteredException extends \InvalidArgumentException
{
    public static function withScopeId(ScopeId $scopeId): ScopeIdAlreadyRegisteredException
    {
        return new self(sprintf('ScopeId "%s" already taken', $scopeId->toString()));
    }
}
