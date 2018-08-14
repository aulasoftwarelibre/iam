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

namespace AulaSoftwareLibre\Iam\Bundle\IamBundle\Api;

use AulaSoftwareLibre\Iam\Bundle\IamBundle\Http\ResponseFactory;

final class Role extends AbstractAccessPoint
{
    public function add(string $id, string $scopeId, string $name): ResponseFactory
    {
        return $this->client->post('roles', compact('id', 'scopeId', 'name'));
    }

    public function get(string $id): ResponseFactory
    {
        return $this->client->get('roles/'.$id);
    }

    public function delete(string $id): ResponseFactory
    {
        return $this->client->delete('roles/'.$id);
    }
}
