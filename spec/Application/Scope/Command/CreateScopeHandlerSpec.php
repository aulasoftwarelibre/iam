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

namespace spec\AulaSoftwareLibre\Iam\Application\Scope\Command;

use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\CreateScopeHandler;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeAliasAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ScopeIdAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeAlias;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeName;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\View\ScopeView;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tests\Spec\Fixtures;

class CreateScopeHandlerSpec extends ObjectBehavior
{
    public function let(Scopes $scopes, ScopeViews $scopeViews): void
    {
        $this->beConstructedWith($scopes, $scopeViews);

        $scopes->find(Fixtures\Scope::SCOPE_ID)->willReturn(null);
        $scopeViews->ofAlias(Fixtures\Scope::ALIAS)->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateScopeHandler::class);
    }

    public function it_creates_a_scope(Scopes $scopes): void
    {
        $scopes->save(Argument::that(
            function (Scope $scope) {
                return $scope->scopeId()->equals(ScopeId::fromString(Fixtures\Scope::SCOPE_ID))
                    && $scope->name()->equals(ScopeName::fromString(Fixtures\Scope::NAME))
                    && $scope->alias()->equals(ScopeAlias::fromString(Fixtures\Scope::ALIAS));
            }
        ))->shouldBeCalled();

        $this(CreateScope::with(
            ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
            ScopeName::fromString(Fixtures\Scope::NAME),
            ScopeAlias::fromString(Fixtures\Scope::ALIAS)
        ));
    }

    public function it_checks_scope_id_does_not_exist(Scopes $scopes, Scope $scope): void
    {
        $scopes->find(ScopeId::fromString(Fixtures\Scope::SCOPE_ID))->willReturn($scope);

        $this->shouldThrow(ScopeIdAlreadyRegisteredException::class)->during('__invoke', [
            CreateScope::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                ScopeName::fromString(Fixtures\Scope::NAME),
                ScopeAlias::fromString(Fixtures\Scope::ALIAS)
            ),
        ]);
    }

    public function it_checks_short_name_is_free(ScopeViews $scopeViews): void
    {
        $scopeViews->ofAlias(Fixtures\Scope::ALIAS)->shouldBeCalled()->willReturn(
            new ScopeView(Fixtures\Scope::SCOPE_ID, Fixtures\Scope::NAME, Fixtures\Scope::ALIAS)
        );

        $this->shouldThrow(ScopeAliasAlreadyRegisteredException::class)->during('__invoke', [
            CreateScope::with(
                ScopeId::fromString(Fixtures\Scope::SCOPE_ID),
                ScopeName::fromString(Fixtures\Scope::NAME),
                ScopeAlias::fromString(Fixtures\Scope::ALIAS)
            ),
        ]);
    }
}
