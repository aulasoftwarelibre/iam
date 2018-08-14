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

namespace AulaSoftwareLibre\Iam\Bundle\IamBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Client
{
    /**
     * @var RequestManager
     */
    private $requestManager;
    /**
     * @var string
     */
    private $host;

    public function __construct(RequestManager $requestManager, string $host)
    {
        $this->requestManager = $requestManager;
        $this->host = $host;
    }

    public function get(string $uri, array $body = []): Response
    {
        $uri = $this->generateUri($uri);

        return $this
            ->requestManager
            ->sendRequest(Request::METHOD_GET, $uri, $body);
    }

    public function post(string $uri, array $body = []): Response
    {
        $uri = $this->generateUri($uri);

        return $this
            ->requestManager
            ->sendRequest(Request::METHOD_POST, $uri, $body);
    }

    public function put(string $uri, array $body = []): Response
    {
        $uri = $this->generateUri($uri);

        return $this
            ->requestManager
            ->sendRequest(Request::METHOD_PUT, $uri, $body);
    }

    public function delete(string $uri): Response
    {
        $uri = $this->generateUri($uri);

        return $this
            ->requestManager
            ->sendRequest(Request::METHOD_DELETE, $uri);
    }

    private function generateUri(string $path, ...$args): string
    {
        return sprintf('%s/'.ltrim($path, '/ '), $this->host, ...$args);
    }
}
