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

use AulaSoftwareLibre\Iam\Bundle\IamBundle\Services\Client;

class IamApiFacade
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Scope
     */
    private $scope;
    /**
     * @var Role
     */
    private $role;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function scope(): Scope
    {
        if (!$this->scope) {
            $this->scope = new Scope($this->client);
        }

        return $this->scope;
    }

    public function role(): Role
    {
        if (!$this->role) {
            $this->role = new Role($this->client);
        }

        return $this->role;
    }
}
