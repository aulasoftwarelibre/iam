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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository;

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\GrantView;

interface GrantViews
{
    public function add(GrantView $grantView): void;

    public function remove(string $userId, string $roleId): void;

    public function findByRoleId(string $roleId): array;
}
