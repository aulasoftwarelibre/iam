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

use Symfony\Component\HttpFoundation\Response;

final class Scope extends AbstractAccessPoint
{
    public function all(): Response
    {
        return $this->client->get('scopes');
    }

    public function add(string $id, string $name, string $alias): Response
    {
        return $this->client->post('scopes', compact('id', 'name', 'alias'));
    }

    public function get(string $id): Response
    {
        return $this->client->get('scopes/'.$id);
    }

    public function delete(string $id): Response
    {
        return $this->client->delete('scopes/'.$id);
    }

    public function rename(string $id, string $name): Response
    {
        return $this->client->put('scopes/'.$id, compact('id', 'name'));
    }

    public function roles(string $id): Response
    {
        return $this->client->get('scopes/'.$id.'/roles');
    }

    public function users(string $id): Response
    {
        return $this->client->get('scopes/'.$id.'/users');
    }
}
