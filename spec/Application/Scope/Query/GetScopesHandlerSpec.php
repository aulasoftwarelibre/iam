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

use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScopes;
use AulaSoftwareLibre\Iam\Application\Scope\Query\GetScopesHandler;
use AulaSoftwareLibre\Iam\Infrastructure\ReadModel\Scope\Repository\ScopeViews;
use PhpSpec\ObjectBehavior;

class GetScopesHandlerSpec extends ObjectBehavior
{
    public function let(ScopeViews $scopeViews): void
    {
        $this->beConstructedWith($scopeViews);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetScopesHandler::class);
    }

    public function it_get_all_scopes(ScopeViews $scopeViews)
    {
        $scopeViews->findAll()->shouldBeCalled()->willReturn(['scopes']);

        $this(new GetScopes())->shouldBe(['scopes']);
    }
}
