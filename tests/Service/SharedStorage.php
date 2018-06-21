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

namespace Tests\Service;

interface SharedStorage
{
    public function get(string $key);

    public function has(string $key): bool;

    public function set(string $key, $resource): void;

    public function getLatestResource();
}
