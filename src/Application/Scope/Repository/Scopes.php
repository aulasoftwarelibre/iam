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

namespace AulaSoftwareLibre\Iam\Application\Scope\Repository;

use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;

interface Scopes
{
    public function save(Scope $scope): void;

    public function get(ScopeId $scopeId): Scope;

    public function find(ScopeId $scopeId): ?Scope;

    public function nextIdentity(): ScopeId;
}
