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

use AulaSoftwareLibre\Iam\Application\User\Exception\UserNotFoundException;
use AulaSoftwareLibre\Iam\Application\User\Repository\Users;
use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class UsersEventStore extends AggregateRepository implements Users
{
    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }

    public function get(UserId $userId): ?User
    {
        $user = $this->getAggregateRoot($userId->toString());

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function nextIdentity(): UserId
    {
        return UserId::generate();
    }
}
