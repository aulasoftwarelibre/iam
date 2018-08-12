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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\RoleView;

interface RoleViews
{
    public function add(RoleView $roleView): void;

    public function remove(string $roleId): void;

    public function get(string $roleId): RoleView;

    public function ofId(string $roleId): ?RoleView;

    public function all(): array;

    public function ofScopeId(string $scopeId): array;

    public function ofScopeIdAndRoleName(string $scopeId, string $roleName): ?RoleView;
}
