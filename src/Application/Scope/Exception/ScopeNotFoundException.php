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

class ScopeNotFoundException extends \InvalidArgumentException
{
    public static function withScopeId(ScopeId $scopeId): self
    {
        return new self(sprintf('Scope with id "%s" does not exists', $scopeId->toString()));
    }
}
