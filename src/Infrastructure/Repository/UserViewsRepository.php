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

namespace AulaSoftwareLibre\Iam\Infrastructure\Repository;

use AulaSoftwareLibre\DDD\Infrastructure\Doctrine\SchemaManagerORMTrait;
use AulaSoftwareLibre\Iam\Infrastructure\Entity\UserView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserViewsRepository extends ServiceEntityRepository implements UserViews
{
    use SchemaManagerORMTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserView::class);
    }

    public function add(UserView $userView): void
    {
        $this->_em->persist($userView);
        $this->_em->flush();
    }

    public function get(string $userId): UserView
    {
        return $this->find($userId);
    }

    public function findByUsername(string $username): ?UserView
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ?UserView
    {
        return $this->findOneBy(['email' => $email]);
    }
}
