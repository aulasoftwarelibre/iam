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

namespace AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\Repository;

use AulaSoftwareLibre\DDD\Infrastructure\ReadModel\SchemaManagerInterface;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Role\View\RoleView;

interface RoleViews extends SchemaManagerInterface
{
    public function add(RoleView $roleView): void;

    public function remove(string $roleId): void;

    public function describe(string $roleId, ?string $description): void;

    public function get(string $roleId): ?RoleView;

    public function findAll(): array;

    public function findByScopeId(string $scopeId): array;

    public function findOneByRoleName(string $scopeId, string $roleName): ?RoleView;
}
