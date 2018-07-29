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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;

class ScopeViewInMemoryRepository extends AbstractInMemoryRepository implements ScopeViews
{
    public function add(ScopeView $scopeView): void
    {
        $this->_add($scopeView->getId(), $scopeView->getName());
    }

    public function get(string $scopeId): ScopeView
    {
        return $this->_get($scopeId);
    }

    public function rename(string $scopeId, string $name): void
    {
        /** @var ScopeView $scopeView */
        $scopeView = $this->get($scopeId);
        $scopeView->rename($name);

        $this->_add($scopeId, $scopeView);
    }

    public function findByName(string $name): ?ScopeView
    {
        return $this->findBy('getName', $name);
    }

    public function findByShortName(string $shortName): ?ScopeView
    {
        return $this->findBy('getShortName', $shortName);
    }
}
