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
use AulaSoftwareLibre\Iam\Application\Scope\Exception\ShortNameAlreadyRegisteredException;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Name;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ShortName;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Repository\ScopeViews;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateScopeHandlerSpec extends ObjectBehavior
{
    const SCOPE_ID = '5cd2a872-d88d-45a2-a5d2-5daa71f0d685';
    const NAME = 'Identity and Access Management';
    const SHORT_NAME = 'iam';

    public function let(Scopes $scopes, ScopeViews $scopeViews): void
    {
        $this->beConstructedWith($scopes, $scopeViews);

        $scopes->find(self::SCOPE_ID)->willReturn(null);
        $scopeViews->findOneByShortName(self::SHORT_NAME)->willReturn(null);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CreateScopeHandler::class);
    }

    public function it_creates_a_scope(Scopes $scopes): void
    {
        $scopes->save(Argument::that(
            function (Scope $scope) {
                return $scope->scopeId()->equals(ScopeId::fromString(self::SCOPE_ID))
                    && $scope->name()->equals(Name::fromString(self::NAME))
                    && $scope->shortName()->equals(ShortName::fromString(self::SHORT_NAME));
            }
        ))->shouldBeCalled();

        $this(CreateScope::with(
            ScopeId::fromString(self::SCOPE_ID),
            Name::fromString(self::NAME),
            ShortName::fromString(self::SHORT_NAME)
        ));
    }

    public function it_checks_short_name_is_free(ScopeViews $scopeViews): void
    {
        $scopeViews->findOneByShortName(self::SHORT_NAME)->shouldBeCalled()->willReturn(
            new ScopeView(self::SCOPE_ID, self::NAME, self::SHORT_NAME)
        );

        $this->shouldThrow(ShortNameAlreadyRegisteredException::class)->during('__invoke', [
            CreateScope::with(
                ScopeId::fromString(self::SCOPE_ID),
                Name::fromString(self::NAME),
                ShortName::fromString(self::SHORT_NAME)
            ),
        ]);
    }
}
