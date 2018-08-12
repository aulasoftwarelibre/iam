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
use AulaSoftwareLibre\Iam\Domain\Role\Model\Role;
use AulaSoftwareLibre\Iam\Domain\Role\Model\RoleId;
use AulaSoftwareLibre\Iam\Domain\User\Model\UserId;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Response;

class UserContext implements Context
{
    /**
     * @var HttpClient
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
     * @When /^I register an account with username "([^"]*)"$/
     */
    public function iRegisterAnAccountWithUsername($username)
    {
        $this->client->post('/api/users', [], [
            'id' => UserId::generate()->toString(),
            'username' => $username,
        ]);
    }

    /**
     * @Then /^the user "([^"]*)" should be available$/
     */
    public function theUserShouldBeAvailable(string $username): void
    {
        $this->asserter->assertResponseCode($this->client->response(), Response::HTTP_CREATED);
    }

    /**
     * @When /^I browse the users$/
     */
    public function iBrowseTheUsers()
    {
        $this->client->get('/api/users');
    }

    /**
     * @Then /^I should see the "([^"]*)" username$/
     */
    public function iShouldSeeTheUsername(string $username): void
    {
        $expectedResponse = sprintf('[{"id":"@string@","username":"%s"}]', $username);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedResponse
        );
    }

    /**
     * @When /^I check (the user) details$/
     */
    public function iCheckItsDetails(UserId $userId): void
    {
        $this->client->get('/api/users/'.$userId->toString());
    }

    /**
     * @Then /^I should see than the username is "([^"]*)"$/
     */
    public function iShouldSeeThanTheUsernameIs(string $username): void
    {
        $expectedResponse = sprintf('{"id":"@string@","username":"%s","roles":[]}', $username);

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedResponse
        );
    }

    /**
     * @When /^I assign (the role) to (the user)$/
     */
    public function iAssignTheRoleToTheUser(RoleId $roleId, UserId $userId)
    {
        $this->client->put('/api/users/'.$userId->toString().'/promote/'.$roleId->toString());
    }

    /**
     * @Then /^I should see that (the user) has (the role)$/
     */
    public function iShouldSeeThatTheUserHasTheRole(UserId $userId, Role $role)
    {
        $this->client->get('/api/users/'.$userId->toString());

        $expectedResponse = sprintf('{"id":"%s","username":"@string@","roles":[{"id":"%s","scopeId":"@string@","name":"@string@"}]}', $userId->toString(), $role->roleId()->toString());

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedResponse
        );
    }

    /**
     * @When /^I remove (the role) to (the user)$/
     */
    public function iRemoveTheRoleToTheUser(RoleId $roleId, UserId $userId)
    {
        $this->client->delete('/api/users/'.$userId->toString().'/demote/'.$roleId->toString());
    }

    /**
     * @Then /^I shouldn't see that (the user) has (the role)$/
     */
    public function iShouldnTSeeThatTheUserHasTheRole(UserId $userId, RoleId $roleId)
    {
        $this->client->get('/api/users/'.$userId->toString());

        $expectedResponse = sprintf('{"id":"%s","username":"@string@","roles":[]}', $userId->toString());

        $this->asserter->assertResponse(
            $this->client->response(),
            Response::HTTP_OK,
            $expectedResponse
        );
    }
}
