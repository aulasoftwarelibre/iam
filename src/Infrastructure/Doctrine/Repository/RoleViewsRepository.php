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

use AulaSoftwareLibre\DDD\Infrastructure\Doctrine\SchemaManagerORMTrait;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository\RoleViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RoleViewsRepository extends ServiceEntityRepository implements RoleViews
{
    use SchemaManagerORMTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleView::class);
    }

    public function add(RoleView $roleView): void
    {
        $this->_em->persist($roleView);
        $this->_em->flush();
    }

    public function get(string $roleId): ?RoleView
    {
        return $this->find($roleId);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function remove(string $roleId): void
    {
        $role = $this->get($roleId);

        if (!$role instanceof RoleView) {
            return;
        }

        $this->_em->remove($role);
        $this->_em->flush();
    }

    public function describe(string $roleId, ?string $description): void
    {
        if (!$role = $this->get($roleId)) {
            return;
        }

        $role->setDescription($description);
        $this->_em->flush();
    }

    public function findByScopeId(string $scopeId): array
    {
        return $this->findBy(['scopeId' => $scopeId]);
    }

    public function findOneByRoleName(string $scopeId, string $roleName): ?RoleView
    {
        return $this->findOneBy(['scopeId' => $scopeId, 'name' => $roleName]);
    }
}