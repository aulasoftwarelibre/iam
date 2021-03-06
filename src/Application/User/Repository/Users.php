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

namespace AulaSoftwareLibre\Iam\Application\User\Repository;

use AulaSoftwareLibre\Iam\Domain\User\Model\User;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;

interface Users
{
    public function save(User $user): void;

    public function get(UserId $userId): User;

    public function find(UserId $userId): ?User;

    public function nextIdentity(): UserId;
}
