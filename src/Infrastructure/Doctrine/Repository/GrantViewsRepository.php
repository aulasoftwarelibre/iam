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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\GrantViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GrantViewsRepository extends ServiceEntityRepository implements GrantViews
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrantView::class);
    }

    public function add(GrantView $grantView): void
    {
        $this->_em->persist($grantView);
        $this->_em->flush();
    }

    public function remove(string $userId, string $roleId): void
    {
        $grant = $this->findOneBy(['userId' => $userId, 'roleId' => $roleId]);

        if (!$grant instanceof GrantView) {
            return;
        }

        $this->_em->remove($grant);
        $this->_em->flush();
    }

    public function ofRoleId(string $roleId): array
    {
        return $this->findBy(['roleId' => $roleId]);
    }

    public function distinctUsersOfScopeId(string $scopeId): array
    {
        $qb = $this->createQueryBuilder('g');
        $qb
            ->select('g.userId')
            ->distinct(true)
            ->addSelect('g.username')
            ->where($qb->expr()->eq('g.scopeId', ':scopeId'))
            ->setParameter('scopeId', $scopeId)
        ;

        return $qb->getQuery()->getArrayResult();
    }

    public function ofScopeIdAndUserId(string $scopeId, string $userId): array
    {
        return $this->findBy([
            'scopeId' => $scopeId,
            'userId' => $userId,
        ]);
    }
}
