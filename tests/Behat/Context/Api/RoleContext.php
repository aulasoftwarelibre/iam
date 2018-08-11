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

class RoleContext implements Context
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
     * @When /^I add a "([^"]*)" to (it)$/
     */
    public function iAddAToIt(string $roleName, ScopeId $scopeId)
    {
        $this->client->post('/api/roles', [], [
            'id' => RoleId::generate()->toString(),
            'scopeId' => $scopeId->toString(),
            'name' => $roleName,
        ]);
    }

    /**
     * @Then /^the role "([^"]*)" in (this scope) should be available$/
     */
    public function theRoleInThisScopeShouldBeAvailable(string $roleName, ScopeId $scopeId)
    {
        $this->asserter->assertResponseCode($this->client->response(), Response::HTTP_CREATED);
    }

    /**
     * @When /^I browse the roles in (this scope)$/
     */
    public function iBrowseTheRolesInThisScope(ScopeId $scopeId)
    {
        $uri = sprintf('/api/scopes/%s/roles', $scopeId->toString());
        $this->client->get($uri);
    }

    /**
     * @Then /^I should see the "([^"]*)" role$/
     */
    public function iShouldSeeTheRole(string $roleName)
    {
        $expectedContent = sprintf('[{"id":"@string@","scopeId": "@string@","name":"%s"}]', $roleName);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }

    /**
     * @When /^I check (its) details$/
     */
    public function iCheckItsDetails(RoleId $roleId)
    {
        $this->client->get('/api/roles/'.$roleId->toString());
    }

    /**
     * @Then /^I should see than the role name is "([^"]*)"$/
     */
    public function iShouldSeeThanTheRoleNameIs(string $roleName)
    {
        $expectedContent = sprintf('{"id":"@string@","scopeId":"@string@","name":"%s"}', $roleName);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedContent
        );
    }

    /**
     * @When /^I remove (the role)$/
     */
    public function iRemoveIt(RoleId $roleId)
    {
        $this->client->delete('/api/roles/'.$roleId->toString());
    }

    /**
     * @Then /^(the role) should not be available$/
     */
    public function theRoleShouldNotBeAvailable(RoleId $roleId)
    {
        $this->client->get('/api/roles/'.$roleId->toString());

        $this->asserter->assertResponseCode(
            $this->client->response(),
            Response::HTTP_NOT_FOUND
        );
    }
}
