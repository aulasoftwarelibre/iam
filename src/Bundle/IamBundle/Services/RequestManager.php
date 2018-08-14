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

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response;

final class RequestManager
{
    /**
     * @var HttpClient
     */
    private $httpClient;
    /**
     * @var MessageFactory
     */
    private $messageFactory;
    /**
     * @var HttpFoundationFactory
     */
    private $httpFoundationFactory;

    public function __construct()
    {
        $this->httpClient = HttpClientDiscovery::find();
        $this->messageFactory = MessageFactoryDiscovery::find();
        $this->httpFoundationFactory = new HttpFoundationFactory();
    }

    public function sendRequest(string $method, string $uri, array $body = []): Response
    {
        $headers['accept'] = 'application/json';
        $headers['content-type'] = 'application/ld+json';

        $request = $this->messageFactory->createRequest($method, $uri, $headers, json_encode($body));
        $response = $this->httpClient->sendRequest($request);

        return $this->httpFoundationFactory->createResponse($response);
    }
}
