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

namespace Tests\Behat\Repository;

use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;

class ScopeViewsInMemoryRepository extends AbstractInMemoryRepository implements ScopeViews
{
    protected static $stack = [];

    public function add(ScopeView $scopeView): void
    {
        $this->_add($scopeView->getId(), $scopeView);
    }

    public function get(string $scopeId): ScopeView
    {
        $scope = $this->_ofId($scopeId);

        if (!$scope instanceof ScopeView) {
            throw ScopeNotFoundException::withScopeId(ScopeId::fromString($scopeId));
        }

        return $scope;
    }

    public function ofId(string $scopeId): ?ScopeView
    {
        return $this->_ofId($scopeId);
    }

    public function remove(string $scopeId): void
    {
        $this->_remove($scopeId);
    }

    public function rename(string $scopeId, string $name): void
    {
        /** @var ScopeView $scopeView */
        $scopeView = $this->get($scopeId);
        $scopeView->setName($name);

        $this->_add($scopeId, $scopeView);
    }

    public function ofAlias(string $alias): ?ScopeView
    {
        return $this->findOneBy('getAlias', $alias);
    }
}
