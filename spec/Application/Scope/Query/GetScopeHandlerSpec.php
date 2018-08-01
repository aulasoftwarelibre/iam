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

namespace spec\AulaSoftwareLibre\Iam\Application\Scope\Query;

use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScope;
use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScopeHandler;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Repository\ScopeViews;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\View\ScopeView;
use PhpSpec\ObjectBehavior;
use React\Promise\Deferred;

class GetScopeHandlerSpec extends ObjectBehavior
{
    private const SCOPE_ID = 'd5fbec06-7daf-4ba1-b788-cd48c0c9d744';

    public function let(ScopeViews $scopeViews)
    {
        $this->beConstructedWith($scopeViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetScopeHandler::class);
    }

    public function it_gets_scope_by_id(ScopeViews $scopeViews, ScopeView $scopeView)
    {
        $scopeViews->get(self::SCOPE_ID)->shouldBeCalled()->willReturn($scopeView);

        $this(GetScope::with(ScopeId::fromString(self::SCOPE_ID)))->shouldBe($scopeView);
    }

    public function it_gets_scope_by_id_deferred(ScopeViews $scopeViews, ScopeView $scopeView, Deferred $deferred)
    {
        $scopeViews->get(self::SCOPE_ID)->shouldBeCalled()->willReturn($scopeView);
        $deferred->resolve($scopeView)->shouldBeCalled();

        $this(GetScope::with(ScopeId::fromString(self::SCOPE_ID)), $deferred);
    }
}
