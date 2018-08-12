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
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
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
     * @When /^I register an scope with name "([^"]*)" and alias "([^"]*)"$/
     */
    public function iRegisterAnScopeWithNameAndAlias(string $name, string $alias): void
    {
        $this->client->post('/api/scopes', [], [
            'id' => ScopeId::generate()->toString(),
            'name' => $name,
            'alias' => $alias,
        ]);
    }

    /**
     * @When /^I check (its) details$/
     */
    public function iCheckItsDetails(ScopeId $scopeId)
    {
        $this->client->get('/api/scopes/'.$scopeId->toString());
    }

    /**
     * @When /^I remove (it)$/
     * @When /^I remove (it) again$/
     */
    public function iRemoveIt(ScopeId $scopeId)
    {
        $this->client->delete('/api/scopes/'.$scopeId->toString());
    }

    /**
     * @When /^I rename (it) to "([^"]*)"$/
     */
    public function iRenameItTo(ScopeId $scopeId, string $name): void
    {
        $this->client->put('/api/scopes/'.$scopeId->toString(), [], ['name' => $name]);
    }

    /**
     * @Then /^I should see the "([^"]*)" scope$/
     */
    public function iShouldSeeTheScope(string $alias): void
    {
        $expectedContent = sprintf('[{"id":"@string@","name":"@string@","alias":"%s"}]', $alias);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }

    /**
     * @Then /^the scope "([^"]*)" with name "([^"]*)" should be available$/
     */
    public function theScopeWithNameShouldBeAvailable(string $alias, string $name): void
    {
        $this->asserter->assertResponseCode($this->client->response(), Response::HTTP_CREATED);
    }

    /**
     * @Then /^I should see than the name is "([^"]*)" and the alias "([^"]*)"$/
     */
    public function iShouldSeeThanTheNameIsAndTheAlias(string $name, string $alias)
    {
        $expectedContent = sprintf('{"id":"@string@","name":"%s","alias":"%s"}', $name, $alias);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }

    /**
     * @Then /^(the scope) should not be available$/
     */
    public function theScopeShouldNotBeAvailable(ScopeId $scopeId)
    {
        $this->asserter->assertResponseCode(
            $this->client->response(),
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Then /^(it) should be renamed to "([^"]*)"$/
     */
    public function itShouldBeRenamedTo(ScopeId $scopeId, string $name): void
    {
        $expectedContent = sprintf('{"id":"%s","name":"%s","alias":"@string@"}', $scopeId->toString(), $name);
        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }

    /**
     * @Then /^(the scope) should not be available neither (the role)$/
     */
    public function theScopeShouldNotBeAvailableNeitherTheRole(ScopeId $scopeId, RoleId $roleId)
    {
        $this->client->get('/api/roles/'.$roleId->toString());
        $this->asserter->assertResponseCode(
            $this->client->response(),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @Then /^I should not allowed to do it$/
     */
    public function iShouldNotAllowedToDoIt()
    {
        $this->asserter->assertResponseCode(
            $this->client->response(),
            Response::HTTP_CONFLICT
        );
    }

    /**
     * @Then /^I should receive a not found error$/
     */
    public function iShouldReceiveANotFoundError()
    {
        $this->asserter->assertResponseCode(
            $this->client->response(),
            Response::HTTP_NOT_FOUND
        );
    }
}
