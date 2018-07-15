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

use AulaSoftwareLibre\DDD\Infrastructure\ReadModel\SchemaManagerInterface;
use AulaSoftwareLibre\Iam\Infrastructure\Entity\UserView;

interface UserViews extends SchemaManagerInterface
{
    public function add(UserView $userView): void;

    public function get(string $userId): UserView;

    public function findByUsername(string $username): ?UserView;

    public function findByEmail(string $email): ?UserView;
}
