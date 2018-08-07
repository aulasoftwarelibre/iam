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

use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;

interface ScopeViews
{
    public function add(ScopeView $scopeView): void;

    public function remove(string $scopeId): void;

    public function rename(string $scopeId, string $name): void;

    public function get(string $scopeId): ?ScopeView;

    public function findAll(): array;

    public function findOneByShortName(string $shortName): ?ScopeView;
}
