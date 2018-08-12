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

namespace AulaSoftwareLibre\Iam\Infrastructure\Doctrine\Repository;

use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeNotFoundException;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ScopeViewsRepository extends ServiceEntityRepository implements ScopeViews
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScopeView::class);
    }

    public function add(ScopeView $scopeView): void
    {
        $this->_em->persist($scopeView);
        $this->_em->flush();
    }

    public function ofId(string $scopeId): ?ScopeView
    {
        return $this->find($scopeId);
    }

    public function get(string $scopeId): ScopeView
    {
        $scopeView = $this->find($scopeId);

        if (!$scopeView instanceof ScopeView) {
            throw ScopeNotFoundException::withScopeId(ScopeId::fromString($scopeId));
        }

        return $scopeView;
    }

    public function all(): array
    {
        return parent::findAll();
    }

    public function remove(string $scopeId): void
    {
        $scope = $this->get($scopeId);

        if (!$scope instanceof ScopeView) {
            return;
        }

        $this->_em->remove($scope);
        $this->_em->flush();
    }

    public function rename(string $scopeId, string $name): void
    {
        $scopeView = $this->get($scopeId);
        $scopeView->setName($name);

        $this->_em->flush();
    }

    public function ofAlias(string $alias): ?ScopeView
    {
        return $this->findOneBy(['alias' => $alias]);
    }
}
