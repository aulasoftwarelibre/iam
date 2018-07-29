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

use AulaSoftwareLibre\Iam\Application\Scope\Command\RemoveScope;
use AulaSoftwareLibre\Iam\Application\Scope\Command\RemoveScopeHandler;
use AulaSoftwareLibre\Iam\Application\Scope\Repository\Scopes;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\Scope;
use AulaSoftwareLibre\Iam\Domain\Scope\Model\ScopeId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoveScopeHandlerSpec extends ObjectBehavior
{
    private const SCOPE_ID = '5cd2a872-d88d-45a2-a5d2-5daa71f0d685';

    public function let(Scopes $scopes): void
    {
        $this->beConstructedWith($scopes);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoveScopeHandler::class);
    }

    public function it_removes_a_scope(Scopes $scopes, Scope $scope): void
    {
        $scopes->get(Argument::exact(ScopeId::fromString(self::SCOPE_ID)))->willReturn($scope);

        $scope->isRemoved()->willReturn(false);
        $scope->remove()->shouldBeCalled();
        $scopes->save($scope)->shouldBeCalled();

        $this(RemoveScope::with(
            ScopeId::fromString(self::SCOPE_ID)
        ));
    }
}
