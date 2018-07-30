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

namespace Tests\Behat\Context\Api;

use AulaSoftwareLibre\DDD\TestsBundle\Service\HttpClient;
use AulaSoftwareLibre\DDD\TestsBundle\Service\ResponseAsserter;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class ScopeContext implements Context
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var ResponseAsserter
     */
    private $asserter;

    public function __construct(HttpClient $client, ResponseAsserter $asserter)
    {
        $this->client = $client;
        $this->asserter = $asserter;
    }

    /**
     * @When /^I browse the scopes$/
     */
    public function iBrowseTheScopes(): void
    {
        $this->client->get('/api/scopes');
    }

    /**
     * @Then /^I should see the "([^"]*)" scope$/
     */
    public function iShouldSeeTheScope(string $shortName): void
    {
        $expectedContent = sprintf('[{"id":"@string@","name":"@string@","shortName":"%s"}]', $shortName);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }
}
