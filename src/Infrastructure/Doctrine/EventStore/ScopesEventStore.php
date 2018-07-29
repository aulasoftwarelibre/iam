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

namespace AulaSoftwareLibre\Iam\Infrastructure\Doctrine\EventStore;

use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class ScopesEventStore extends AggregateRepository implements Scopes
{
    public function save(Scope $scope): void
    {
        $this->saveAggregateRoot($scope);
    }

    public function get(ScopeId $scopeId): Scope
    {
        $scope = $this->find($scopeId);

        if (!$scope instanceof Scope) {
            throw ScopeNotFoundException::withScopeId($scopeId);
        }

        return $scope;
    }

    public function find(ScopeId $scopeId): ?Scope
    {
        return $this->getAggregateRoot($scopeId->toString());
    }

    public function nextIdentity(): ScopeId
    {
        return ScopeId::generate();
    }
}
