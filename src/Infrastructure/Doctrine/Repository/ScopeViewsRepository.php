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

    public function get(string $scopeId): ?ScopeView
    {
        return $this->find($scopeId);
    }

    public function findAll(): array
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

    public function findOneByShortName(string $shortName): ?ScopeView
    {
        return $this->findOneBy(['shortName' => $shortName]);
    }
}
