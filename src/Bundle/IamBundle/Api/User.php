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

class User extends AbstractAccessPoint
{
    public function add(string $id, string $username): ResponseFactory
    {
        return $this->client->post('users', compact('id', 'username'));
    }

    public function get(string $id): ResponseFactory
    {
        return $this->client->get('users/'.$id);
    }

    public function promote(string $id, string $roleId): ResponseFactory
    {
        return $this->client->post('users/'.$id.'/roles/'.$roleId);
    }

    public function demote(string $id, string $roleId): ResponseFactory
    {
        return $this->client->delete('users/'.$id.'/roles/'.$roleId);
    }

    public function roles(string $id, string $scopeId): ResponseFactory
    {
        return $this->client->get('users/'.$id.'/scopes/'.$scopeId.'/roles');
    }
}
